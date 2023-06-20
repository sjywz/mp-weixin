<?php

namespace App\Admin\Controllers;

use App\Admin\Renderable\MpTable;
use App\Admin\Renderable\ResourceTable;
use App\Admin\Repositories\AutoReply;
use App\Models\AutoReply as ModelsAutoReply;
use App\Models\Mp;
use App\Models\Resource;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AutoReplyController extends AdminController
{
    private $status = [0=>'禁用',1=>'启用'];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AutoReply(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('appid');
            $grid->column('type')->using(ModelsAutoReply::$type)->badge();
            $grid->column('key');
            $grid->column('event');
            $grid->column('context');
            $grid->column('wight');
            $grid->column('status')->using($this->status)->label();
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
            $form->selectTable('appid', '应用公众号')
                ->title('公众号')
                ->from(MpTable::make())
                ->model(Mp::class, 'appid', 'name')
                ->required();

            $form->radio('type')->options(ModelsAutoReply::$type)
                ->when(0, function (Form $form) {
                    $form->textarea('key');
                })->when(2, function (Form $form) {
                    $form->text('event');
                })
                ->default(0)
                ->required();
            $form->radio('reply_type', '回复类型')
                ->options(ModelsAutoReply::$replyType)
                ->default('text')
                ->when('text', function (Form $form) {
                    $form->textarea('context.text');
                })
                ->when('image', function (Form $form) {
                    $form->multipleSelectTable('context.image', '图片')
                        ->title('图片列表')
                        ->from(ResourceTable::make())
                        ->model(Resource::class, 'id', 'name');
                });

            $form->number('wight');
            $form->radio('status')->options($this->status)->default(1);

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
