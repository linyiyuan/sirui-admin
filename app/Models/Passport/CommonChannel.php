<?php

namespace App\Models\Passport;

use Illuminate\Database\Eloquent\Model;

class CommonChannel extends Model
{
    /**
     * 数据库表
     * @var
     */
    protected $table = 'common_channel';

    public $timestamps = true;

    public $primaryKey = 'resource_id';
}
