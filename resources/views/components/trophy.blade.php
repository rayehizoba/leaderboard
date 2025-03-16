@props(['id', 'path', 'autoplay' => false, 'loop' => false])

<figure {{ $attributes->merge(['class' => 'aspect-square bg-black/5 dark:bg-white/5 rounded-full w-20']) }}>
    <div id='{{ $id }}'></div>
</figure>

@pushonce('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.3/lottie.min.js"></script>
@endpushonce

@push('scripts')
    <script>
        // Initialize the Lottie animation
        lottie.loadAnimation({
            container: document.getElementById(@json($id)),
            renderer: 'svg', // Choose the renderer (svg, canvas, html)
            loop: @json($loop), // Set to true for loop
            autoplay: @json($autoplay), // Set to true to play automatically
            path: @json($path) // Path to your .json animation file
        });
    </script>
@endpush
