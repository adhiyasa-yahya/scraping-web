<a href="{{ route('scraping') }}">
    <button>Mulai Scraping</button>
</a>

<a href="{{ route('scraping.clear') }}">
    <button>Hapus Rate</button>
</a>

@if(session('success'))
    <p>{{ session('success') }}</p>
@endif