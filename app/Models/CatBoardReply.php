<?php

namespace App\Models;

use App\Repository\BoardTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatBoardReply extends Model
{
    use SoftDeletes;
    use BoardTrait;

    protected $table = 'cat_board_reply';
    protected $fillable = ['id', 'board_id', 'reply_writer', 'reply_content', 'choose'];


    public function getBoardReplyDetailData($replyId)
    {
        $data = self::find($replyId);

        return $data;
    }


    public function createBoardReply($arrData)
    {
        $this->convModelData($arrData);
        $res = $this->save();

        return $res;
    }

    public function deleteBoardReply($replyId)
    {
        $res = self::find($replyId)->delete();

        return $res;
    }

    public function updateBoardReply($replyId, $arrData)
    {
        $model = self::find($replyId);
        $resData = $this->convModelData($arrData);
        $model->fill($resData->toArray());
        $res = $model->save();

        return $res;
    }


}
