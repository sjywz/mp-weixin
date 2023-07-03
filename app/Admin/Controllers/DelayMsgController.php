<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\MpTable;
use App\Admin\Renderable\ResourceTable;
use App\Admin\Repositories\DelayMsg;
use App\Models\AutoReply;
use App\Models\Mp;
use App\Models\Resource;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;

class DelayMsgController extends AdminController
{
    private $status = [0=>'禁用',1=>'启用'];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new DelayMsg(['mp']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('mp.name');

            $grid->column('content')->display(function(){
                $content = [$this->content];
                $replyContent = [];
                $replyTypeList = AutoReply::$replyType;
                foreach($content as $v){
                    $type = $v['reply_type'];
                    $value = $v[$type] ?? '';
                    $content = [['name'=>'类型','text'=>$replyTypeList[$type] ?: $type]];
                    if(is_array($value)){
                        $content[] = ['name'=>'内容','text'=>join(',',$value)];
                    }else{
                        $content[] = ['name'=>'内容','text'=>$value];
                    }
                    $replyContent[] = new Card('',join('',array_map(function($v){
                        return sprintf('<div><b>%s：</b><span>%s</span></div>',$v['name'],$v['text']);
                    },$content)));
                }
                return join('',$replyContent);
            });

            $grid->column('delay');
            $grid->column('status')->using($this->status);
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

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
        return Show::make($id, new DelayMsg(), function (Show $show) {
            $show->field('id');
            $show->field('appid');
            $show->field('content');
            $show->field('delay');
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
        return Form::make(new DelayMsg(), function (Form $form) {
            $form->display('id');
            $form->selectTable('appid', '公众号')
                ->title('公众号')
                ->from(MpTable::make())
                ->model(Mp::class, 'appid', 'name')
                ->required();

            $form->radio('content.reply_type', '类型')
                ->options(AutoReply::$replyType)
                ->default('text')
                ->required()
                ->when('text', function ($table) {
                    $table->textarea('content.text', '文字');
                    // $table->html('<p>文字中支持变量替换，可用变量有：用户的openid/公众号名称/date/datetime/week/</p>');
                })
                ->when('image', function ($table) {
                    $table->multipleSelectTable('content.image', '图片')
                        ->title('图片列表')
                        ->from(ResourceTable::make(['type'=>1]))
                        ->model(Resource::class, 'id', 'name')
                        ->help('10M，支持PNG\JPEG\JPG\GIF格式');
                    $table->radio('content.bind','回复规则')
                        ->options([1=>'绑定',0=>'随机'])
                        ->default(1)
                        ->help('绑定表示如果用户触发过，后续触发时将使用相同的图片回复；随机则每次按图片权重随机回复');
                })
                ->when('voice', function ($table) {
                    $table->multipleSelectTable('content.voice', '语音')
                        ->title('语音列表')
                        ->from(ResourceTable::make(['type'=>3]))
                        ->model(Resource::class, 'id', 'name')
                        ->help('2M，播放长度不超过60s，支持AMR\MP3格式');
                });
            $form->number('delay','间隔')->required()
                ->attribute('min', 1)
                ->attribute('max', 2880);
            $form->radio('status')->options($this->status)->default(1);

            $form->display('created_at');
            $form->display('updated_at');
        })->saving(function(Form $form){
            $content = array_filter($form->input('content'));
            $form->input('content',$content);
        });
    }
}
