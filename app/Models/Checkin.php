<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model
{
    protected $table = 'check_in';


    protected $fillable = ['user_id', 'subservice_id', 'service_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subservice()
    {
        return $this->belongsTo(SubService::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
