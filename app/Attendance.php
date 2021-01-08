<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $table = 'CHECKINOUT';

    public $timestamps = false;

    public function student()
    {
        return $this->belongsTo('App\Student', 'USERID');
    }

}
