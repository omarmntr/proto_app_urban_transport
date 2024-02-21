<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
// use MatanYadaev\EloquentSpatial\Objects\Point;
// use MatanYadaev\EloquentSpatial\Traits\HasSpatial;


class Route extends Model
{

    protected $table = 'routes';
    protected $primaryKey = 'route_id';

    //use HasSpatial;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'osm_id', //atributte id
        'name', //tag official_name
        'colour', //tag = colour
    ];


    public $timestamps = true;
}
