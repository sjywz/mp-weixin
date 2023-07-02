<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpMessage;
use App\Models\Mp;
use App\Models\MpMessage as ModelsMpMessage;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;

class MpMessageController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $message = ModelsMpMessage::with('mp')->where('sender',0);
        return Grid::make($message, function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('mp.name', '公众号');
            $grid->column('type')->label();
            $grid->column('from','发送用户');
            $grid->column('event');
            $grid->column('content', '内容')->display(function(){
                $type = $this->type;
                if($type === 'text'){
                    return $this->content;
                }
                $content = json_decode($this->content,true);
                if($type === 'location'){
                    return $content['Label'];
                }
                if($type === 'image'){
                    return sprintf('<img src="%s" style="max-width:100px"/>',$content['PicUrl']);
                }
                if($type === 'voice'){
                    return $content['MediaId'];
                }
                if($type === 'link'){
                    return sprintf('<a href="%s">%s</a>',$content['Url'],$content['Title']);
                }
            });
            $grid->column('create_time');
            $grid->column('content2', '更多')
                ->display('查看') // 设置按钮名称
                ->expand(function () {
                    $content = array_filter([
                        '消息id：'.$this->msgid,
                        '来自：'.$this->from,
                        '发送：'.$this->to,
                    ]);
                    $card = new Card('', join('<hr>',$content));
                    return "<div style='padding:10px 10px 0'>$card</div>";
                });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();

                $mplist = Mp::get()->pluck('name','appid');
                $filter->equal('appid','公众号')->width(3)->select($mplist);
                $filter->equal('type')->width(3);
                $filter->like('from','openid')->width(3);
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableEdit();
            });
            $grid->disableCreateButton();
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
        return Show::make($id, new MpMessage(), function (Show $show) {
            $show->field('id');
            $show->field('type');
            $show->field('msgid');
            $show->field('create_time');
            $show->field('from');
            $show->field('to');
            $show->field('event');
            $show->field('rest');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new MpMessage(), function (Form $form) {
            $form->display('id');
            $form->text('type');
            $form->text('msgid');
            $form->text('create_time');
            $form->text('from');
            $form->text('to');
            $form->text('event');
            $form->text('rest');
        });
    }
}
