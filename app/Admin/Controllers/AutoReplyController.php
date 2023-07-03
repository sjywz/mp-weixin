<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\MpTable;
use App\Admin\Renderable\ResourceTable;
use App\Admin\Repositories\AutoReply;
use App\Models\AutoReply as ModelsAutoReply;
use App\Models\Mp;
use App\Models\Resource;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;

class AutoReplyController extends AdminController
{
    private $status = [0=>'禁用',1=>'启用'];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AutoReply(['mp']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('mp.name','公众号');
            $grid->column('type')->using(ModelsAutoReply::$type)->badge([
                0 => 'success',
                1 => 'warning',
                2 => 'info'
            ]);
            $grid->column('key','触发事件/关键词')->display(function(){
                if($this->type == 0){
                    $content = join('&nbsp;&nbsp;',array_map(function($v){
                        return '<span class="label" style="background: black">'.$v.'</span>';
                    },explode(',',$this->key)));
                    return $content;
                }else{
                    return $this->event;
                }
            });
            $grid->column('context')->display(function(){
                $replyContent = [];
                $replyTypeList = ModelsAutoReply::$replyType;
                foreach($this->context as $v){
                    $type = $v['reply_type'];
                    $content = [
                        ['name'=>'类型','text'=>$replyTypeList[$type] ?: $type],
                        ['name'=>'内容','text'=>$v[$type]],
                    ];
                    $replyContent[] = new Card('',join('',array_map(function($v){
                        return sprintf('<div><b>%s：</b><span>%s</span></div>',$v['name'],$v['text']);
                    },$content)));
                }
                return join('',$replyContent);
            });
            $grid->column('wight');
            $grid->column('status')->using($this->status)->label();
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();

                $mplist = Mp::get()->pluck('name','appid');
                $filter->equal('appid','公众号')->width(3)->select($mplist);
                $filter->equal('type')->width(3)->select(ModelsAutoReply::$type);
                $filter->like('key')->width(3);
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new AutoReply(), function (Show $show) {
            $show->field('id');
            $show->field('type');
            $show->field('key');
            $show->field('event');
            $show->field('mp_id');
            $show->field('wight');
            $show->field('status');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new AutoReply(), function (Form $form) {
            $form->display('id');
            $form->selectTable('appid', '应用公众号')
                ->title('公众号')
                ->from(MpTable::make())
                ->model(Mp::class, 'appid', 'name')
                ->required();

            $form->radio('type')->options(ModelsAutoReply::$type)
                ->when(0, function (Form $form) {
                    $form->textarea('key');
                    $form->html('<div class="alert alert-success">关键词可添加多个，以英文逗号分割；关键词支持精准匹配；前匹配，关键词以%开头；后匹配，关键词以%结尾；模糊匹配，关键词前后加%</div>');
                })->when(2, function (Form $form) {
                    $form->text('event');
                })
                ->default(0)
                ->required();
            $form->array('context', '回复内容', function ($table) {
                $table->radio('reply_type', '类型')
                    ->options(ModelsAutoReply::$replyType)
                    ->default('text')
                    ->when('text', function ($table) {
                        $table->textarea('text', '文字');
                        // $table->html('<p>文字中支持变量替换，可用变量有：用户的openid/公众号名称/date/datetime/week/</p>');
                    })
                    ->when('image', function ($table) {
                        $table->multipleSelectTable('image', '图片')
                            ->title('图片列表')
                            ->from(ResourceTable::make(['type'=>1]))
                            ->model(Resource::class, 'id', 'name')
                            ->help('10M，支持PNG\JPEG\JPG\GIF格式');
                        $table->radio('bind','回复规则')
                            ->options([1=>'绑定',0=>'随机'])
                            ->default(1)
                            ->help('绑定表示如果用户触发过，后续触发时将使用相同的图片回复；随机则每次按图片权重随机回复');
                    })
                    ->when('voice', function ($table) {
                        $table->multipleSelectTable('voice', '语音')
                            ->title('语音列表')
                            ->from(ResourceTable::make(['type'=>3]))
                            ->model(Resource::class, 'id', 'name')
                            ->help('2M，播放长度不超过60s，支持AMR\MP3格式');
                    });
            });
            $form->divider('注：可以添加多条回复内容，用户回复关键词可回复5条，其他事件或关注最多3条');
            $form->number('wight');
            $form->radio('status')->options($this->status)->default(1);

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableViewCheck();
            $form->disableViewButton();
        })->saving(function(Form $form){
            $context = array_map(function($v){
                return array_filter($v);
            },$form->input('context'));
            $form->input('context',$context);
            if($form->type == 1){
                $form->event = 'subscribe';
            }
        });
    }
}
