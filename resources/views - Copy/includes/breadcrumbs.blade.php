<!-- refer http://laravel-breadcrumbs.davejamesmiller.com/en/latest/defining.html#dynamic-titles-and-links -->
@if ($breadcrumbs)
<ol class="breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)
        @if (!$breadcrumb->last)
            <li><a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a></li>
        @else
            <li class="active">{{ $breadcrumb->title }}</li>
        @endif
    @endforeach
</ol>
@endif