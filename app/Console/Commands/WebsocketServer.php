<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;
use Log;

class WebsocketServer extends Command
{

    private $worker = null;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'start websocket server for accept client connect';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        global $argv;
        $arg = $this->argument('action');
        $argv[1] = $argv[2];
        $argv[2] = isset($argv[3]) ? "-{$argv[3]}" : '';
        switch ($arg) {
            case 'start':
                $this->start();
                break;
            case 'stop':
                break;
            case 'restart':
                break;
            case 'reload':
                break;
            case 'status':
                break;
            case 'connections':
                break;
        }

    }

    private function start(){
        // 使用websocket协议, 监听端口, 等待客户端发起websocket连接
        $this->worker = new Worker('websocket://0.0.0.0:'. config('bid.websocket_server_external_port'));
        // 保存客户端连接
        $this->worker->uidConnections = [];
        $this->worker->count = 1;
        $this->worker->onWorkerStart = function ($worker) {
            // 创建内部连接, 用于接收扫码后, php服务器推送的消息
            $inner_text_worker = new Worker('tcp://0.0.0.0:'. config('bid.tcp_server_internal_port'));
            $inner_text_worker->onMessage = function ($connection, $buffer) {
                Log::alert('workerman inner accept message : ' . $buffer);
                $data = json_decode($buffer, true);
                $uid = $data['scene'];
                $token = $data['token'];
                // 向浏览器推送用户标识
                $res = $this->sendMessageByUid($uid, $token);
                Log::alert('workerman reply message to uid : '. $uid .'..$openid..'. $token);
                $mess = $res ? 'ok' : 'fail';
                Log::alert('workerman reply message : ' . $mess);
                // 通知php客户端推送结果
                $connection->send($mess);
            };
            $inner_text_worker->listen();
        };

        $this->worker->onMessage = function ($connection, $data) {
            Log::alert('workerman accept browser data : ' . $data);
            if (!isset($connection->uid)) {
                $connection->uid = $data;
                $this->worker->uidConnections[$connection->uid] = $connection;
                return;
            }
        };

        $this->worker->onClose = function ($connection) {
            if (isset($connection->uid)) {
                Log::alert('workerman close browser : ' . $connection->uid);
                unset($this->worker->uidConnections[$connection->uid]);
            }
        };

        Worker::runAll();
    }

    /**
     * 消息广播
     * @param $message
     */
    private function broadCast($message)
    {
        global $worker;
        foreach ($worker->uidConnections as $connection) {
            $connection->send($message);
        }
    }

    private function sendMessageByUid($uid, $message)
    {
        Log::alert(json_encode($this->worker->uidConnections));
        if (isset($this->worker->uidConnections[$uid])) {
            $connection = $this->worker->uidConnections[$uid];
            $connection->send($message);
            return true;
        }
        return false;
    }
}
