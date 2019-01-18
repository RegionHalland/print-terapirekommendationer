<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{ PRINT_TR_PLUGIN_URL }}/dist/css/print_ssk_a6.min.css?ver=7.0">
</head>
<body>
    
    {{-- Cover Page (Empty) --}}
    <div class="cover-page"></div>
    {{-- Cover Page (Empty) END --}}

    {{-- Foreword --}}
    <main class="main" role="main">
        <div class="list"">
            <h1>{{$foreword->post_title}}</h1>
            {!! apply_filters('the_content', $foreword->post_content) !!}
        </div>
    </main>
    {{-- Foreword END --}}
    
    {{-- Table of Contents --}}
    <h2 class="table-of-contents__header">Innehållsförteckning</h2>
    <ul class="table-of-contents">
        @foreach($chapters as $key => $chapter)
            <li class="table-of-contents__chapter">{{$chapter['Rubrik']}}<a href="#{{$key+1}}"></a></li>
        @endforeach
    </ul>
    {{-- Table of Contents END --}}
    
    <main class="main" role="main">
        {{-- Book --}}
        @foreach($chapters as $key => $chapter)
            <div class="list" id="{{$key+1}}">
                {!! apply_filters('the_content', $chapter['Content']) !!}
            </div>
        @endforeach
        {{-- Book END --}}
    </main>

</body>