<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardValidRequest;
use App\Models\CatBoard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\boardConvData;
use function App\responseData;
use function App\userConvData;

class CatBoardController extends Controller
{
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
        $arrData = [
            'page' => $request->input('page', 9)
        ];
        //# todo:: 검색 관련 request 검증 고도화작업 (interface화 시키기)
        $reqData = (new CatBoard())->getBoardListData($arrData);

        $convData = $reqData->map(function($e){
            $e = userConvData($e);
            $e = boardConvData($e);
            return $e;
        });

        return responseData(200, $convData, "조회 성공했습니다.");
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

        return responseData(200, $convData, "조회 성공했습니다.");
    }

    /**
     * 4. 질문 등록
     *
     * 제목
     * 내용
     * 질문 타입
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setBoardCreate(BoardValidRequest $request)
    {
        $valData = $request->validated();

        $userId = Auth::getUser()->id;
        if(empty($userId)) return responseData(400, '존재하지 않는 ID 입니다.');

        $arrData = [
          'title'               => $valData['title'],
          'content'             => $valData['content'],
          'category_type_id'    => $valData['category_type_id'],
          'writer'              => $userId
        ];

        try {
            $res = (new CatBoard())->createBoard($arrData);

            if($res){
                return responseData(200, null, "등록 성공했습니다.");
            }else{
                return responseData(400, null, "등록 실패했습니다.");
            }

        }catch (\Exception $e){
            return responseData(400, $e->getMessage(), "등록 오류가 발생했습니다.");
        }
    }


    /**
     * 5. 질문 삭제
     * 질문 답변이 달린 후에는 삭제 불가
     *
     * @param $boardId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeBoardData($boardId)
    {
        try {
            $resData = (new CatBoard())->getBoardDetailData($boardId);

            if(empty($resData)) return responseData(400, null, "이미 삭제된 질문입니다.");
            if(count($resData->cat_board_reply) > 0) return responseData(400, null, "답변이 달린 질문은 삭제할 수 없습니다.");

            //# 삭제 진행
            $res = (new CatBoard())->deleteBoard($boardId);

            if($res){
                return responseData(200, null, "삭제 성공했습니다.");
            }else{
                return responseData(400, null, "삭제 실패했습니다.");
            }

        }catch (\Exception $e){
            return responseData(400, $e->getMessage(), "삭제 오류가 발생했습니다.");
        }
    }


    /**
     * 6. 질문 수정
     * 질문 답변이 달린 후에는 수 불가
     *
     * @param $boardId
     * @param BoardValidRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBoardData($boardId, BoardValidRequest $request)
    {
        $valData = $request->validated();

        $arrData = [
            'title' => $valData['title'],
            'content' => $valData['content'],
            'category_type_id' => $valData['category_type_id'],
        ];

        try {
            $resData = (new CatBoard())->getBoardDetailData($boardId);

            if(empty($resData)) return responseData(400, null, "이미 삭제된 질문입니다.");
            if(count($resData->cat_board_reply) > 0) return responseData(400, null, "답변이 달린 질문은 수정할 수 없습니다.");

            //# 수정 진행
            $res = (new CatBoard())->updateBoard($boardId, $arrData);

            if($res){
                return responseData(200, null, "수정 성공했습니다.");
            }else{
                return responseData(400, null, "수정 실패했습니다.");
            }

        }catch (\Exception $e){
            return responseData(400, $e->getMessage(), "수정 오류가 발생했습니다.");
        }
    }

}
