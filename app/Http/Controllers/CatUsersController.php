<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatUsersController extends Controller
{
    public function getUsersData(Request $request) {
        //# 비회원 조회 불가
        $data = Auth::user();
        dd($data);
    }
}
