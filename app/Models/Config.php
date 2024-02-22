<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;



class Config extends Model
{

    protected $table = 'config';
    protected $primaryKey = 'config_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', //atributte id
        'value',
        'description',
    ];



    public $timestamps = true;

   
}
