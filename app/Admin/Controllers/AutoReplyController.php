<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\AutoReply;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AutoReplyController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AutoReply(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('type');
            $grid->column('key');
            $grid->column('event');
            $grid->column('mp_id');
            $grid->column('wight');
            $grid->column('status');
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
            $form->text('type');
            $form->text('key');
            $form->text('event');
            $form->text('mp_id');
            $form->text('wight');
            $form->text('status');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
