<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpMessage;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

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
            $grid->column('id')->sortable();
            $grid->column('type');
            $grid->column('msgid');
            $grid->column('create_time');
            $grid->column('from');
            $grid->column('to');
            $grid->column('event');
            $grid->column('rest');
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
        return Show::make($id, new MpMessage(), function (Show $show) {
            $show->field('id');
            $show->field('type');
            $show->field('msgid');
            $show->field('create_time');
            $show->field('from');
            $show->field('to');
            $show->field('event');
            $show->field('rest');
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
        return Form::make(new MpMessage(), function (Form $form) {
            $form->display('id');
            $form->text('type');
            $form->text('msgid');
            $form->text('create_time');
            $form->text('from');
            $form->text('to');
            $form->text('event');
            $form->text('rest');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
