<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpMenu;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MpMenuController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new MpMenu(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('type');
            $grid->column('url');
            $grid->column('key');
            $grid->column('media_id');
            $grid->column('appid');
            $grid->column('pagepath');
            $grid->column('article_id');
            $grid->column('parent_id');
            $grid->column('appid');
            $grid->column('mid');
            $grid->column('status');
            $grid->column('group_index');
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
        return Show::make($id, new MpMenu(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('type');
            $show->field('url');
            $show->field('key');
            $show->field('media_id');
            $show->field('appid');
            $show->field('pagepath');
            $show->field('article_id');
            $show->field('parent_id');
            $show->field('appid');
            $show->field('mid');
            $show->field('status');
            $show->field('group_index');
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
        return Form::make(new MpMenu(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('type');
            $form->text('url');
            $form->text('key');
            $form->text('media_id');
            $form->text('appid');
            $form->text('pagepath');
            $form->text('article_id');
            $form->text('parent_id');
            $form->text('appid');
            $form->text('mid');
            $form->text('status');
            $form->text('group_index');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
