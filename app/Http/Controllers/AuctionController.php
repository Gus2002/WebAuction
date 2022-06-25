<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index()
    {
        return view('auctions.index');
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
}
