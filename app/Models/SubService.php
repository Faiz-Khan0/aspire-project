<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{

    protected $fillable = ['name', 'service_id'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function checkins()
    {
        return $this->hasMany(Checkin::class);
    }
}
