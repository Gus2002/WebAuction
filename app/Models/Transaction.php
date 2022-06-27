<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'auction_id',
        'bid_id',
        'buyer_id',
        'seller_id',
        'amount',
        'comment',
        'rating',
    ];

    public function Bid()
    {
        return $this->belongsTo(User::class);
    }

    public function Auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
