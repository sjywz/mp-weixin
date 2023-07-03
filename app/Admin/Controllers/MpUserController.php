<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpUser;
use App\Models\Mp;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MpUserController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $mplist = Mp::get()->pluck('name','appid');

        return Grid::make(new MpUser(['mp']), function (Grid $grid) use ($mplist) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('mp.name','公众号');
            $grid->column('openid');
            $grid->column('unionid');
            $grid->column('subscribe')->using([
                0 => '取关',
                1 => '关注',
            ])->label([
                0 => 'warning',
                1 => 'success'
            ]);
            $grid->column('subscribe_time');
            // $grid->column('remark');
            // $grid->column('groupid');
            // $grid->column('tagid_list');
            // $grid->column('subscribe_scene');
            // $grid->column('language');
            // $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) use ($mplist){
                $filter->panel();
                $filter->expand();

                $filter->equal('appid','公众号')->width(3)->select($mplist);
                $filter->like('openid')->width(3);
                $filter->like('unionid')->width(3);
                $filter->equal('subscribe')->width(3)->radio([
                    0 => '取关',
                    1 => '关注',
                ]);
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $grid->disableBatchActions();
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
        return Show::make($id, new MpUser(), function (Show $show) {
            $show->field('id');
            $show->field('appid');
            $show->field('openid');
            $show->field('unionid');
            $show->field('subscribe');
            $show->field('subscribe_time');
            $show->field('remark');
            $show->field('groupid');
            $show->field('tagid_list');
            $show->field('subscribe_scene');
            $show->field('language');
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
        return Form::make(new MpUser(), function (Form $form) {
            $form->display('id');
            $form->text('appid');
            $form->text('openid');
            $form->text('unionid');
            $form->text('subscribe');
            $form->text('subscribe_time');
            $form->text('remark');
            $form->text('groupid');
            $form->text('tagid_list');
            $form->text('subscribe_scene');
            $form->text('language');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
