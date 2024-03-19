@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - FAQ 관리
@endsection


@section("content")
    <section id="faq-write" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>FAQ 관리</h3>
            </div>
        </div>

        <form action="/admin/board/faq" method="post">
            @csrf
            @if (isset($edit))
                @method("put")
                <input type="hidden" name="id" value="{{ $post->id ?? "" }}">
            @endif
            <div class="table-wrap">
                <table class="write-table">
                    <tr class="write-table-tr write-subject-tr">
                        <th class="write-table-th table-title-th">제목</th>
                        <td><input class="write-table-input" type="text" name="subject" value="{{ $post->subject ?? "" }}"/></td>
                    </tr>
                    <tr class="write-table-tr textarea-tr">
                        <th class="write-table-th">내용</th>
                        <td><textarea name="content" class="faq-textarea">{{ $post->content ?? "" }}</textarea></td>
                    </tr>
                </table>
            </div>
            <div class="form-button-wrap">
                <div class="form-button-div"><a href="/admin/board/faq" class="list-link form-button-link">목록</a></div>
                <div class="form-button-div"><button class="confirm-link form-button-link">확인</button></div>
            </div>
        </form>
    </section>
@endsection
