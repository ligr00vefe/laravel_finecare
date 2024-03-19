@extends("layouts/layout")

@section("title")
    사회보험 - 통합징수포탈 등록
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("salary.side_nav")

<section id="member_wrap" class="collect-upload-wrap list_wrapper">

    <article id="list_head">

        <div class="head-info">
            <h1>통합징수포탈 등록</h1>
            <div class="tab-wrap">
                <ul>
                    <li class="{{$title == "국민연금" ? "on" : ""}}">
                        <a href="/social/collect/upload/1">국민연금</a>
                    </li>
                    <li class="{{$title == "건강보험" ? "on" : ""}}">
                        <a href="/social/collect/upload/2">건강보험</a>
                    </li>
                    <li class="{{$title == "산재보험" ? "on" : ""}}">
                        <a href="/social/collect/upload/3">산재보험</a>
                    </li>
                    <li class="{{$title == "고용보험" ? "on" : ""}}">
                        <a href="/social/collect/upload/4">고용보험</a>
                    </li>
                </ul>
            </div>
        </div>


    </article> <!-- article list_head end -->

    <article id="contents">

        <div class="contents-box">

            <div class="box-top">
                <h3>
                    <img src="{{__IMG__}}/icon_doc_small.png" alt="문서아이콘">{{$title}} 파일
                </h3>

                <div class="m-top-10">
                    <p class="title">
                        <span class="dotted-yellow">▪</span>{{$title}} 사회보험통합징수포털 제공파일을 이용하여 등록하는 방법
                    </p>
                    <p>
                        1. 사회보험통합징수포털 사이트(<a href="http://sj4n.nhis.or.kr" class="fc-sky" target="_blank" rel="noreferrer noopener"><b>http://sj4n.nhis.or.kr</b></a>) 에 들어가셔서 산출내역조회메뉴에서 해당 월의
                        {{$title}} 산출내역서(개인별조회)를 엑셀로 다운받으시기 바랍니다.
                    </p>
                    <p class="m-clear">
                        2. 다운 받은 파일을 아래 파일 업로드 영역에 업로드하세요.
                        <br>
                    </p>
                    {{--<span class="acc-orange">* 업로드하실 떄 파일형식을 csv 형식이 아닌 엑셀 통합문서 형식으로 저장하신 후 업로드하시기 바랍니다.</span>--}}
                </div>

                <div class="m-top-20">
                    <p class="title">
                        <span class="dotted-yellow">▪</span>FINECARE 제공양식을 이용하여 등록하는 방법
                    </p>
                    <p class="m-clear">
                        1. {{$title}} 등록양식을 다운받고 내용을 입력하세요.
                    </p>
                    <a href="/storage/docs/{{ $download }}" class="btn-download">
                        <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
                    </a>

                    <p class="m-clear">
                        2. 다운 받은 파일을 아래 파일 업로드 영역에 업로드하세요.
                        <br>
                    </p>

                    <p class="acc-orange m-clear">* 업로드한 파일에서 결정보험료 항목만 추출하여 급여계산에 사용됩니다. 업로드가 완료된 후 해당 항목이 정상적으로 추출되었는지 반드시 확인하시기 바랍니다.</p>
                    <p class="acc-orange m-clear">(사회보험 > 통합징수포탈 조회 > {{$title}} 탭 선택)</p>
                </div>
            </div>

            <div class="box-bottom">
                <h3>
                    해당 양식에 입력 완료 후 파일 업로드 하기
                </h3>
                <p class="m-top-10">
                    한개의 파일만 업로드 하실 수 있습니다. 두개의 파일을 올려도 데이터가 합쳐지지 않습니다.
                </p>

                <form action="/social/collect/upload/{{$type}}" method="post" enctype="multipart/form-data" onsubmit="return formValidation(this)">

                    @csrf
                    <p class="upload-selector">
                        <input type="radio" name="upload_type" id="upload_renewal" checked value="renew">
                        <label for="upload_renewal">업로드 파일로 갱신</label>
                        <input type="radio" name="upload_type" id="upload_update" value="new">
                        <label for="upload_update">신규자료 업데이트</label>
                    </p>
                    <div class="upload-wrap">
                        <input type="file" name="upload_file" id="upload_file" accept="">
                        <input type="text" class="fileDrag" name="upload_file_text" id="upload_file_text" placeholder="선택된 파일 없음" readonly accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, csv">
                        <label for="upload_file">찾아보기</label>
                    </div>

                    <div class="button-wrap m-top-10">
                        <button class="btn-black">업로드</button>
                    </div>

                </form>
            </div>

        </div>

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

    $("#upload_file").on("change", function(e) {
        var filename = e.target.files[0].name;
        $("#upload_file_text").val(filename);
    });

    function formValidation(f)
    {
        if (f.upload_type.value == "") {
            alert("업로드 타입을 선택해주세요.");
            return false;
        }
    }

</script>
@endsection
