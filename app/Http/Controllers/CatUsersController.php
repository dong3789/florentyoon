<?php

namespace App\Http\Controllers;

use App\Models\CatUsers;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\responseData;
use function App\userConvData;

class CatUsersController extends Controller
{
    use ApiResponseHelpers;

    /**
     * 1. 유저(고양이) 정보 받아오기 - 비회원 조회 불가
     *
     * 품종
     * 나이
     * 털색깔/무늬
     * 유저형태
    */
    public function getUsersData(Request $request)
    {
        $userId = Auth::getUser()->id;
        if(empty($userId)) return responseData(400, '존재하지 않는 ID 입니다.');

        $reqData = (new CatUsers())->getCatUserData($userId)->addSelect('cat_users.age')->first();
        if(empty($reqData)) return responseData(400, '존재하지 않는 사용자 입니다.');


        $convData = userConvData($reqData);

        return $this->respondWithSuccess($convData);

    }


}
