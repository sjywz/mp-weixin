<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Resource;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class ResourceController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Resource(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('path');
            $grid->column('desc');
            $grid->column('type');
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
        return Show::make($id, new Resource(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('path');
            $show->field('desc');
            $show->field('type');
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
        return Form::make(new Resource(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('path');
            $form->text('desc');
            $form->text('type');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
