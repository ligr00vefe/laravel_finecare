@extends("layouts/layout")

@section("title")
    사회보험 - EDI 등록
@endsection

@php

$contents_type = [
    "국민연금", "건강보험", "고용보험"
];

@endphp

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("salary.side_nav")


<section id="member_wrap" class="edi-upload-wrap list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>EDI 등록</h1>
            <div class="tab-wrap">
                <ul>
                    <li class="{{$title == "국민연금" ? "on" : ""}}">
                        <a href="/social/EDI/upload/1">국민연금</a>
                    </li>
                    <li class="{{$title == "건강보험" ? "on" : ""}}">
                        <a href="/social/EDI/upload/2">건강보험</a>
                    </li>
                    <li class="{{$title == "산재보험" ? "on" : ""}}">
                        <a href="/social/EDI/upload/3">산재보험</a>
                    </li>
                    <li class="{{$title == "고용보험" ? "on" : ""}}">
                        <a href="/social/EDI/upload/4">고용보험</a>
                    </li>
                </ul>
            </div>
        </div>


    </article> <!-- article list_head end -->

    <article id="contents">

        @if (in_array($title, $contents_type))
            @include("social.edi_upload_type1")
        @else
            @include("social.edi_upload_type2")
        @endif

    </article>

</section>


<script>
    $(".fileDrag").on("dragenter", function(e) {
        e.preventDefault();
        e.stopPropagation();
    }).on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(this).css("background-color", "#FFD8D8");
    }).on("drop", function(e) {
        e.preventDefault();

        var files = e.originalEvent.dataTransfer.files;
        var $inputfile;
        var $inputtext;
        var thisname = $(this).attr("name");
        if (thisname == "upload_file_text") {
            $inputfile = document.getElementById("upload_file");
            $inputtext = $("input[name='upload_file_text']")
        }

        $inputfile.files = files;
        $inputtext.val(files[0].name);
        $(this).css("background-color", "#ffffff");

    });
</script>
@endsection
