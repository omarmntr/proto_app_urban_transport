<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;


class Stop extends Model
{

    use HasSpatial;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'location',
    ];

    protected $casts = [
        'location' => Point::class
    ];

    public $timestamps = true;
}
