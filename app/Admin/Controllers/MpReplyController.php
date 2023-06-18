<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpReply;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MpReplyController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new MpReply(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('type');
            $grid->column('content');
            $grid->column('status');
            $grid->column('wight');
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
        return Show::make($id, new MpReply(), function (Show $show) {
            $show->field('id');
            $show->field('type');
            $show->field('content');
            $show->field('status');
            $show->field('wight');
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
        return Form::make(new MpReply(), function (Form $form) {
            $form->display('id');
            $form->text('type');
            $form->text('content');
            $form->text('status');
            $form->text('wight');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
