<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Platform;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Services\WeixinService;
use Illuminate\Support\Facades\Config;
use App\Models\Mp;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\Card;

class PlatformController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $public_ip = file_get_contents('https://api.ipify.org');

        return Grid::make(new Platform(), function (Grid $grid) use ($public_ip){
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('icon')->image('',100);
            $grid->column('appid');
            $grid->column('app_secret');
            // $grid->column('verify_token');
            // $grid->column('msg_key');
            // $grid->column('desc');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            $grid->column('content', '对接信息')
                ->display('查看') // 设置按钮名称
                ->expand(function () use ($public_ip) {
                    $content = [
                        ['title'=>'授权事件接收','value'=>sprintf('%s/platauth/%s',env('APP_URL'),$this->appid)],
                        ['title'=>'消息与事件接收','value'=>sprintf('%s/platmsg/%s/$APPID$',env('APP_URL'),$this->appid)],
                        ['title'=>'消息校验Token','value'=>$this->verify_token],
                        ['title'=>'消息加解密Key','value'=>$this->msg_key],
                        ['title'=>'授权发起页/公众号开发/小程序服务器/小程序业务域名','value'=>env('APP_URL')],
                        ['title'=>'IP白名单','value'=>$public_ip]
                    ];
                    $card = new Card(null, join('',array_map(function($v){
                        return '<div style="padding:10px;margin:10px;border-bottom:1px solid #efefef;"><b>'.$v['title'].'：</b><span>'.$v['value'].'</span></div>';
                    },$content)));
                    return $card;
                });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->panel();
                $filter->expand();
                $filter->like('name')->width(3);
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableView();
                $actions->disableDelete();
                $actions->append(sprintf('<a href="/admin/platform/auth/%s" target="_blank" class="btn btn-sm btn-warning">授权账号</a>',$this->id));
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
        return Show::make($id, new Platform(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('icon');
            $show->field('appid');
            $show->field('app_secret');
            $show->field('verify_token');
            $show->field('msg_key');
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
        return Form::make(new Platform(), function (Form $form) {
            $form->display('id');
            $form->text('name')->required();
            $form->image('icon')->autoUpload()->autoSave(false);
            $form->text('appid')->required();
            $form->text('app_secret')->required();
            $form->text('verify_token')->required();
            $form->text('msg_key')->required();
            $form->textarea('desc');

            $form->display('created_at');
            $form->display('updated_at');

            $form->disableViewCheck();
            $form->disableViewButton();
            $form->disableDeleteButton();
        });
    }

    public function auth($id)
    {
        try{
            $plat = new WeixinService();
            $app = $plat->getApp($id);
            $server = $app->getServer();

            $base = Config::get('app.url');
            $callback = $base.'/admin/platform/call/'.$id;
            $url = $app->createPreAuthorizationUrl($callback);
            return response("<script>window.location.href='$url';</script>")->header('Content-Type','text/html');
        }catch(\Exception $e){
            exit($e->getMessage());
        }
    }

    public function call(Content $content, $id)
    {
        try{
            $plat = new WeixinService();
            $app = $plat->getApp($id);
            $account = $app->getAccount();
            $pAppid = $account->getAppId();

            $server = $app->getServer();

            $auth_code = request()->get('auth_code');
            if($auth_code){
                $authorization = $app->getAuthorization($auth_code);
                $aAppid = $authorization->getAppId();
                $accessToken = $authorization->getAccessToken();
                $refreshToken = $authorization->getRefreshToken();

                $authorizationInfo = $authorization->authorization_info;
                $expires_in = $authorizationInfo['expires_in'];
                $func_info  = $authorizationInfo['func_info'];

                $mpinfo = Mp::where('appid',$aAppid)
                    ->where('plat_appid',$pAppid)
                    ->first();

                if($mpinfo){
                    $data = [
                        'refresh_token' => $refreshToken,
                        'func_info' => json_encode($func_info)
                    ];
                    Mp::where('id',$mpinfo->id)->update($data);
                }else{
                    $data = [
                        'name' => $aAppid,
                        'appid' => $aAppid,
                        'refresh_token' => $refreshToken,
                        'plat_appid' => $pAppid,
                        'func_info' => json_encode($func_info)
                    ];
                    Mp::create($data);
                }
                $alert = Alert::make(sprintf('APPID：%s',$aAppid), '授权成功')->success();
            }else{
                throw new \Exception('预授权码不存在，请重新操作');
            }
        }catch(\Exception $e){
            $error = $e->getMessage();
            $alert = Alert::make($error, '授权失败')->warning();
        }

        return $content->header('授权')
            ->description('授权结果')
            ->body(function (Row $row) use ($alert) {
                $row->column(12, function (Column $column) use ($alert) {
                    $card = Card::make(sprintf('<a class="btn btn-sm btn-success" href="%s">查看</a>&nbsp;&nbsp;<a class="btn btn-sm btn-danger" href="%s">继续授权</a>','/admin/mp','/admin/platform'));
                    $column->row($alert);
                    $column->row($card);
                });
            });
    }
}
