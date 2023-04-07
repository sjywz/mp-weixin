<?php

namespace App\Admin\RowAction;

use Dcat\Admin\Admin;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;
use App\Services\PlatformService;
use App\Models\Mp;

class UpdateMpInfo extends RowAction
{
    protected $model;

    public function __construct(string $model = null)
    {
        $this->model = $model;
    }

    /**
     * 标题
     *
     * @return string
     */
    public function title()
    {
        return '更新信息';
    }

    /**
     * 设置确认弹窗信息，如果返回空值，则不会弹出弹窗
     *
     * 允许返回字符串或数组类型
     *
     * @return array|string|void
     */
    public function confirm()
    {
        return [
            '您是否确定更新当前账号信息？',
            $this->row->no,
        ];
    }

    protected function setupHtmlAttributes()
    {
        // 添加class
        $this->addHtmlClass('btn btn-sm btn-info mr-1');
        parent::setupHtmlAttributes();
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return \Dcat\Admin\Actions\Response
     */
    public function handle(Request $request)
    {
        $id = $this->getKey();
        $model = $request->get('model');

        $userid = Admin::user()->id;
        $mpinfo = $model::find($id);

        if(empty($mpinfo)){
            return $this->response()->error('公众号/小程序信息不存在');
        }

        $plat = new PlatformService();
        $app = $plat->getApp($mpinfo->plat_appid);
        $api = $app->getClient();

        $account = $app->getAccount();
        $pAppid = $account->getAppId();

        $appid = $mpinfo->appid;
        $response = $api->postJson('/cgi-bin/component/api_get_authorizer_info', [
            'component_appid' => $pAppid,
            'authorizer_appid' => $appid,
        ]);

        $content = json_decode($response->getContent(),true);
        $authorizer_info = $content['authorizer_info'];

        $nick_name = $authorizer_info['nick_name'];
        $head_img = $authorizer_info['head_img'];
        $user_name = $authorizer_info['user_name'];
        $alias = $authorizer_info['alias'];
        $qrcode_url = $authorizer_info['qrcode_url'];
        $principal_name = $authorizer_info['principal_name'];
        $signature = $authorizer_info['signature'];
        $account_status = $authorizer_info['account_status'];
        $miniProgramInfo = $authorizer_info['MiniProgramInfo'] ?? null;
        $service_type_info = $authorizer_info['service_type_info']['id'];
        $verify_type_info = $authorizer_info['verify_type_info']['id'];

        $data = [
            'name' => $nick_name,
            'icon' => $head_img,
            'origin_id' => $user_name,
            'principal_name' => $principal_name,
            'desc' => $signature,
            'status' => $account_status,
            'type' => empty($miniProgramInfo)?0:1,
            'account_type' => $service_type_info
        ];

        Mp::where('id',$mpinfo->id)->update($data);
        return $this->response()->success("更新成功")->refresh();
    }

    /**
     * 设置要POST到接口的数据
     *
     * @return array
     */
    public function parameters()
    {
        return [
            'model' => $this->model,
        ];
    }
}
