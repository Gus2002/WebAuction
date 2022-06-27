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
                        <p>No bids</p>
                    @else
                        <p>Current highest bid: {{ $highest_bid }} EUR</p>
                    @endif
                </li>
                <li class="mb-10 flex justify-center">
                    @if ($highest_bid > 0)
                        <p>Highest bidder: {{ $highest_bidder_username }}</p>
                    @endif
                </li>
                @auth
                    @if (auth()->user()->id == $auction->user_id)
                    @else
                        <li class="mb-10 flex justify-center">
                            @auth
                                @if ($diff->invert == 1 || $auction->buy_now_price == $highest_bid)
                                @else
                                    <form action={{ '/show/' . $auction->id }} method="post">
                                        @csrf
                                        <div class="mb-4">
                                            <input type="hidden" name="auction_id" id="auction_id" value="{{ $auction->id }}">
                                            <input type="text" name="amount" id="amount" placeholder="Amount"
                                                class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('bid') border-red-500 @enderror"
                                                value="">
                                            @error('amount')
                                                <div class="text-red-500 mt-2 text-sm">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div>
                                            <button type="submit"
                                                class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                                                Make a bid
                                            </button>
                                            @if (session()->has('message'))
                                                @if (str_starts_with(session()->get('message'), 'Bid unsuccessful'))
                                                    <div class="bg-red-500 p-1 rounded-lg mt-5 text-white text-center">
                                                        {{ session()->get('message') }}
                                                    </div>
                                                @else
                                                    <div class="bg-green-500 p-1 rounded-lg mt-5 text-white text-center">
                                                        {{ session()->get('message') }}
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </form>
                                @endif
                            @endauth
                        @endauth
                    </li>

                    <li class="mb-5 mt-5 flex justify-center">
                        <p>Buy now price: {{ $auction->buy_now_price }}</p>
                    </li>

                    @auth
                        @if (auth()->user()->id == $auction->user_id)
                        @else
                            <li class="mb-10 flex justify-center">
                                @if ($diff->invert == 1 || $auction->buy_now_price == $highest_bid)
                                @else
                                    <form action={{ '/show/' . $auction->id }} method="post">
                                        @csrf
                                        <input type="hidden" name="auction_id" id="auction_id" value="{{ $auction->id }}">
                                        <input type="hidden" name="amount" id="amount"
                                            value="{{ $auction->buy_now_price }}">
                                        <button type="submit"
                                            class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                                            Buy now
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @endif
                    @endauth
                @endif
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
                @auth
                    @if (($diff->invert == 1 || $auction->buy_now_price == $highest_bid) && auth()->user()->id == $highest_bidder_id)
                        <p class="bg-green-500 text-white px-4 py-3 rounded font-medium text-center ">You won the auction</p>
                        @if ($auction->transaction)
                            <p class="bg-green-500 text-white px-4 py-3 rounded font-medium text-center ">Transaction completed
                            </p>
                        @else
                            <a href="{{ '/create-transaction/' . $auction->id }}"
                                class="bg-blue-500 text-white px-4 py-3 rounded font-medium text-center">
                                Finish transaction
                            </a>
                        @endif
                    @elseif (($diff->invert == 1 || $auction->buy_now_price == $highest_bid) && $highest_bid > 0 && auth()->user()->id == $auction->user_id)
                        @if (!$auction->transaction)
                            <p class="bg-green-500 text-white px-4 py-3 rounded font-medium text-center ">Auction successful.
                                Wait
                                for buyer {{ $highest_bidder_username }} to finish the transaction</p>
                        @else
                            <p class="bg-green-500 text-white px-4 py-3 rounded font-medium text-center ">Buyer has completed
                                the transaction and balance has been added to your account</p>
                        @endif
                    @elseif (($diff->invert == 1 || $auction->buy_now_price == $highest_bid) && $highest_bid == 0 && auth()->user()->id == $auction->user_id)
                        <p class="bg-red-500 text-white px-4 py-3 rounded font-medium text-center ">Auction unsuccessful. No
                            bids.</p>
                    @endif
                @endauth
                @guest
                    @if ($diff->invert == 0 && $auction->buy_now_price !== $highest_bid)
                        <p class="p-5 bg-gray-200 rounded-2xl flex justify-center">Register or login to bid on this auction
                        </p>
                    @endif
                @endguest

            </ul>
        </div>
    </div>
@endsection
