<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['user_id', 'total_amount', 'dummy_address', 'invoice_path'];

    // Enkripsi otomatis kolom dummy_address di database sesuai standar Data Privasi PRD
    protected $casts = [
        'dummy_address' => 'encrypted',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}