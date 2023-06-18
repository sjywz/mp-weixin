<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MpReply;
use App\Models\MpReply as ModelsMpReply;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class MpReplyController extends AdminController
{
    private $status = [0=>'禁用',1=>'启用'];
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new MpReply(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('title');
            $grid->column('type')->using(ModelsMpReply::$type)->label();
            $grid->column('content');
            $grid->column('status')->using($this->status)->badge([
                1 => 'success',
                0 => 'danger',
            ]);
            $grid->column('wight');
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
        return Show::make($id, new MpReply(), function (Show $show) {
            $show->field('id');
            $show->field('title');
            $show->field('type');
            $show->field('content');
            $show->field('status');
            $show->field('wight');
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
        return Form::make(new MpReply(), function (Form $form) {
            $form->display('id');
            $form->text('title')->required();
            $form->radio('type')
                ->options(ModelsMpReply::$type)
                ->when(0, function(Form $form){
                    $form->textarea('content');
                })
                ->when(1, function(Form $form){
                    $form->textarea('content', '请选择图片');
                })
                ->when(2, function(Form $form){
                    $form->textarea('content', '请选择图文');
                })
                ->when(3, function(Form $form){
                    $form->textarea('content', '请选择语音');
                })
                ->when(4, function(Form $form){
                    $form->textarea('content', '请选择视频');
                })
                ->when(5, function(Form $form){
                    $form->textarea('content', '请选择音乐');
                })
                ->when(6, function(Form $form){
                    $form->textarea('content', '请选择菜单');
                })
                ->required();
            $form->radio('status')->options([0=>'禁用',1=>'启用'])->default(1);
            $form->number('wight');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
