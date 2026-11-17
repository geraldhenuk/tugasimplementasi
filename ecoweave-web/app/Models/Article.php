<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Article extends Model {
    protected $fillable = ['category_id', 'title', 'content', 'image_url'];
    public function category() { return $this->belongsTo(Category::class); }
}