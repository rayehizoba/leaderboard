<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $client->name }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel=stylesheet href="https://cdn.jsdelivr.net/npm/@mdi/font@6.1.95/css/materialdesignicons.min.css"/>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
</head>
<body class="antialiased">
<div
    style="background: {{ $client->brand_color }}"
    class="min-h-screen bg-cyan-700 flex-col items-center space-y-5 flex p-5 text-stone-700 dark:text-white/75">

    {{-- Brand Logo --}}
    <header class="flex items-center space-x-3 mb-5">
        <img src="{{ $client->logo_file_url }}" alt="{{ $client->name }}" class="h-16">
        <h1 class="text-xl font-semibold">
            {{ $client->heading ?? $client->name }}
        </h1>
    </header>

    {{-- Leaders --}}
    <ul class="flex items-center justify-center max-w-3xl">
        @php
            $defaultTrophy = 'json/silver.json';
            $trophies = [
                'json/gold.json',
                'json/silver.json',
                'json/bronze.json',
            ];
        @endphp

        {{-- Top 3 Leaders --}}
        @foreach($leaders as $leader)
            @if ($loop->index < 3)
                @php
                    $orderClass = [
                        'order-2',
                        'order-first',
                        'order-last'
                    ];
                @endphp
                <li class="flex flex-col items-center px-2 @if(count($leaders) >= 3){{ $orderClass[$loop->index] }}@endif {{ $loop->index === 0 ? 'flex-2' : 'flex-1' }}">
                    <div class="{{ $loop->index === 0 ? 'text-4xl' : 'text-2xl' }} font-bold text-center">
                        {{ $loop->iteration }}
                    </div>
                    <x-trophy
                        class="{{ $loop->index === 0 ? 'w-36' : '' }}"
                        id="trophy-{{ $loop->index }}"
                        path="{{ asset($trophies[$loop->index] ?? $defaultTrophy) }}"
                        :loop="$loop->first"
                        autoplay
                    />
                    <div
                        class="{{ $loop->index === 0 ? 'text-2xl' : 'text-lg' }} font-semibold text-center leading-tight mt-1">
                        {{ $leaders[$loop->index]->name }}
                    </div>
                </li>
            @else
                @break
            @endif
        @endforeach
    </ul>

    {{-- Others --}}
    <ol start="4" class="list-decimal font-medium space-y-2 marker:text-xl marker:text-stone-500 text-stone-700 max-w-sm">
        @foreach($leaders as $leader)
            @if ($loop->index >= 3)
                <li>
                    <div class="rounded-xl bg-black/5 dark:bg-white/5 p-5 py-3 ml-5">
                        {{ $leader->name }}
                    </div>
                </li>
            @endif
        @endforeach
    </ol>

    {{-- Footer --}}
    <footer>
        <p class="text-xs font-medium">
            Last Updated: {{ Carbon\Carbon::parse($client->updated_at)->format('M d Y') }}
        </p>
    </footer>
</div>

@stack('scripts')
<script>
    (function () {
        if (isDarkModeColor('{{ $client->brand_color }}')) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.add('light');
        }
    })();

    function isDarkModeColor(colorCode) {
        let red, green, blue;

        // Check if the color code is in the #RRGGBB format
        if (colorCode.startsWith("#") && colorCode.length === 7) {
            red = parseInt(colorCode.substr(1, 2), 16);
            green = parseInt(colorCode.substr(3, 2), 16);
            blue = parseInt(colorCode.substr(5, 2), 16);
        }
        // Check if the color code is in the rgb(r, g, b) format
        else if (/^rgb\(\s*\d+\s*,\s*\d+\s*,\s*\d+\s*\)$/.test(colorCode)) {
            const rgbArray = colorCode.match(/\d+/g);
            red = parseInt(rgbArray[0]);
            green = parseInt(rgbArray[1]);
            blue = parseInt(rgbArray[2]);
        }
        // Invalid color code format
        else {
            throw new Error("Invalid color code format");
        }

        // Calculate the relative luminance of the color using the sRGB color space
        const luminance = 0.2126 * red + 0.7152 * green + 0.0722 * blue;

        // Determine if the color is "dark" based on its luminance value
        return luminance < 128;
    }
</script>
</body>
</html>
