@extends('layouts.app')

@section('content')
    <div class="flex justify-center mt-20">
        <div class="mr-20 w-5/12 bg-white p-6 rounded-lg"> <img src="{{ asset('images/' . $auction->image_path) }}"
                alt="">
            <div class="mt-5 bg-white p-1 rounded-lg">
                <p>Description/specifications:</p>
                <p>{{ $auction->description }}</p>
            </div>
        </div>
        <div class="flex w-3/12 bg-white p-6 rounded-lg justify-center">
            <ul class="flex flex-col">
                <li class="mb-2 flex justify-center">
                    <h1 class="mb-3 font-bold text-2xl">{{ $auction->name }}</h1>
                </li>
                <li class="mb-5 flex justify-center">
                    <p>Seller: {{ $auction->user->username }}</p>
                </li>
                <li class="mb-1 flex justify-center">
                    <p>Type: {{ $auction->type }}</p>
                </li>
                <li class="mb-10 flex justify-center">
                    <p>Condition: {{ $auction->condition }}</p>
                </li>
                <li class="mb-1 flex justify-center">
                    <p>Starting price: {{ $auction->start_price }}</p>
                </li>
                <li class="mb-1 flex justify-center">
                    @if ($highest_bid == 0)
                        <p>No bids yet</p>
                    @else
                        <p>Current highest bid: {{ $highest_bid }} EUR</p>
                    @endif
                </li>
                <li class="mb-10 flex justify-center">
                    @if ($highest_bid > 0)
                        <p>Highest bidder: {{ $highest_bidder_username }}</p>
                    @endif
                </li>
                <li class="mb-10 flex justify-center">
                    @auth
                        <form action={{ '/show/' . $auction->id }} method="post">
                            @csrf
                            <div class="mb-4">
                                <input type="text" name="amount" id="amount" placeholder="Amount"
                                    class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('bid') border-red-500 @enderror"
                                    value="">
                                <input type="hidden" name="auction_id" id="auction_id" value="{{ $auction->id }}">
                                @error('bid')
                                    <div class="text-red-500 mt-2 text-sm">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                                    Make a bid
                                </button>
                                @if (session()->has('message'))
                                    <div class="bg-green-500 p-1 rounded-lg mt-5 text-white text-center">
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                            </div>
                        </form>
                    @endauth

                </li>
                <li class="mb-5 mt-5 flex justify-center">
                    <p>Buy now price: {{ $auction->buy_now_price }}</p>
                </li>
                @auth
                    <li class="mb-10 flex justify-center">
                        <form action={{ '/show/' . $auction->id }} method="post">
                            @csrf
                            <input type="hidden" name="auction_id" id="auction_id" value="{{ $auction->id }}">
                            <input type="hidden" name="amount" id="amount" value="{{ $auction->buy_now_price }}">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                                Buy now
                            </button>
                        </form>
                    </li>
                @endauth
                <li class="p-5 mb-5 bg-gray-200 rounded-2xl flex justify-center">
                    @if ($diff->invert == 1 || $auction->buy_now_price == $highest_bid)
                        <p>Auction has ended</p>
                    @else
                        <div class="flex flex-col">
                            <p class="text-center">Auction ends in:</p>
                            <div>{{ $diff->d }} days
                                {{ $diff->h }} hours {{ $diff->i }}
                                minutes {{ $diff->s }} seconds</div>
                        </div>
                    @endif
                </li>
                @guest
                    @if ($diff->invert == 0 && $auction->buy_now_price !== $highest_bid)
                        <p class="p-5 bg-gray-200 rounded-2xl flex justify-center">Register or login to bid on this auction</p>
                    @endif
                @endguest

            </ul>
        </div>
    </div>
@endsection
