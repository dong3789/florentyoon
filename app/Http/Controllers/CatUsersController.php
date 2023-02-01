<?php

namespace App\Http\Controllers;

use App\Models\CatUsers;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatUsersController extends Controller
{
    use ApiResponseHelpers;

    public function getUsersData(Request $request) {
        //# 비회원 조회 불가
        $userId = Auth::getUser()->id;

        $findData = (new CatUsers())->getCatUserData($userId);
        dd($findData);
        return $this->respondWithSuccess($findData);
    }
}
