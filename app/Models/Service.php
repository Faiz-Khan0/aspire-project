<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';

    protected mixed $name;

    protected $fillable = ['name'];
    public function subservices()
{
    return $this->hasMany(SubService::class);
}
}
