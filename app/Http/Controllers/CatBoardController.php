<?php

namespace App\Http\Controllers;

use App\Models\CatBoard;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;
use function App\boardConvData;
use function App\userConvData;

class CatBoardController extends Controller
{
    use ApiResponseHelpers;

    /**
     * 2. 질문 가져오기 - 비회원 조회 가능
     *
     * 제목
     * 내용(처음 20글자만)
     * 작성 날짜
     * 유저 정보(민감 정보 제외)
     *
     * todo:: 검색 정보(request)
     */
    public function getBoardList(Request $request)
    {
        $reqData = $request->input();

        //# todo:: 검색 관련 request 검증 고도화작업 (interface화 시키기)
//        if(isNan($reqData)) return responseData(400, 'request값 확인 요청');
//        if(empty($userId)) return responseData(400, 'request값 확인 요청');

        $reqData = (new CatBoard())->getBoardListData();

        $reqData->map(function($e){
            $e = userConvData($e);
            $e = boardConvData($e);
            return $e;
        });

        return $reqData;
    }

    /**
     * 3. 질문과 답변 가져오기 - 비회원 조회 가능
     *
     * 제목
     * 내용
     * 작성 날짜
     * 유저 정보 (민감정보 제외)
     * 답변 리스트
     * ㄴ답변 내용
     * ㄴ답변 채택 여부
     * ㄴ답변 날짜
     * ㄴ답변자 유저 정보 (민감정보 제외)
     *
     * @param $boardId
     */
    public function getBoardDetail($boardId)
    {
        $reqData = (new CatBoard())->getBoardDetailData($boardId);

        $convData = userConvData($reqData);
        $convData = boardConvData($convData);

        return $convData;
    }
}
