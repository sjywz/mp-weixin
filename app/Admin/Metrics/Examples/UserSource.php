<?php

namespace App\Admin\Metrics\Examples;

use App\Models\MpUser;
use Dcat\Admin\Admin;
use Dcat\Admin\Widgets\Metrics\Donut;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserSource extends Donut
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $color = Admin::color();
        $colors = [
            $color->primary(),
            $color->alpha('blue2', 0.5)
        ];

        $this->title('关注来源');
        $this->dropdown([
            '7' => '最近7天',
            '30' => '最近30天',
            '90' => '最近90天',
            '180' => '最近180天',
            '365' => '最近一年',
        ]);
        // $this->chartLabels($this->labels);
        // 设置图表颜色
        $this->chartColors($colors);
    }

    /**
     * 渲染模板
     *
     * @return string
     */
    public function render()
    {
        $this->fill();

        return parent::render();
    }

    public function handle(Request $request)
    {
        $option = $request->get('option',7);

        $startTime = strtotime('-'.($option - 1).' day');
        $startDate = date('Y-m-d',$startTime);
        $where = [['created_at','>',$startDate]];
        $mpUsers = DB::table('mp_users')
            ->where($where)
            ->selectRaw('subscribe_scene,COUNT(id) AS count')
            ->groupBy('subscribe_scene')
            ->get();

        $mpUsersCountOfScene = $mpUsers->pluck('count','subscribe_scene');
        $sScene = array_filter($mpUsers->pluck('subscribe_scene')->toArray());
        sort($sScene);

        $subScene = MpUser::$sub_scene;
        $this->chartLabels(array_map(function($v) use ($subScene){
            if($v && isset($subScene[$v]) &&  $subScene[$v]){
                return $subScene[$v];
            }
            return $v;
        },$sScene));

        $data = [];
        foreach($sScene as $v){
            $data[] = $mpUsersCountOfScene->get($v);
        }

        $this->withContent($mpUsersCountOfScene->toArray());
        $this->withChart($data);
    }

    /**
     * 设置图表数据.
     *
     * @param array $data
     *
     * @return $this
     */
    public function withChart(array $data)
    {
        return $this->chart([
            'series' => $data
        ]);
    }

    /**
     * 设置卡片头部内容.
     *
     * @param mixed $desktop
     * @param mixed $mobile
     *
     * @return $this
     */
    protected function withContent($data)
    {
        $blue = Admin::color()->alpha('blue2', 0.5);

        $subScene = MpUser::$sub_scene;
        $labelEl = [];
        foreach($data as $k => $v){
            if($k && isset($subScene[$k]) &&  $subScene[$k]){
                $k = $subScene[$k];
            }
            if(empty($k)){
                $k = '未知';
            }
            $labelEl[] = '<div class="d-flex pl-1 pr-1" style="margin-bottom: 8px">
                <div>
                    <i class="fa fa-circle" style="color: '.$blue.'"></i>
                    <span>'.$k.'</span>
                </div>
                <div style="margin-left:10px">'.$v.'</div>
            </div>';
        }
        return $this->content(join('',$labelEl));
    }
}
