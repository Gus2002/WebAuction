@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">Currently showing {{ $auction->id }} {{ $auction->name }} page</div>
        <img src="{{ asset('images/' . $auction->image_path) }}" alt="">
    </div>
@endsection
