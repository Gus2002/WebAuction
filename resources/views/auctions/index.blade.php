@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg mb-4">
            <ul class="mb-10">
                <li><a href="{{ route('new-auction') }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Create
                        an auction</a></li>
            </ul>
            <div class="mb-4">
                @if ($auctions->count())
                    @foreach ($auctions as $auction)
                        <div class="mb-10 bg-grey-400 border-solid border-2 border-indigo-600 rounded-lg p-5">
                            <p class="font-bold border-dashed border-2 border-indigo-600 rounded-lg mb-4 p-2"> Name:
                                {{ $auction->name }}</p>
                            <p class="font-bold border-dashed border-2 border-indigo-600 rounded-lg mb-4 p-2">Description:
                                {{ $auction->description }}</p>
                            <p class="font-bold border-dashed border-2 border-indigo-600 rounded-lg mb-4 p-2">Auction ends
                                at:
                                {{ $auction->end_time }} GMT</p>
                            <p class="font-bold border-dashed border-2 border-indigo-600 rounded-lg mb-10 p-2">Seller:
                                {{ $auction->user->username }}</p>
                            <a href={{ '/show/' . $auction->id }}
                                class="bg-blue-500 text-white px-4 py-3 rounded font-medium">
                                Show auction</a>
                        </div>

            </div>
            @endforeach
            {{ $auctions->links() }}
        @else
            <p>There are no auctions</p>
            @endif
        </div>
    </div>

    </div>
@endsection
