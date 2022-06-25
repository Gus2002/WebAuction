@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            {{-- <form action="{{ route('auctions') }}" method="post"> --}}
            <ul>

                <li><a href="{{ route('new-auction') }}" class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Create
                        an auction</a></li>
            </ul>
            {{-- </form> --}}
        </div>
    </div>
@endsection
