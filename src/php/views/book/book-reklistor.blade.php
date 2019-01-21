<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{ PRINT_TR_PLUGIN_URL }}/dist/css/print_ssk_a6.min.css?ver=9.0">
</head>
<body>
    
    {{-- Empty Page --}}
    <div class="list"></div>
    {{-- Empty Page END --}}

    {{-- Empty Page --}}
    <div class="list"></div>
    {{-- Empty Page END --}}
    
    {{-- Empty Page --}}
    <div class="list"></div>
    {{-- Empty Page END --}}
    
    {{-- Empty Page --}}
    <div class="list"></div>
    {{-- Empty Page END --}}
    
    {{-- Table of Contents --}}
    <h2 class="table-of-contents__header">Innehållsförteckning</h2>
    <ul class="table-of-contents">
        @foreach($chapters as $key => $chapter)
            <li class="table-of-contents__chapter">{{$chapter->post_title}}<a href="#{{$key+1}}"></a></li>
        @endforeach
    </ul>
    {{-- Table of Contents END --}}
    
    <main class="main" role="main">
        {{-- Book --}}
        @foreach($chapters as $key => $chapter)
            <div class="section" id="{{$key+1}}">
                <h1 class="hide">{{$chapter->post_title}}</h1>
                {!! apply_filters('the_content', $chapter->post_content) !!}
            </div>
        @endforeach
        {{-- Book END --}}
    </main>

</body>