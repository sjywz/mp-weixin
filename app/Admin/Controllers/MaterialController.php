<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Material;
use App\Models\Mp;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Storage;

class MaterialController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Material(['mp']), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('mp.name','公众号');
            // $grid->column('name');
            $grid->column('media_id')->width('120px');
            $grid->column('url')->image('',150);

            $grid->column('url')->display(function ($url) {
                $url = Storage::disk(config('admin.upload.disk'))->url($url);
                if($this->type == 'image'){
                    return "<img data-action='preview-img' src='$url' class='img img-thumbnail' style='width:150px'/>";
                }
                if($this->type === 'video'){
                    return "<div style='overflow:auto'><video style='width:200px' src='$url' controls></video></div>";
                }
                return "<div style='overflow:auto'><audio src='$url' controls></audio></div>";
            });

            // $grid->column('content');
            $grid->column('is_temp')->using([
                0 => '否',
                1 => '是',
            ])->badge([
                0 => 'success',
                1 => 'danger'
            ]);
            $grid->column('type')->label();
            $grid->column('created_at');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();

                $mplist = Mp::get()->pluck('name','appid');
                $filter->equal('appid','公众号')->width(3)->select($mplist);
                $filter->equal('media_id')->width(3);
                $filter->equal('is_temp')->width(3)->radio([
                    0 => '否',
                    1 => '是',
                ]);
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
        return Show::make($id, new Material(), function (Show $show) {
            $show->field('id');
            $show->field('media_id');
            $show->field('name');
            $show->field('url');
            $show->field('content');
            $show->field('is_temp');
            $show->field('appid');
            $show->field('mid');
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
        return Form::make(new Material(), function (Form $form) {
            $form->display('id');
            $form->text('media_id');
            $form->text('name');
            $form->text('url');
            $form->text('content');
            $form->text('is_temp');
            $form->text('appid');
            $form->text('mid');
            $form->text('type');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
