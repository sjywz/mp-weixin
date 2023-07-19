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
        $public_ip = file_get_contents('https://api.ipify.org');

        return Grid::make(new Mp(['platform']), function (Grid $grid) use ($public_ip){
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('icon')->image('',50,50);
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
            $grid->column('platform.name','授权平台');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            $grid->column('test','更多')
                ->display('查看')
                ->expand(function() use($public_ip){
                    $more = array_filter([
                        ['name'=>'AppSecret','text'=>$this->app_secret],
                        ['name'=>'校验Token','text'=>$this->verify_token],
                        ['name'=>'消息加解密密钥','text'=>$this->msg_key],
                        ['name'=>'简介','text'=>$this->desc],
                    ], function($v){
                        return !empty($v['text']);
                    });

                    if(!$this->plat_appid){
                        $auth = sprintf('%s/mp/%s',env('APP_URL'),$this->appid);
                        $more[] = ['name' => '服务器地址', 'text' => $auth];
                        $more[] = ['name' => 'IP白名单','text' => $public_ip];
                    }

                    $card = new Card(null, join('',array_map(function($v){
                        return '<div style="padding:10px;margin:10px;border-bottom:1px solid #efefef;"><b>'.$v['name'].'：</b><span>'.$v['text'].'</span></div>';
                    },$more)));
                    return $card;
                });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();

                $mplist = ModelsMp::get()->pluck('name','appid');
                $filter->equal('appid','公众号')->width(3)->select($mplist);
                $filter->equal('type')->width(3)->select(ModelsMp::$type);
                $filter->like('name')->width(3);
            });
            $grid->tools('<a class="btn btn-danger" href="/admin/platform">授权接入</a>');
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
