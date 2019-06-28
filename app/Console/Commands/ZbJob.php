<?php
/**
 * Created by PhpStorm.
 * User: chw
 * Date: 2019-06-24
 * Time: 15:36
 */

namespace App\Console\Commands;


use App\Services\ZbService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Monolog\Logger;

class ZbJob extends Command
{
    protected $name = 'zb:run';
    protected $description = '执行zb';


    public function handle(ZbService $zbService): void
    {
        while (true) {
            $this->info('======== 脚本执行开始： ' . date('Y-m-d H:i:s') . ' ========');
            $handleMsg = $zbService->test();
            foreach ($handleMsg as $msg) {
                $this->info($msg);
            }
            $this->info('======== 脚本执行结束： ' . date('Y-m-d H:i:s') . ' ========');
            sleep(20);
        }
    }

}
