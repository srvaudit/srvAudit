@extends('spark::layouts.app')

@section('content')
<div class="docs-wrapper container">


    {{-- <div id="search">
        <div id="search-wrapper">
            <input placeholder="" type="text" id="search-input" />
            <i id="cross" class="icon"></i>
        </div>
    </div> --}}

    <article>
        {!! $content !!}
    </article>
</div>
@endsection
