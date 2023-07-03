<?php

namespace App\Jobs;

use App\Models\DelayMsg as ModelsDelayMsg;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class DelayMsg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $appid = $this->data['appid'];
        $event = $this->data['event'];
        $openid = $this->data['openid'];
        $msgType = $this->data['msgType'];
        $plat_appid = $this->data['plat_appid'];

        if($event === 'subscribe'){
            $delayMsg = ModelsDelayMsg::where('appid',$appid)
                ->where('status',1)
                ->get(['id','delay']);

            if($delayMsg){
                $data = [];
                foreach($delayMsg as $v){
                    $sendDate = date('Y-m-d H:i:s',(time() + $v->delay * 60));
                    $data[] = [
                        'status' => 0,
                        'msg_id' => $v->id,
                        'openid' => $openid,
                        'appid'  => $appid,
                        'plat_appid' => $plat_appid,
                        'send_time'  => $sendDate,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                DB::table('delay_reply')->insert($data);
            }
        }if($event === 'unsubscribe'){
            DB::table('delay_reply')
                ->where('appid',$appid)
                ->where('openid',$openid)
                ->delete();
        }else{
            if($msgType != 'event'){
                DB::table('delay_reply')
                    ->where('appid',$appid)
                    ->where('openid',$openid)
                    ->where('status',0)
                    ->where('send_time','>',date('Y-m-d H:i:s'))
                    ->update(['status'=>1,'updated_at'=>date('Y-m-d H:i:s')]);
            }
        }
    }
}
