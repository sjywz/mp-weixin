<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\PlatformEvent;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class PlatformEventController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new PlatformEvent(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('appid');
            $grid->column('create_time');
            $grid->column('info_type');
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
        return Show::make($id, new PlatformEvent(), function (Show $show) {
            $show->field('id');
            $show->field('appid');
            $show->field('create_time');
            $show->field('info_type');
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
        return Form::make(new PlatformEvent(), function (Form $form) {
            $form->display('id');
            $form->text('appid');
            $form->text('create_time');
            $form->text('info_type');
            $form->text('rest');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
