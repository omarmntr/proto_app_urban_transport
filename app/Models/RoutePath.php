<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
// use MatanYadaev\EloquentSpatial\Objects\Point;
// use MatanYadaev\EloquentSpatial\Traits\HasSpatial;


class RoutePath extends Model
{

    protected $table = 'route_path';
    protected $primaryKey = 'route_path_id';

    //use HasSpatial;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'route_id',
        'path_id',
        'order_path', //use array_reverse before inserting, use index of arrray for this
        'location',
    ];


    public $timestamps = true;
}
