<?php
/**
 * 认真编码 快乐生活
 * User: helloJiu
 * Date: 2018/11/28
 * Time: 9:37
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request){
        return view('index');
    }
}