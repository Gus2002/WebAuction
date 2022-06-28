@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg mb-4">
            <ul class="flex items-center justify-between">
                <li class="mb-10">
                    <h1 class="font-bold text-2xl">Auctions</h1>
                </li>
                <li class="mb-10"><a href="{{ route('new-auction') }}"
                        class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Create
                        an auction</a></li>
            </ul>
            <div class="mb-4 bg-white p-6 rounded-lg grid grid-cols-2 gap-10 content-center">
                @if ($auctions->count())
                    @foreach ($auctions as $auction)
                        <div class="mb-5 bg-gray-200 border-solid border-2 border-blue-500 rounded-lg p-5 h-150 max-w-md">
                            <p class="font-bold    p-2"> Name:
                                {{ $auction->name }}</p>
                            <p class="font-bold    p-2">Type:
                                {{ $auction->type }}</p>
                            <p class="font-bold   mb-2 p-2">Seller:
                                {{ $auction->user->username }}</p>
                            <img src="{{ asset('images/' . $auction->image_path) }}" class="max-w-sm pb-5" alt="">
                            <p class="font-bold  rounded-lg mb-4 p-2">
                                Buy now price: {{ $auction->buy_now_price }}
                            </p>
                            @if ($auction->bids->toArray() && strtotime($auction->end_time) - time() > 0 && max(array_column($auction->bids->toArray(), 'amount')) < $auction->buy_now_price)
                                <p class="font-bold  rounded-lg mb-4 p-2">Auction ends
                                    at:
                                    {{ $auction->end_time }} GMT</p>
                            @elseif(!$auction->bids->toArray() && strtotime($auction->end_time) - time() > 0)
                                <p class="font-bold mb-5">Ends at: {{ $auction->end_time }} GMT</p>
                            @else
                                <p class="font-bold  rounded-lg mb-4 p-2">Auction ended</p>
                            @endif
                            <a href={{ '/show/' . $auction->id }}
                                class="bg-blue-500 text-white px-4 py-3 rounded font-medium">
                                Show auction</a>
                        </div>
                    @endforeach
            </div>
            {{ $auctions->links() }}
        @else
            <p>There are no auctions</p>
            @endif
        </div>
    </div>

    </div>
@endsection
