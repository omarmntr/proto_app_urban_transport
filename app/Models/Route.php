<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;


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

    public function routeStop(): BelongsToMany
    {
        return $this->belongsToMany(RouteStop::class,'route_id', 'route_id');
    }
    public function routePath(): BelongsToMany
    {
        return $this->belongsToMany(RoutePath::class,'route_id', 'route_id');
    }
}
