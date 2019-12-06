<?php

namespace App\Models\Passport;

use Illuminate\Database\Eloquent\Model;

class CommonGame extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'common_game';

    public $timestamps = true;

    public $primaryKey = 'game_id';
}
