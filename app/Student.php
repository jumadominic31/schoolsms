<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Staudenmeir\EloquentParamLimitFix\ParamLimitFix;

class Student extends Model
{
    protected $table = 'USERINFO';

    protected $primaryKey = 'USERID';

    public $timestamps = false;

    // public function attendance()
    // {
    //     return $this->hasMany('App\Attendance');
    // }
}
