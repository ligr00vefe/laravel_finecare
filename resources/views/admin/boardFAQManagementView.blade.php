@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - FAQ 관리
@endsection


@section("content")
    <section id="faq-view" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>FAQ 관리</h3>
            </div>
        </div>
        <div class="view-wrap">
            <div class="view-title-wrap">
                <div class="view-title"><h2>{{ $post->subject }}</h2></div>
                <div class="view-title-info">
                    <div class="view-date">작성일 : {{ $post->created_at }}</div>
                </div>
            </div>
            <div class="view-content-wrap">
                <p class="view-content">
                    {!! $post->content !!}
                </p>
            </div>
        </div>
        <div class="form-button-wrap">
            <div class="form-button-div"><a class="list-link form-button-link" href="/admin/board/faq">목록</a></div>
            <form action="/admin/board/faq/destroy" method="post" onsubmit="if (!confirm('삭제하시겠습니까?')) { return false; }" style="display: inline-block">
                @csrf
                @method("delete")
                <input type="hidden" name="id" value="{{ $post->id ?? "" }}">
                <div class="form-button-div"><button class="delete-link form-button-link">삭제</button></div>
            </form>
            <div class="form-button-div"><a class="modify-link form-button-link" href="/admin/board/faq/edit/{{ $post->id }}">수정</a></div>
            <div class="form-button-div"><a class="write-link form-button-link" href="/admin/board/faq/write">글쓰기</a></div>
        </div>
    </section>
@endsection
