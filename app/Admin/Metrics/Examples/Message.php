<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Mp;
use Dcat\Admin\Widgets\Metrics\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Message extends Round
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('接受/回复消息');
        $this->subTitle('最近30天');
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        $option = $request->get('option',30);

        $startTime = strtotime('-'.($option - 1).' day');
        $startDate = date('Y-m-d',$startTime);
        $where = [['create_time','>',$startDate]];

        $message = DB::table('mp_message')
            ->where($where)
            ->selectRaw('appid,COUNT(id) AS count')
            ->groupBy('appid')
            ->get();

        $messageCountOfAppid = $message->pluck('count','appid')->toArray();
        $mpNameList = [];
        $mpOfAppid = [];
        if($messageCountOfAppid){
            $appids = array_keys($messageCountOfAppid);
            $mp = Mp::whereIn('appid',$appids)->get(['appid','name']);

            $mpOfAppid = $mp->pluck('name','appid')->toArray();
            $mpNameList = array_map(function($v) use ($mpOfAppid){
                return $mpOfAppid[$v];
            },$appids);
        }

        $sum = array_sum($messageCountOfAppid);
        $this->withChart(array_map(function($v) use ($sum){
            return round($v/$sum * 100,2);
        },array_values($messageCountOfAppid)));
        $this->chartLabels($mpNameList);
        $this->chartTotal('总计', $sum);
        $this->withContent($messageCountOfAppid, $mpOfAppid);
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
            'series' => $data,
        ]);
    }

    /**
     * 卡片内容.
     *
     * @param int $finished
     * @param int $pending
     * @param int $rejected
     *
     * @return $this
     */
    public function withContent($data, $mpOfAppid)
    {
        $labelel = [];
        foreach($data as $k => $v){
            $labelel[] = '<div class="col-12 d-flex flex-column flex-wrap text-center" style="max-width: 220px">
                <div class="chart-info d-flex justify-content-between mb-1 mt-2" >
                    <div class="series-info d-flex align-items-center">
                        <i class="fa fa-circle-o text-bold-700 text-primary"></i>
                        <span class="text-bold-600 ml-50">'.($mpOfAppid[$k] ?? '').'</span>
                    </div>
                    <div class="product-result">
                        <span class="btn btn-sm">'.$v.'</span>
                    </div>
                </div>
            </div>';
        }
        return $this->content(join('',$labelel));
    }
}
