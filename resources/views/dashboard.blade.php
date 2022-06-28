@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            <h1 class="font-bold text-2xl mb-5">Dashboard</h1>
            <div class="grid grid-cols-3">
                <div class="mb-4 bg-white p-6 max-w-sm">
                    <h2 class="font-bold text-lg text-center mb-5">My auctions</h2>
                    @if (session()->has('message'))
                        @if (!str_starts_with(session()->get('message'), 'Update successful'))
                            <p class="bg-red-500 text-white px-2 py-2 mb-5 text-center rounded font-medium">
                                {{ session()->get('message') }}
                            </p>
                        @else
                            <p class="bg-green-500 text-white px-2 py-2 mb-5 text-center rounded font-medium">
                                {{ session()->get('message') }}
                            </p>
                        @endif
                    @endif
                    <div class="bg-gray-200 border-solid border-2 p-3 border-blue-500 rounded-lg">
                        @if ($auctions->count())
                            @foreach ($auctions as $auction)
                                <div class="mb-5 bg-white border-solid border-2 border-blue-500 rounded-lg p-2 max-w-xs">
                                    <p class="font-bold "> Auction name:
                                        {{ $auction->name }}</p>
                                    <p class="font-bold"> Auction ID:
                                        {{ $auction->id }}</p>
                                    @if ($auction->bids->toArray() && strtotime($auction->end_time) - time() > 0 && max(array_column($auction->bids->toArray(), 'amount')) < $auction->buy_now_price)
                                        <p class="font-bold mb-5">Ends at: {{ $auction->end_time }} GMT</p>
                                    @elseif(!$auction->bids->toArray() && strtotime($auction->end_time) - time() > 0)
                                        <p class="font-bold mb-5">Ends at: {{ $auction->end_time }} GMT</p>
                                    @else
                                        <p class="font-bold mb-5">Auction has ended</p>
                                    @endif
                                    <a href={{ '/show/' . $auction->id }}
                                        class="bg-blue-500 text-white px-2 py-2 rounded font-medium">
                                        Show auction</a>
                                    @if ($auction->bids->toArray())
                                        <p class="bg-red-300 text-white px-2 py-2 mt-5 text-center rounded font-medium">
                                            Auction has bids, cannot delete</p>
                                    @else
                                        <div>
                                            <form action="{{ route('destroy-auction', $auction) }}" method="post">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    class="bg-red-500 text-white px-2 py-2 mt-5 text-center rounded font-medium"
                                                    type="submit">Delete</button>
                                            </form>
                                        </div>
                                    @endif
                                    @if ($auction->bids->toArray())
                                        <p class="bg-orange-300 text-white px-2 py-2 mt-5 text-center rounded font-medium">
                                            Auction has bids, cannot edit</p>
                                    @else
                                        <div class="mt-5">
                                            <a class="bg-orange-500 text-white px-2 py-2 text-center rounded font-medium"
                                                href={{ '/update/' . $auction->id }}>Edit</a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="mb-4 bg-white p-6 max-w-sm">
                    <h2 class="font-bold text-lg text-center mb-5"> My bid history</h2>
                    <div class="bg-gray-200 border-solid border-2 p-3 pr-5 pl-5 border-blue-500 rounded-lg">
                        @if ($bids->count())
                            @foreach ($bids as $bid)
                                {{-- @if (strtotime($bid->created_at) > time() - 86400) --}}
                                <div class="mb-5 bg-white border-solid border-2 border-blue-500 rounded-lg p-2 max-w-xs">
                                    <p class="font-bold p-1"> Auction name:
                                        {{ $bid->auction->name }}</p>
                                    <p class="font-bold p-1">Auction ID: {{ $bid->auction->id }}</p>
                                    <p class="font-bold p-1">Bid created at: {{ $bid->created_at }} GMT</p>
                                    <p class="font-bold p-1 mb-5">Amount: {{ $bid->amount }}</p>
                                    <a href={{ '/show/' . $bid->auction->id }}
                                        class="bg-blue-500 text-white px-2 py-2 mb-5 rounded font-medium">
                                        Show auction</a>
                                    @if ($bid->transaction)
                                        <p class="bg-green-500 text-white text-center px-1 py-1 mt-5 rounded-lg">Winning bid
                                            -
                                            transaction completed
                                        </p>
                                    @elseif (max(array_column($bid->auction->bids->toArray(), 'amount')) == $bid->amount && strtotime($bid->auction->end_time) - time() < 0 && !$bid->auction->transaction)
                                        <p class="bg-orange-500 text-white text-center px-1 py-1 mt-5 rounded-lg">You won
                                            the auction
                                            -
                                            go to auction to complete the transaction
                                        </p>
                                    @elseif (max(array_column($bid->auction->bids->toArray(), 'amount')) == $bid->auction->buy_now_price && !$bid->auction->transaction)
                                        <p class="bg-orange-500 text-white text-center px-1 py-1 mt-5 rounded-lg">You won
                                            the auction
                                            -
                                            go to auction to complete the transaction
                                        </p>
                                    @endif
                                </div>
                                {{-- @endif --}}
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="mb-4 bg-white p-6 max-w-sm">
                    <h2 class="font-bold text-lg text-center mb-5">My transactions</h2>
                    <div class="bg-gray-200 border-solid border-2 p-3 border-blue-500 rounded-lg">
                        @if ($transactions->count())
                            @foreach ($transactions as $transaction)
                                <div class="border-solid bg-white rounded-lg border-2 border-blue-500 rouned-lg mb-3 p-2">
                                    <p class="font-bold">Transaction ID: {{ $transaction->id }}</p>
                                    <p class="font-bold">Auction ID: {{ $transaction->auction_id }}</p>
                                    @if ($transaction->seller_id == auth()->user()->id)
                                        <p class="font-bold">Role: Seller</p>
                                        <p class="font-bold">Amount added: {{ $transaction->amount }}</p>
                                    @elseif ($transaction->buyer_id == auth()->user()->id)
                                        <p class="font-bold">Role: Buyer</p>
                                        <p class="font-bold">Amount deducted: {{ $transaction->amount }} EUR</p>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
