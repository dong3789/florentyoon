<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatBoardReply extends Model
{
    use SoftDeletes;

    protected $table = 'cat_board_reply';

}
