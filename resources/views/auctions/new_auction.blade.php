@extends('layouts.app')

@section('content')
    <div class="flex justify-center">
        <div class="w-8/12 bg-white p-6 rounded-lg">
            @auth
                <form action="{{ route('new-auction') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="name" class="sr-only">Name</label>
                        <input type="text" name="name" id="name" placeholder="Enter the name of the item"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror"
                            value="{{ old('name') }}">
                        @error('name')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="type">Type:</label>
                        <select id="type" name="type" class="bg-gray-100 border-2 p-4 rounded-lg">
                            <option value="input-device">computer</option>
                            <option value="computer-component">computer-component</option>
                            <option value="mobile-device">mobile-device</option>
                            <option value="input-device">input-device</option>
                            <option value="input-device">output-device</option>
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
                            <option value="brand-new">brand-new</option>
                            <option value="slightly-used">slighlty-used</option>
                            <option value="slightly-used">used</option>
                        </select>
                        @error('condition')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <p>Choose the duration of your auction - max allowed duration is 7 days</p>
                        <label for="hours">Hours:</label>
                        <select id="hours" name="hours" class="bg-gray-100 border-2 p-4 mr-5 rounded-lg">
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                            <option value="21">21</option>
                            <option value="22">22</option>
                            <option value="23">23</option>
                            <option value="24">24</option>
                        </select>
                        @error('hours')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                        <label for="days">Days:</label>
                        <select id="days" name="days" class="bg-gray-100 border-2 p-4 rounded-lg">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                        @error('days')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="start_price">Starting price: </label>
                        <input type="text" name="start_price" id="start_price"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                        @error('start_price')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="buy_now_price">Buy now price: </label>
                        <input type="text" name="buy_now_price" id="buy_now_price"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                        @error('buy_now_price')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="description">Write a short description about your item(specifications are necessary
                            according to site rules!):</label>
                        <textarea id="description" name="description" rows="4" cols="50"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg"></textarea>
                        @error('description')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="image">Upload an image of the item:</label>
                        <input type="file" name="image" id="image"
                            class="bg-gray-100 border-2 w-full p-4 rounded-lg">
                        @error('image')
                            <div class="text-red-500 mt-2 text-sm">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">
                            Create auction
                        </button>
                    </div>
                </form>

            @endauth
            @guest
                <p>Register or login to create an auction</p>
            @endguest
        </div>
    </div>
@endsection
