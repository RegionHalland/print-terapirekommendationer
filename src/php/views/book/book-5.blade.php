<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" type="text/css" href="{{ PRINT_TR_PLUGIN_URL }}/dist/css/print_to_a5.min.css?ver=1.0">
</head>
<body>
    
    {{-- Cover Page (Empty) --}}
    <div class="cover-page"></div>
    {{-- Cover Page (Empty) END --}}

    {{-- Foreword --}}
    <main class="main" role="main">
        <div class="section"">
            <h1>{{$foreword->post_title}}</h1>
            {!! apply_filters('the_content', $foreword->post_content) !!}
        </div>
    </main>
    {{-- Foreword END --}}
    
    {{-- Table of Contents --}}
    <h2 class="table-of-contents__header">Innehållsförteckning</h2>
    <ul class="table-of-contents">
        @foreach($chapters as $key => $chapter)
            <li class="table-of-contents__chapter">{{$chapter->post_name}} - {{$chapter->post_title}}<a href="#{{$key+1}}"></a></li>
            @foreach($chapter->children as $k => $children)
                <li class="table-of-contents__subchapter">{{$children->post_title}}<a href="#{{$key+1}}.{{$k+1}}"></a></li>
            @endforeach
        @endforeach
    </ul>
    {{-- Table of Contents END --}}
    
    <main class="main" role="main">
        {{-- Book --}}
        @foreach($chapters as $key=>$chapter)
                <div class="section" id="{{$key+1}}">
                    <h1>{{$chapter->post_title}}</h1>
                    {!! apply_filters('the_content', $chapter->post_content) !!}
                    <div class="chapter-header-left">1</div>
                    <div class="chapter-header-right">1</div>
                    @foreach($chapter->children as $k=>$children)
                        <?php if ($children->post_title != "Rekommenderade läkemedel") { ?>
                            <h2 id="{{$key+1}}.{{$k+1}}">{{$children->post_title}}</h2>
                        <?php } ?>
                        {!! apply_filters('the_content', $children->post_content) !!}
                    @endforeach
                </div>
        @endforeach
        {{-- Book END --}}
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
        // Hide headings over "Rekommenderade läkemedel" tables
        var headings = $('h2:contains("Rekommenderade läkemedel")');
        headings.addClass('hide');
    </script>
</body>