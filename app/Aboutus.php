<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Aboutus extends Model
{
    protected $fillable = ['usersID_FK','content','lang','seo_id'];

    public function seo()
    {
        return $this->belongsTo(Seo::class);
    }
}
