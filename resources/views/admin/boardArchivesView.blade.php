@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 자료실 관리
@endsection


@section("content")
    <section id="archives-view" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>자료실 관리</h3>
            </div>
        </div>
        <div class="view-wrap">
            <div class="view-title-wrap">
                <div class="view-title"><h2>{{ $post->subject }}</h2></div>
                <div class="view-title-info">
                    <div class="view-date">작성일 : {{ $post->created_at }}</div>
                </div>
            </div>

            <style>
                #archives-view table tr td {
                    font-size: 15px;
                    color: #363636;
                    font-weight: 500;
                }

                #archives-view .file-link-th {
                    padding-left: 20px;
                }
            </style>

            @if ($post->file1name)
                <tr class="sub-subject-tr file-link-tr">
                    <th class="file-link-th">파일링크1</th>
                    <td colspan="3">
                        <a href="{{ __URL__ }}/{{ $post->file1path ?? "" }}">{{ $post->file1name }}</a>
                    </td>
                </tr>
            @endif
            @if ($post->file2name)
                <tr class="sub-subject-tr file-link-tr">
                    <th class="file-link-th">파일링크2</th>
                    <td colspan="3">
                        <a href="{{ __URL__ }}/{{ $post->file2path ?? "" }}">{{ $post->file2name }}</a>
                    </td>
                </tr>
            @endif

            <div class="view-content-wrap">
                <p class="view-content">
                    {!! $post->content !!}
                </p>
            </div>
        </div>
        <div class="form-button-wrap">
            <div class="form-button-div"><a class="list-link form-button-link" href="/admin/board/archives">목록</a></div>
            <form action="{{ route("admin.board.archives.destroy") }}" method="post" style="display: inline-block" onsubmit="if(!confirm('글을 삭제하시겠습니까?')) { return false;} ">
                @csrf
                @method("delete")
                <input type="hidden" name="id" value="{{ $post->id }}">
                <div class="form-button-div"><button class="delete-link form-button-link">삭제</button></div>
            </form>
            <div class="form-button-div"><a class="modify-link form-button-link" href="/admin/board/archives/edit/{{ $post->id }}">수정</a></div>
            <div class="form-button-div"><a class="write-link form-button-link" href="/admin/board/archives/write">글쓰기</a></div>
        </div>
    </section>
@endsection
