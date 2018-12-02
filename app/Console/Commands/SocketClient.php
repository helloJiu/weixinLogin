<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;

class SocketClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:socket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push socket message';

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
        $req = ['scene' => 'uid1', 'openid' => 'openid123'];
        $req = json_encode($req);
        $ip = config('bid.websocket_server_ip');
        $port = config('bid.tcp_server_internal_port');
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        $result = socket_connect($socket, $ip, $port);
        if ($result < 0) {
            throw new InternalSocketConnectException();
        }

        socket_write($socket, $req, 8192);
        $out = socket_read($socket, 8192);
        if ($out == 'fail') {
            Log::alert('向浏览器推送信息失败....');
            Log::alert('失败信息: ' . $req);
        }
        socket_close($socket);
    }
}
