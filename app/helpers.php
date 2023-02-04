<?php

namespace App;


use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;

if(!function_exists('responseData')){
    function responseData($code = 200, $data = '', $message = ''){

        $arrResult = [];
        $arrResult['code'] = $code;

        if($data != '') {
            $arrResult['data'] = $data;
        }

        if($message != '') {
            $arrResult['message'] = $message;
        }

        return response()->json($arrResult, 200);
    }
}


if(!function_exists('userConvData')){
    /**
     * $data : 변환할 데이터
     * $type : true=>답변 로딩 금지, false=>답변 로딩 허용
     * @param $data
     * @param false $type
     * @return mixed
     */
    function userConvData($data, $type=false)
    {
        if(!empty($data->ment_type)) $data->ment_str     = getMentTypeDataConv($data->ment_type);
        if(!empty($data->breed_id))  $data->breed_str    = getBreedDataConv($data->breed_id);
        if(!empty($data->skin_id))   $data->skin_str     = getSkinTypeDataConv($data->skin_id);

        if(!$type && !empty($data->cat_board_reply)){
            $data->cat_board_reply->map(function($q){
                $q = userConvData($q);
                return $q;
            });
        }

        return $data;
    }
}


if(!function_exists('boardConvData')){
    function boardConvData($data)
    {
        if(!empty($data->category_type_id)) $data->category_type_str = getBoardTypeDataConv($data->category_type_id);

        return $data;
    }
}
