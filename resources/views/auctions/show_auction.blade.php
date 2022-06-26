@extends('layouts.app')

@section('content')
    <div class="flex justify-center mt-20">
        <div class="mr-20 w-5/12 bg-white p-6 rounded-lg"> <img src="{{ asset('images/' . $auction->image_path) }}"
                alt="">
            <div class="mt-5 bg-white p-1 rounded-lg">
                <p>{{ $auction->description }}</p>
            </div>
        </div>
        <div class="flex w-3/12 bg-white p-6 rounded-lg justify-center">
            <ul class="flex flex-col">
                <h1 class="mb-10 font-bold text-2xl">{{ $auction->name }}</h1>
                <li class="mb-10">
                    <p>Type: {{ $auction->type }}</p>
                </li>
                <li class="mb-10">
                    <p>Condition: {{ $auction->condition }}</p>
                </li>
                <li>
                    <a href="" class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Bid</a>
                </li>
            </ul>
        </div>
    </div>
@endsection
