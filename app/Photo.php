<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    //
    protected $fillable = [
        'file'
    ];

    protected $uploads = 'http://localhost/~kuanhanchen/laravel/codehacking/public/images/';

    public function getFileAttribute($photo){

        return $this->uploads . $photo;

    }

}
