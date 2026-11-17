<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model {
    public $timestamps = false; // Tabel user_preferences tidak memakai created_at/updated_at
    protected $fillable = ['user_id', 'category_id'];
    public function category() { return $this->belongsTo(Category::class); }
}