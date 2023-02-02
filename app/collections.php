<?php

namespace App;


/**
 * 멘토 정보
 * @param $val
 */
function getMentTypeDataConv($val)
{
    $res = collect();
    $res->put('mentor', '멘토'); //# mentor
    $res->put('mentee', '멘티'); //# mentee
    $data = $res->get($val);

    return $data ?? null;
}

/**
 * 품종 정보
 * @param $val
 */
function getBreedDataConv($val)
{
    $res = collect();
    $res->put(1, '터키쉬 앙고라');        //# Turkish Angora
    $res->put(2, '샴');                //# Siamese
    $res->put(3, '스코티쉬 폴드');        //# Scottish Fold
    $res->put(4, '러시안 블루');         //# Russian Blue
    $res->put(5, '먼치킨');             //# Munchkin
    $res->put(6, '코리안 숏헤어');        //# Korean Short Hair
    $res->put(7, '스노우슈');           //# Snowshoe
    $data = $res->get($val);

    return $data ?? null;
}



/**
 * 털색/무늬 정보
 * @param $val
 */
function getSkinTypeDataConv($val)
{
    $res = collect();
    $res->put(1, '흰색');     //# white
    $res->put(2, '회색');     //# grey
    $res->put(3, '검정색');    //# black
    $res->put(4, '삼색');     //# tricolor
    $res->put(5, '턱시도');    //# tuxedo
    $res->put(6, '고등어');    //# mackerel
    $res->put(7, '치즈');     //# cheese
    $data = $res->get($val);

    return $data ?? null;
}


/**
 * 질문 타입
 *
 * @param $val
 */
function getBoardTypeDataConv($val)
{
    $res = collect();
    $res->put(1, '사료');       //# feed
    $res->put(2, '그루밍');      //# grooming
    $res->put(3, '집사후기');     //# review
    $data = $res->get($val);

    return $data ?? null;
}
