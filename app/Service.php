<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['title','picture','slug','tags','content','priority','status','categoryID_FK','usersID_FK','lang','seo_id','seo_id'];
    protected $casts = [
        'picture'=> 'array'
        ];

    public function category_service()
    {
        return $this->belongsTo(CategoryService::class,'categoryID_FK');
    }
    public function images()
    {
        return $this->hasMany(ImageServices::class,'serviceID_FK');
    }
    public function seo()
    {
        return $this->belongsTo(Seo::class);
    }
}
