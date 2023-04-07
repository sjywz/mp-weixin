<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\Platform;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use App\Services\PlatformService;
use Illuminate\Support\Facades\Config;
use App\Models\Mp;
use Dcat\Admin\Layout\Column;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Layout\Row;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\Callout;
use Dcat\Admin\Widgets\Box;
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
        return Grid::make(new Platform(), function (Grid $grid) {
            $grid->model()->orderBy('id', 'desc');

            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('icon');
            $grid->column('appid');
            $grid->column('app_secret');
            $grid->column('verify_token');
            $grid->column('msg_key');
            $grid->column('desc');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append(sprintf('<a href="/admin/platform/auth/%s" target="_blank" class="btn btn-sm btn-warning">授权账号</a>',$this->id));
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
            $form->image('icon')->autoUpload();
            $form->text('appid')->required();
            $form->text('app_secret')->required();
            $form->text('verify_token')->required();
            $form->text('msg_key')->required();
            $form->textarea('desc');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    public function auth($id)
    {
        try{
            $plat = new PlatformService();
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
            $plat = new PlatformService();
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
