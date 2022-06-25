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
            <li><a href="{{ route('auctions') }}"
                    class="bg-blue-500 text-white px-4 py-3 rounded font-medium mr-5">Auctions</a></li>
            @auth
                <li><a href="{{ route('dashboard') }}"
                        class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Dashboard</a></li>
            @endauth
        </ul>

        <ul class="flex items-center">
            @guest
                <li><a href="{{ route('login') }}"
                        class="bg-blue-500 text-white px-4 py-3 rounded font-medium mr-5">Login</a>
                </li>
                <li><a href="{{ route('register') }}"
                        class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Register</a></li>
            @endguest
            @auth
                <li>
                    <form action="{{ route('logout') }}" method="post" class="pr-3 inline">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded font-medium">Logout</button>
                    </form>
                </li>
            @endauth
        </ul>
    </nav>
    @yield('content')
</body>

</html>
