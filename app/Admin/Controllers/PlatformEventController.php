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
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('appid');
            $grid->column('info_type')->label();
            $grid->column('plat_appid');
            $grid->column('create_time');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->equal('info_type');
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
        });
    }
}
