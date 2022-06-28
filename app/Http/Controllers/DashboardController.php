<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }
    public function index()
    {

        $auctions = auth()->user()->auctions;
        $auctionsSorted = $auctions->sortByDesc('id');
        $auctions = $auctionsSorted;
        $bids = auth()->user()->bids;
        $bidsSorted = $bids->sortByDesc('created_at');
        $bids = $bidsSorted;
        $transactions = DB::table('transactions')
            ->where('buyer_id', '=', auth()->user()->id)
            ->orWhere('seller_id', auth()->user()->id)
            ->get();
        $transactionsSorted = $transactions->sortByDesc('id');
        $transactions = $transactionsSorted;
        //dd($transactions);
        return view('dashboard', compact('auctions', 'bids', 'transactions'));
    }
}
