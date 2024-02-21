<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;


class Stop extends Model
{

    use HasSpatial;

    protected $table = 'stops';
    protected $primaryKey = 'stop_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'osm_id', //atributte id
        'name',
        'location',
    ];

    protected $casts = [
        'location' => Point::class
    ];

    public $timestamps = true;

    
    public function routeStop(): BelongsToMany
    {
        return $this->belongsToMany(RouteStop::class,'route_id', 'route_id');
    }
}
