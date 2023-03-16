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
    style="background: {{ $client->brand_color }}"
    class="min-h-screen bg-cyan-700 flex-col items-center space-y-10 justify-center flex p-5">
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
    <p class="italic text-sm">
        Last Updated: {{ Carbon\Carbon::parse($client->updated_at)->format('M d Y') }}
    </p>
</div>

<script>
    (function () {
        if (isDarkModeColor('{{ $client->brand_color }}')) {
            document.body.classList.add('text-white');
        } else {
            document.body.classList.add('text-black');
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
