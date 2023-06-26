<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\SwitchGridView;
use App\Admin\Repositories\Resource;
use App\Models\Resource as ModelsResource;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Storage;

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
            if (request()->get('_view_') !== 'list') {
                $grid->view('admin.grid.custom-img');
                // $grid->setActionClass(Grid\Displayers\Actions::class);
            }

            $grid->column('id')->sortable();
            $grid->column('name');

            $grid->column('path')->display(function ($path) {
                $url = Storage::disk(config('admin.upload.disk'))->url($path);
                if($this->type == 1){
                    return "<img data-action='preview-img' src='$url' class='img img-thumbnail' style='width:150px'/>";
                }
                if($this->type === 2){
                    return "<div style='overflow:auto'><video style='width:200px' src='$url' controls></video></div>";
                }
                return "<div style='overflow:auto'><audio src='$url' controls></audio></div>";
            });

            $grid->column('desc');
            $grid->column('type')->using(ModelsResource::$type)->label([
                1 => 'primary',
                2 => 'success',
                3 => 'info',
                4 => 'warning'
            ]);
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();
                $filter->like('name')->width(3);
                $filter->equal('type')->select(ModelsResource::$type)->width(3);
            });

            $grid->tools([
                new SwitchGridView(),
            ]);

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
            });

            $grid->disableBatchDelete();
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
            $form->radio('type')
                ->options(ModelsResource::$type)
                ->default(1);
            $form->text('name')->required();
            $form->file('path','文件')->autoUpload()->required();
            $form->number('wight','权重');
            $form->textarea('desc');

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableViewButton();
            $form->disableViewCheck();
        });
    }
}
