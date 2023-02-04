<?php

namespace App\Http\Controllers;

use App\Http\Requests\BoardReplyRequest;
use App\Http\Requests\BoardValidRequest;
use App\Models\CatBoard;
use App\Models\CatBoardReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function App\responseData;

class CatBoardReplyController extends Controller
{

    /**
     * 7. 답변 작성
     * 답변이 3개 이상 달린 경우 작성 불가
     *
     * @param $boardId
     * @param BoardValidRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createBoardReplyData($boardId, BoardReplyRequest $request)
    {
        $valData = $request->validated();

        $userId = Auth::getUser()->id;
        if(empty($userId)) return responseData(400, '존재하지 않는 ID 입니다.');

        $arrData = [
            'board_id'      => $boardId,
            'reply_content' => $valData['reply_content'],
            'reply_writer'  => $userId
        ];

        try {
            $resData = (new CatBoard())->getBoardDetailData($arrData);
            if(empty($resData)) return responseData(400, null, "이미 삭제된 질문입니다.");
            if(count($resData->cat_board_reply) >= 3) return responseData(400, null, "답변 작성한 개수를 초과했습니다.");

            //# 답변 등록 진행
            $res = (new CatBoardReply())->createBoardReply($arrData);

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
     * 8. 답변 수정
     * 답변이 채택된 이후엔 수정이 불가
     *
     * @param $replyId
     * @param BoardValidRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateBoardReplyData($replyId, BoardReplyRequest $request)
    {
        $userId = Auth::getUser()->id;
        $valData = $request->validated();

        $arrData = [
            'reply_content' => $valData['reply_content'],
        ];

        try {
            $resReplyData = (new CatBoardReply())->getBoardReplyDetailData($replyId);
            if(empty($resReplyData)) return responseData(400, null, "이미 삭제된 답변 입니다.");
            if($resReplyData->choose) return responseData(400, null, "채택된 답변은 수정 불가능 합니다.");
            if($resReplyData->reply_writer != $userId) return responseData(400, null, "수 할 수 없습니다.");

            $resData = (new CatBoard())->getBoardDetailData($resReplyData->board_id);
            if(empty($resData)) return responseData(400, null, "이미 삭제된 질문입니다.");

            //# 수정 진행
            $res = (new CatBoardReply())->updateBoardReply($replyId, $arrData);

            if($res){
                return responseData(200, null, "수정 성공했습니다.");
            }else{
                return responseData(400, null, "수정 실패했습니다.");
            }

        }catch (\Exception $e){
            return responseData(400, $e->getMessage(), "수정 오류가 발생했습니다.");
        }
    }



    /**
     * 9. 답변 삭제
     * 답변이 채택된 이후에는 삭제 불가
     *
     * @param $replyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeBoardReplyData($replyId)
    {
        $userId = Auth::getUser()->id;

        try {
            $resReplyData = (new CatBoardReply())->getBoardReplyDetailData($replyId);
            if(empty($resReplyData)) return responseData(400, null, "이미 삭제된 답변 입니다.");
            if($resReplyData->choose) return responseData(400, null, "채택된 답변은 삭제 불가능 합니다.");
            if($resReplyData->reply_writer != $userId) return responseData(400, null, "삭제 할 수 없습니다.");

            $resData = (new CatBoard())->getBoardDetailData($resReplyData->board_id);
            if(empty($resData)) return responseData(400, null, "이미 삭제된 질문입니다.");

            //# 삭제 진행
            $res = (new CatBoardReply())->deleteBoardReply($replyId);

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
     * 10. 답변 채택
     * @param $boardId
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function setChooseReplyData($replyId)
    {
        $userId = Auth::getUser()->id;

        $arrData = [
            'choose'=> true
        ];

        try {
            $resReplyData = (new CatBoardReply())->getBoardReplyDetailData($replyId);
            if(empty($resReplyData)) return responseData(400, null, "이미 삭제된 답변 입니다.");
            if($resReplyData->choose) return responseData(400, null, "이미 선택 된 답변 입니다.");

            $resData = (new CatBoard())->getBoardDetailData($resReplyData->board_id);
            if(empty($resData)) return responseData(400, null, "이미 삭제된 질문입니다.");
            if($resData->board_writer != $userId) return responseData(400, null, "채택 할 수 없습니다.");
            if($resData->cat_board_reply->sum('choose') > 0) return responseData(400, null, "이미 채택 된 답변이 있습니다.");

            //# 채택 진행
            $res = (new CatBoardReply())->chooseBoardReply($replyId, $arrData);

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
