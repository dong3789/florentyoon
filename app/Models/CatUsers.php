<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use App;
use function App\getBreedDataConv;
use function App\getMentTypeDataConv;
use function App\getSkinTypeDataConv;

class CatUsers extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    protected $table = 'cat_users';

    protected $hidden = [
        'name',
        'email',
        'password',
        'remember_token'
    ];

    /**
     *
     * @param $userId
     * @return mixed
     */
    public function getCatUserData($userId)
    {
        $data = self::
            select(
                'cat_users.id',
                'cat_users.ment_type',
                'cat_users.breed_id',
                'cat_users.skin_id',
                'cat_users.created_at'
            )
            ->leftJoin('cat_users_breed', 'cat_users_breed.id', '=', 'cat_users.id')

            //# $userId => int: 개인 | array: 다중 정보 받아올 때
            ->when(is_array($userId), function($q) use($userId) {
                $q->whereIn('cat_users.id', $userId);
            }, function($q) use($userId) {
                $q->where('cat_users.id', '=', $userId);
            });

        return $data;
    }


    public function removeToken($userId)
    {
        //# 지난 토큰 삭제
        $model = DB::table('oauth_access_tokens')->where('user_id','=', $userId);
        $mData = $model->first();
        if(!empty($mData)){
            $resId = $mData->id;
            $model->delete();
            DB::table('oauth_refresh_tokens')->where('access_token_id','=', $resId)->delete();
        }
    }
}
