<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Mp;
use App\Models\Mp as ModelsMp;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use App\Admin\RowAction\UpdateMpInfo;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;

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
            $grid->column('icon')->image(50,50);
            $grid->column('plat_appid');
            $grid->column('appid');
            $grid->column('type')->using(ModelsMp::$type)->badge([
                0 => 'primary',
                1 => 'info'
            ]);
            $grid->column('account_type')->display(function($type){
                return ModelsMp::$accountType[$this->type][$type] ?? '未知';
            })->label('#666');
            $grid->column('status')->using(ModelsMp::$accountStatus)->label([
                1 => 'success',
                14 => 'danger',
                16 => 'default',
                18 => 'warning',
                19 => 'danger'
            ]);
            $grid->column('desc');
            $grid->column('test','更多')->display('查看')->expand(function(){
                $content = [
                    '<div>'.$this->app_secret.'</div>',
                    '<div>'.$this->verify_token.'</div>',
                    '<div>'.$this->msg_key.'</div>'
                ];
                $card = new Card(null, join('',$content));
                return "<div style='padding:10px 10px 0'>$card</div>";
            });
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append(new UpdateMpInfo(ModelsMp::class));
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
