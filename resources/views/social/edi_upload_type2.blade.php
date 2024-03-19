<div class="contents-box">

    <div class="box-top">
        <h3>
            <img src="{{__IMG__}}/icon_doc_small.png" alt="문서아이콘">{{$title}} 파일
        </h3>

        <div class="m-top-10">
            <p class="title">
                <span class="dotted-yellow">▪</span>{{$title}} EDI 제공파일을 이용하여 등록하는 방법
            </p>
            <p class="m-clear">
                1. 해당 기관의 EDI 엑셀파일을 기관 컴퓨터에 다운로드 하세요.
            </p>
            <p class="acc-orange m-clear">(해당 사이트에서 사업장 선택 > 빠른서비스 > 부과고지보험료조회 > {{$title}} > 당월보험료부과내역조회)</p>
            <p class="m-top-10">
                2. 파일을 아래 파일 업로드 영역에 업로드하세요.
            </p>
        </div>

        <div class="m-top-20">
            <p class="title">
                <span class="dotted-yellow">▪</span>FINECARE 제공양식을 이용하여 등록하는 방법
            </p>
            <p class="m-clear">
                1. {{$title}} 등록양식을 다운받고 내용을 입력하세요.
            </p>
            <button type="button" class="btn-download">
                <img src="/storage/img/icon_download.png" alt="다운로드 버튼">다운로드
            </button>

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
        <form action="/social/EDI/upload/{{$type}}" method="post" enctype="multipart/form-data">
            @csrf
            <p class="upload-selector">
                <input type="radio" name="upload_type" id="upload_renewal" checked>
                <label for="upload_renewal">업로드 파일로 갱신</label>
                <input type="radio" name="upload_type" id="upload_update">
                <label for="upload_update">신규자료 업데이트</label>
            </p>
            <div class="upload-wrap">
                <input type="file" name="upload_file" id="upload_file">
                <input type="text" class="fileDrag" name="upload_file_text" id="upload_file_text" placeholder="선택된 파일 없음">
                <label for="upload_file">찾아보기</label>
            </div>

            <div class="button-wrap m-top-10">
                <button class="btn-black">업로드</button>
            </div>
        </form>
    </div>

</div>
