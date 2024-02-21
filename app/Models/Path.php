<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use MatanYadaev\EloquentSpatial\Objects\MultiPoint;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

use Illuminate\Database\Eloquent\Relations\HasMany;


class Path extends Model
{

    use HasSpatial;

    protected $table = 'path';
    protected $primaryKey = 'path_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'osm_id', //atributte id
        'coordinates',
    ];

    protected $casts = [
        'coordinates' => MultiPoint::class
    ];

    public $timestamps = true;

    public function routePath(): HasMany
    {
        return $this->belongsToMany(RoutePath::class,'path_id', 'path_id');
    }

}
