<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;



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

    public function route(): HasMany
    {
        return $this->hasMany(Route::class,'route_id', 'route_id');
    }

    public function path(): HasMany
    {
        return $this->hasManyThrough(Path::class,'path_id', 'path_id');
    }
}
