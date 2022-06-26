<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;

class AuctionController extends Controller
{
    public function index()
    {

        $auctions = Auction::paginate(1);
        return view('auctions.index', [
            'auctions' => $auctions
        ]);
    }

    public function create()
    {
        return view('auctions.new_auction');
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'type' => 'required|string|max:100',
            'condition' => 'required|string|max:15',
            'hours' => 'required|numeric',
            'days' => 'required|numeric',
            'start_price' => 'required|numeric|min:1|max:9999',
            'buy_now_price' => 'required|numeric|gt:start_price|max:9999.99',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048',
        ]);

        ///calculate end time
        $current_timestamp = Carbon::now()->timestamp;
        $hours = $request->hours;
        $days = $request->days;
        $end_time_unix = $current_timestamp + $days * 24 * 60 * 60  + $hours * 60 * 60;
        $end_time = date("Y-m-d H:i:s", $end_time_unix);  ///GMT

        ///image
        $newImageName = time() . '-' . $request->name . '.' . $request->image->extension();
        $newImageName = str_replace(str_split('\\/:*?"<>| '), '', $newImageName);
        $request->image->move(public_path('images'), $newImageName);

        $request->user()->auctions()->create([
            'name' => $request->name,
            'type' => $request->type,
            'condition' => $request->condition,
            'end_time' => $end_time,
            'start_price' => $request->start_price,
            'buy_now_price' => $request->buy_now_price,
            'description' => $request->description,
            'image_path' => $newImageName,
        ]);
        return redirect()->route('dashboard');
    }

    public function show($id)
    {
        $auction = Auction::find($id);

        //Calculate when auction ends (difference between now and auction end time)
        $now = Carbon::now()->format('Y/m/d H:i:s');
        $now = new DateTime($now);
        $end = new DateTime($auction->end_time);
        $diff = $now->diff($end);
        //diff->invert (1 represents negative time period, 0 otherwise)
        //dd($diff);
        ///Obtain highest bid amount and the highest bidder of currently shown auction
        if (DB::table('bids')->where('auction_id', '=', $id)->exists()) {
            $highest_bid = DB::table('bids')
                ->where('auction_id', '=', $id)
                ->max('amount');
            $highest_bid_fastest = DB::table('bids')->where('amount', '=', $highest_bid)->min('created_at');
            $highest_bidder_id = DB::table('bids')->where('amount', '=', $highest_bid)->where('created_at', '=', $highest_bid_fastest)->value('user_id');
            $highest_bidder_username = DB::table('users')->where('id', '=', $highest_bidder_id)->value('username');
        } else {
            $highest_bid = 0;
            $highest_bidder_username = 0;
        }
        $auction = Auction::find($id);

        return view('auctions.show_auction', compact('auction', 'highest_bid', 'highest_bidder_username', 'diff'));
    }

    public function storebid(Request $request)
    {
        /*
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'type' => 'required|string|max:100',
            'condition' => 'required|string|max:15',
            'hours' => 'required|numeric',
            'days' => 'required|numeric',
            'start_price' => 'required|numeric|min:1|max:9999',
            'buy_now_price' => 'required|numeric|gt:start_price|max:9999.99',
            'description' => 'required',
            'image' => 'required|mimes:jpg,png,jpeg|max:5048',
        ]);
        */
        Bid::create([
            'auction_id' => $request->auction_id,
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
        ]);
        return redirect()->back()->with('message', 'Bid Successful !');
    }
}
