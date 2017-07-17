
<section class="content-header">
    <h1>
        {{ $menu_info['menu'] }} {{ isset($page_title) ? $page_title : '' }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ url(backend_url()) }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        @include('backend.layouts.partials.breadcrumbs')
    </ol>
</section>