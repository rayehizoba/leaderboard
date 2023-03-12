<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $client->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>

    <!-- Styles -->
    <link rel=stylesheet href="https://cdn.jsdelivr.net/npm/@mdi/font@6.1.95/css/materialdesignicons.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased">
<div
    class="min-h-screen bg-cyan-700 text-white flex-col items-center space-y-10 justify-center flex p-5">
    <img src="{{ $client->logo_file_url }}" alt="{{ $client->name }}" class="h-40">
    <ul class="space-y-8">
        @foreach($client->leaders()->orderBy('sort')->get() as $leader)
            <li class="flex space-x-2">
                <figure class="relative">
                    <i class="mdi mdi-trophy-variant text-7xl @if($loop->iteration === 1) text-amber-400 @endif @if($loop->iteration === 2) text-slate-400 @endif @if($loop->iteration === 3) text-yellow-700 @endif @if($loop->iteration > 3) text-black opacity-10 @endif"></i>
                    <div class="absolute top-1/2 transform -translate-y-1/2 left-1/2 -translate-x-1/2 -mt-3 font-bold text-2xl @if($loop->iteration > 3) opacity-10 @else opacity-90 @endif">
                        {{ $loop->iteration }}
                    </div>
                </figure>
                <p class="text-lg uppercase mt-2 @if($loop->iteration > 3) opacity-25 @endif">{{ $leader->name }}</p>
            </li>
        @endforeach
    </ul>
</div>
</body>
</html>
