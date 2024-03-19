@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 자료실 관리
@endsection


@section("content")
    <section id="archives-write" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>자료실 관리</h3>
            </div>
        </div>
        <form action="/admin/board/archives" method="post" enctype="multipart/form-data">
            @csrf
            @if (isset($edit))
                @method("put")
                <input type="hidden" name="id" value="{{ $post->id }}">
            @endif
            <div class="table-wrap">
                <table class="write-table">
                    <tr class="write-table-tr write-subject-tr">
                        <th class="write-table-th table-title-th">제목</th>
                        <td><input class="write-table-input" type="text" name="subject" value="{{ $post->subject ?? "" }}"/></td>
                    </tr>
                    <tr class="write-table-tr write-content-th">
                        <th class="write-table-th">내용</th>
                        <td class="">
                            <textarea class="write-content-textarea" name="content">{{ $post->content ?? "" }}</textarea>
                        </td>
                    </tr>
                    <tr class="write-table-tr table-file-tr-01">
                        <th class="write-table-th">첨부파일</th>
                        <td class="write-file-td-01">
                            <input type="text" readonly="readonly" id="attachments1" name="file1name" placeholder="선택된 파일 없음" value="{{ $post->file1name ?? "" }}">
                            <label class="file-label">찾아보기<input class="file-01" type="file" name="file1" onchange="javascript:document.getElementById('attachments1').value=$(this).val().split('/').pop().split('\\').pop();"></label>
                        </td>
                    </tr>
                    <tr class="write-table-tr table-file-tr-02">
                        <th class="write-table-th">첨부파일</th>
                        <td class="write-file-td-02">
                            <input type="text" readonly="readonly" id="attachments2" name="file2name" placeholder="선택된 파일 없음" value="{{ $post->file2name ?? "" }}">
                            <label class="file-label">찾아보기<input class="file-02" type="file" name="file2" onchange="javascript:document.getElementById('attachments2').value=$(this).val().split('/').pop().split('\\').pop();"></label>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="form-button-wrap">
                <div class="form-button-div"><a class="list-link form-button-link" href="/admin/board/archives">목록</a></div>
                <div class="form-button-div"><button class="confirm-link form-button-link">확인</button></div>
            </div>
        </form>
    </section>
@endsection
