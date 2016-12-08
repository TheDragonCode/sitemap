<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    @foreach($items as $item)
        <url>
            <loc>{{ $item->get('loc') }}</loc>

            @if($item->get('lastmod'))
                <lastmod>{{ $item->get('lastmod') }}</lastmod>
            @endif

            @if($item->get('changefreq'))
                <changefreq>{{ $item->get('changefreq') }}</changefreq>
            @endif

            @if($item->get('priority'))
                <priority>{{ $item->get('priority') }}</priority>
            @endif
        </url>
    @endforeach
</urlset>