@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            <h1 class="font-bold text-2xl mb-5">Updating auction {{ $auction->name }} with ID {{ $auction->id }}</h1>
            @auth
                <form action="{{ '/update/' . $auction->id }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="auction_id" id="auction_id" value="{{ $auction->id }}">
                    <div class="mb-4">
                        <label for="name" class="sr-only">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter the name of the item"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror"
                            value="{{ $auction->name }}">
                        @error('name')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="type">Type:</label>
                        <select id="type" name="type" class="bg-gray-100 border-2 p-4 rounded-lg">
                            <option value="computer" @if ($auction->type == 'computer') selected @endif>computer
                            </option>
                            <option value="computer-component" @if ($auction->type == 'computer-component') selected @endif>
                                computer-component</option>
                            <option value="mobile-device"@if ($auction->type == 'mobile-device') selected @endif>mobile-device
                            </option>
                            <option value="input-device" @if ($auction->type == 'input-device') selected @endif>input-device</option>
                            <option value="output-device" @if ($auction->type == 'output-device') selected @endif>output-device
                            </option>
                        </select>
                        @error('type')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="condition">Condition:</label>
                        <select id="condition" name="condition" class="bg-gray-100 border-2 p-4 rounded-lg">
                            <option value="brand-new"@if ($auction->condition == 'brand-new') selected @endif>brand-new</option>
                            <option value="slightly-used"@if ($auction->condition == 'slightly-used') selected @endif>slighlty-used
                            </option>
                            <option value="used" @if ($auction->condition == 'used') selected @endif>used</option>
                        </select>
                        @error('condition')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="end_now">End auction in 20 seconds:</label>
                        <input type="checkbox" id="end_now" name="end_now" value="20">
                        @error('end_now')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="start_price">Starting price: </label>
                        <input type="text" name="start_price" id="start_price"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $auction->start_price }}">
                        @error('start_price')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="buy_now_price">Buy now price: </label>
                        <input type="text" name="buy_now_price" id="buy_now_price"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg" value="{{ $auction->buy_now_price }}">
                        @error('buy_now_price')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4" cols="50"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg">{{ $auction->description }}</textarea>
                        @error('description')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                            Update auction
                        </button>
                    </div>
                </form>

            @endauth
            @guest
                <p>Login or register to create an auction</p>
            @endguest
        </div>
    </div>
@endsection
