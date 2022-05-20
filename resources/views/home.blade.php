<a href="{{ route('scraping.clear') }}">
    <button>Hapus Rate</button>
</a>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif