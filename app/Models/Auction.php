<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'end_time',
        'condition',
        'type',
        'start_price',
        'buy_now_price',
        'image_path',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
