<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpMessage;
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
        return Grid::make(new MpMessage(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('type')->label();
            $grid->column('appid');
            // $grid->column('msgid');
            // $grid->column('from');
            // $grid->column('to');
            $grid->column('event');
            // $grid->column('event_key');
            // $grid->column('plat_appid');
            // $grid->column('content')->display(function($content){
            //     return "<div style='width:500px;overflow:auto'>$content</div>";
            // });
            // $grid->column('rest');
            $grid->column('create_time');
            $grid->column('content', '更多')
                ->display('查看') // 设置按钮名称
                ->expand(function () {
                    $content = array_filter([
                        '消息id：'.$this->msgid,
                        '来自：'.$this->from,
                        '发送：'.$this->to,
                        $this->event_key,
                        $this->plat_appid,
                        $this->content,
                        $this->rest,
                    ]);
                    $card = new Card('', join('<hr>',$content));
                    return "<div style='padding:10px 10px 0'>$card</div>";
                });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('type');
                $filter->equal('msgid');
                $filter->equal('event');
                $filter->equal('appid');
                $filter->equal('plat_appid');
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
