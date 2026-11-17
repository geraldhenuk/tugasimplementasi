<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Interaction extends Model {
    protected $fillable = ['user_id', 'item_id', 'item_type', 'interaction_type', 'rating_value'];
}