<?php

namespace App\Admin\Metrics\Examples;

use App\Models\MpUser;
use Dcat\Admin\Widgets\Metrics\Line;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewUsers extends Line
{
    /**
     * 初始化卡片内容
     *
     * @return void
     */
    protected function init()
    {
        parent::init();

        $this->title('新增用户');
        $this->dropdown([
            '7' => '最近7天',
            '30' => '最近30天',
            '90' => '最近90天',
            '180' => '最近180天',
            '365' => '最近一年',
        ]);
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
        $option = $request->get('option',7);

        $startTime = strtotime('-'.($option - 1).' day');
        $startDate = date('Y-m-d',$startTime);
        $where = [['created_at','>',$startDate]];
        $mpUsers = DB::table('mp_users')
            ->where($where)
            ->selectRaw('DATE(created_at) AS date,COUNT(id) AS count')
            ->groupBy('date')
            ->get();

        $mpUsersCount = DB::table('mp_users')->where($where)->count(['id']);
        $mpUsersCountOfDate = $mpUsers->pluck('count','date');

        $countList = [];
        for($i = $option; $i > 0; $i--){
            $sTime = strtotime('-'.($i - 1).' day');
            $countList[] = $mpUsersCountOfDate->get(date('Y-m-d',$sTime),0);
        }

        $this->withContent($mpUsersCount);
        $this->withChart($countList);
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
            'series' => [
                [
                    'name' => $this->title,
                    'data' => $data,
                ],
            ],
        ]);
    }

    /**
     * 设置卡片内容.
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex justify-content-between align-items-center mt-1" style="margin-bottom: 2px">
    <h2 class="ml-1 font-lg-1">{$content}</h2>
    <span class="mb-0 mr-1 text-80">{$this->title}</span>
</div>
HTML
        );
    }
}
