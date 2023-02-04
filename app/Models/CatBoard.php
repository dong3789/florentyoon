<?php

namespace App\Models;

use App\Repository\BoardTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CatBoard extends Model
{
    use SoftDeletes;
    use BoardTrait;

    protected $table = 'cat_board';
    protected $fillable = ['id', 'category_type_id', 'writer', 'title', 'content'];


    /**
     * 관계형 Databases
     * @return mixed
     */
    public function cat_board_reply()
    {
        return $this->hasMany(CatBoardReply::class, 'board_id', 'board_id');
    }

    /**
     * Board Query
     * @return mixed
     */
    private function getBoardQuery()
    {
        $query = self::select(
                'cat_board.id as board_id',                 //# 질문 id
                'cat_board.category_type_id',               //# 질문 타입
                'cat_board.writer as board_writer',         //# 질문 작성자
                'cat_board.title as board_title',           //# 질문 제목
                'cat_users.id as users_id',                 //# 유저 id
                'cat_users.ment_type',                      //# 유저 형태
                'cat_users.breed_id',                       //# 품종
                'cat_users.skin_id',                        //# 털색깔/무늬
                'cat_board.updated_at as board_updated_at', //# 질문 작성날짜
            )->addSelect(
                DB::raw('substr(cat_board.content, 1, 20) as content')
            )
            ->join('cat_users', 'cat_users.id', '=', 'cat_board.writer')
            ->whereNull('cat_board.deleted_at');
        return $query;
    }


    public function getBoardListData($request)
    {
        $data = $this->getBoardQuery()->paginate($request['page']);

        return $data;
    }


    public function getBoardDetailData($boardId)
    {
        $data = $this->getBoardQuery()
            ->where('cat_board.id', '=', $boardId)
            ->with(['cat_board_reply' => function($q){
                $q->select(
                    'cat_board_reply.id as reply_id',       //# 답변 id
                    'cat_board_reply.board_id',             //# 질문 id
                    'cat_board_reply.reply_content',        //# 답변내용
                    'cat_board_reply.reply_writer',         //# 답변 유저 id
                    'cat_board_reply.choose',               //# 답변 채택 유무
                    'cat_users.id as users_id',             //# 답변 유저 id
                    'cat_users.ment_type',                  //# 답변 유저 타입
                    'cat_users.breed_id',                   //# 답변 유저 품종
                    'cat_users.skin_id',                    //# 답변 유저 털색깔/무늬
                    'cat_board_reply.updated_at as reply_updated_at' //# 답변 작성날짜
                )
                ->join('cat_users', 'cat_users.id', '=', 'cat_board_reply.reply_writer')
                ->get();
            }])
            ->first();

        return $data;
    }


    public function createBoard($arrData)
    {
        $this->convModelData($arrData);
        $res = $this->save();

        return $res;
    }

    public function deleteBoard($boardId)
    {
        $res = self::find($boardId)->delete();

        return $res;
    }

    public function updateBoard($boardId, $arrData)
    {
        $model = self::find($boardId);
        $resData = $this->convModelData($arrData);
        $model->fill($resData->toArray());
        $res = $model->save();

        return $res;
    }


}
