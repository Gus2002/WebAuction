<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Auctions</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="bg-gray-200">
    <nav class="p-6 bg-white flex justify-between mb-6">
        <ul class="flex items-center">
            <li><a href="" class="p-3">Auctions</a></li>
            @auth
                <li><a href="" class="p-3">My auctions</a></li>
                <li><a href="" class="p-3">My bids</a></li>
            @endauth
        </ul>

        <ul class="flex items-center">
            @guest
                <li><a href="{{ route('login') }}" class="p-3">Login</a></li>
                <li><a href="{{ route('register') }}" class="p-3">Register</a></li>
            @endguest
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="post" class="pr-3 inline">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </li>
            @endauth
        </ul>
    </nav>
    @yield('content')
</body>

</html>
