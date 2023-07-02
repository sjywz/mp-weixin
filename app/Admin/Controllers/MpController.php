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
            $grid->column('icon')->image('',50,50);
            // $grid->column('plat_appid');
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
            // $grid->column('desc');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            $grid->column('test','更多')->display('查看')->expand(function(){
                $content = [
                    '<div><b>平台APPID</b>:'.$this->plat_appid.'</div>',
                    '<div><b>Secret</b>:'.$this->app_secret.'</div>',
                    '<div><b>Verify_Token</b>:'.$this->verify_token.'</div>',
                    '<div><b>Msg_key</b>:'.$this->msg_key.'</div>',
                    '<div><b>描述</b>:'.$this->desc.'</div>',
                ];
                $card = new Card(null, join('',$content));
                return "<div style='padding:10px 10px 0'>$card</div>";
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();

                $mplist = ModelsMp::get()->pluck('name','appid');
                $filter->equal('appid','公众号')->width(3)->select($mplist);
                $filter->equal('type')->width(3)->select(ModelsMp::$type);
                $filter->like('name')->width(3);
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                if($this->plat_appid){
                    $actions->disableDelete();
                    $actions->disableEdit();
                    $actions->append(new UpdateMpInfo(ModelsMp::class));
                }
            });
            $grid->disableBatchActions();
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
            $form->image('icon')->autoUpload()->autoSave(false);
            $form->text('appid')->required();
            $form->text('app_secret')->required();
            $form->text('verify_token')->required();
            $form->text('msg_key');
            $form->radio('type')->options(ModelsMp::$type)->default(0);
            $form->textarea('desc');

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableViewCheck();
            $form->disableViewButton();
        })->saving(function($form){
            if($form->isCreating()){
                $appid = $form->appid;
                $mp = ModelsMp::where('appid',$appid)->first(['id','appid']);
                if($mp){
                    return $form->response()->error('公众号/小程序已存在，不能重复添加');
                }
            }
        });
    }
}
