<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use App\Models\Transaction;

class AuctionController extends Controller
{
    public function index()
    {

        $auctions = Auction::orderBy('end_time', 'desc')->paginate(4);
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

        if (!DB::table('auctions')->where('id', '=', $id)->exists()) return redirect()->route('auctions');
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
            $highest_bid_fastest = DB::table('bids')->where('auction_id', '=', $id)->where('amount', '=', $highest_bid)->min('created_at');
            $highest_bidder_id = DB::table('bids')->where('auction_id', '=', $id)->where('amount', '=', $highest_bid)->where('created_at', '=', $highest_bid_fastest)->value('user_id');
            $highest_bidder_username = DB::table('users')->where('id', '=', $highest_bidder_id)->value('username');
        } else {
            $highest_bid = 0;
            $highest_bidder_username = 0;
            $highest_bidder_id = 0;
        }
        $auction = Auction::find($id);

        return view('auctions.show_auction', compact('auction', 'highest_bid', 'highest_bidder_username', 'highest_bidder_id', 'diff'));
    }

    public function storebid(Request $request)
    {

        $this->validate($request, [
            'amount' => 'required|numeric',
        ]);

        if ($request->amount > auth()->user()->balance) return redirect()->back()->with('message', 'Bid unsuccessful - insufficient balance');
        $auction = Auction::find($request->auction_id);
        if (DB::table('bids')->where('auction_id', '=', $request->auction_id)->exists()) {
            $highest_bid = DB::table('bids')
                ->where('auction_id', '=', $request->auction_id)
                ->max('amount');
            if ($request->amount < $highest_bid) return redirect()->back()->with('message', 'Bid unsuccessful - your bid is lower than current highest bid');
            //$highest_bid_fastest = DB::table('bids')->where('amount', '=', $highest_bid)->min('created_at');
            //$highest_bidder_id = DB::table('bids')->where('amount', '=', $highest_bid)->where('created_at', '=', $highest_bid_fastest)->value('user_id');
            //if ($highest_bidder_id == auth()->user()->id) return redirect()->back()->with('message', 'Bid unsuccessful - you already are the highest bidder');
        }

        if ($request->amount < $auction->start_price) return redirect()->back()->with('message', 'Bid unsuccessful - your bid is lower than starting price');
        if ($request->amount > $auction->buy_now_price) return redirect()->back()->with('message', 'Bid unsuccessful - your bid is higher than buy now price');
        Bid::create([
            'auction_id' => $request->auction_id,
            'user_id' => auth()->user()->id,
            'amount' => $request->amount,
        ]);
        return redirect()->back()->with('message', 'Bid successful');
    }

    public function createtransaction($id)
    {
        if (!auth()->check()) return redirect()->route('login');
        $auction = Auction::find($id);
        $highest_bid = DB::table('bids')
            ->where('auction_id', '=', $auction->id)
            ->max('amount');
        $highest_bid_fastest = DB::table('bids')->where('auction_id', '=', $id)->where('amount', '=', $highest_bid)->min('created_at');
        $highest_bidder_id = DB::table('bids')->where('auction_id', '=', $id)->where('amount', '=', $highest_bid)->where('created_at', '=', $highest_bid_fastest)->value('user_id');

        $now = Carbon::now()->format('Y/m/d H:i:s');
        $now = new DateTime($now);
        $end = new DateTime($auction->end_time);
        $diff = $now->diff($end);

        if (auth()->user()->id != $highest_bidder_id || ($diff->invert == 0 && $auction->buy_now_price != $highest_bid)) return redirect('/show/' . $auction->id);
        return view('auctions.transaction', compact('auction', 'highest_bid'));
    }

    public function storetransaction(Request $request)
    {
        if (DB::table('transactions')->where('auction_id', '=', $request->auction_id)->exists()) return redirect('/show/' . $request->auction_id);
        $auction = Auction::find($request->auction_id);
        $highest_bid = DB::table('bids')
            ->where('auction_id', '=', $auction->id)
            ->max('amount');
        $highest_bid_fastest = DB::table('bids')->where('auction_id', '=', $request->auction_id)->where('amount', '=', $highest_bid)->min('created_at');
        $highest_bid_id = DB::table('bids')->where('auction_id', '=', $request->auction_id)->where('amount', '=', $highest_bid)->where('created_at', '=', $highest_bid_fastest)->value('id');

        Transaction::create([
            'auction_id' => $request->auction_id,
            'bid_id' => $highest_bid_id,
            'buyer_id' => auth()->user()->id,
            'seller_id' => $auction->user_id,
            'amount' => $highest_bid,
        ]);
        $buyer = User::find(auth()->user()->id);
        $buyer->balance = ($buyer->balance - $highest_bid);
        $buyer->save();
        $seller = User::find($auction->user_id);
        $seller->balance = ($seller->balance + $highest_bid);
        $seller->save();

        return redirect('/show/' . $request->auction_id);
    }

    public function showupdate($id)
    {
        $auction = Auction::find($id);
        if (!auth()->check() || auth()->user()->id != $auction->user_id) return redirect()->route('auctions');
        return view('auctions.update', compact('auction'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'auction_id' => 'required|exists:auctions,id',
            'name' => 'required|string|max:191',
            'type' => 'required|string|max:100',
            'condition' => 'required|string|max:15',
            'start_price' => 'required|numeric|min:1|max:9999',
            'buy_now_price' => 'required|numeric|gt:start_price|max:9999.99',
            'description' => 'required',
        ]);
        $auction = Auction::find($request->auction_id);
        if (!auth()->check() || auth()->user()->id != $auction->user_id) return redirect()->route('auctions');
        if ($auction->bids->toArray()) return redirect()->route('dashboard')->with('message', 'Update failed - auction has bids');
        $auction->name = $request->name;
        $auction->type = $request->type;
        $auction->condition = $request->condition;
        $auction->start_price = $request->start_price;
        $auction->buy_now_price = $request->buy_now_price;
        $auction->description = $request->description;
        if ($request->end_now) {
            $end_time_unix = time() + 20;
            $auction->end_time = gmdate('Y/m/d H:i:s', $end_time_unix);
        }
        $auction->save();
        return redirect()->route('dashboard')->with('message', 'Update successful');
    }

    public function destroy(Auction $auction)
    {
        if (!auth()->check() || $auction->user->id != auth()->user()->id) return redirect()->route('auctions');
        $auction->delete();
        return back();
    }
}
