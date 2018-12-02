<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $jsonArr = ['status' => 1, 'statusinfo' => '', 'data' => ''];

    public function jsonResOk($data = '', $options=0) {

        $this->jsonArr['status'] = 0;
        $this->jsonArr['data'] = $data;

        return response()->json($this->jsonArr, 200, [], $options);
    }

    public function jsonResErr($statusinfo = 'system_error') {

        $this->jsonArr['statusinfo'] = $statusinfo;
        return response()->json($this->jsonArr);
    }

}
