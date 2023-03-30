<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Mp;
use App\Models\Mp as ModelsMp;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MpController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Mp(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('icon');
            $grid->column('pid');
            $grid->column('appid');
            $grid->column('app_secret');
            $grid->column('verify_token');
            $grid->column('msg_key');
            $grid->column('type')->using(ModelsMp::$type);
            $grid->column('desc');
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
        return Show::make($id, new Mp(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('icon');
            $show->field('pid');
            $show->field('appid');
            $show->field('app_secret');
            $show->field('verify_token');
            $show->field('msg_key');
            $show->field('type');
            $show->field('desc');
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
        return Form::make(new Mp(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->image('icon')->autoUpload();
            $form->text('appid');
            $form->text('app_secret');
            $form->text('verify_token');
            $form->text('msg_key');
            $form->radio('type')->options(ModelsMp::$type);
            $form->textarea('desc');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
