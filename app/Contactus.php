<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contactus extends Model
{
    protected $fillable = ['tel','email','mobile','fax','postal_code','address','usersID_FK','google_map','lang','seo_id'];
    public function seo()
    {
        return $this->belongsTo(Seo::class);
    }
}
