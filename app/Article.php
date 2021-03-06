<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['popular','title','picture','slug','url','tags','content','priority','status','categoryID_FK','usersID_FK','lang','seo_id'];

    protected $casts = [
      'picture'=>'array'
    ];
    public function category_articles()
    {
        return $this->belongsTo(CategoryArticle::class,'categoryID_FK');
    }
    public function seo()
    {
        return $this->belongsTo(Seo::class);
    }
}
