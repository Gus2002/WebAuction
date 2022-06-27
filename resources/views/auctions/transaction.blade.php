@extends('layouts.app')

@section('content')
    <div class="flex justify-center mt-20">
        <div class="mr-20 w-5/12 bg-white p-6 rounded-lg">
            <h1 class="mb-10 font-bold text-2xl text-center">Transaction for auction: {{ $auction->name }}</h1>
            <p class="mb-3 text-2xl text-center">Seller: {{ $auction->user->username }}</p>
            <p class="mb-10 text-2xl text-center">{{ $highest_bid }} EUR will be deducted from your account</p>
            <form action={{ '/create-transaction/' . $auction->id }} method="post">
                @csrf
                <div class="mb-4">
                    <input type="hidden" name="auction_id" id="auction_id" value="{{ $auction->id }}">
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                        Confirm
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection
