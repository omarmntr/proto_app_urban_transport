<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
// use MatanYadaev\EloquentSpatial\Objects\Point;
// use MatanYadaev\EloquentSpatial\Traits\HasSpatial;


class RouteStop extends Model
{

    protected $table = 'route_stop';
    protected $primaryKey = 'route_stop_id';

    //use HasSpatial;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'route_id',
        'stop_id',
        'order_stop', //use array_reverse before inserting, use index of arrray for this
        'location',
    ];


    public $timestamps = true;
}
