<?php

namespace App;


if(!function_exists('responseData')){
    function responseData($code=null, $msg=null)
    {
        $response = [
            'code' => $code,
            'message' => $msg
        ];
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}


if(!function_exists('userConvData')){
    function userConvData($data)
    {
        if(isset($data->ment_type)) $data->ment_str = getMentTypeDataConv($data->ment_type);
        if(isset($data->breed_id)) $data->breed_str = getBreedDataConv($data->breed_id);
        if(isset($data->skin_id)) $data->skin_str = getSkinTypeDataConv($data->skin_id);

        if(isset($data->cat_board_reply)){
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
        if(isset($data->category_type_id)) $data->category_type_str = getBoardTypeDataConv($data->category_type_id);

        return $data;
    }
}
