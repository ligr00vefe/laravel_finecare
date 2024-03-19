@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 온라인 문의 내역
@endsection

<?php
use App\Classes\Input;
use App\Classes\Custom;
?>

@section("content")
    <section id="inquiry-modify" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>온라인 문의 내역</h3>
            </div>
        </div>
        <form method="post" action="{{route("admin.board.inquiry.store", [ "id" => $post->id ])}}">
            @csrf
            <div class="table-wrap">
                <table>
                    <tr class="sub-subject-tr">
                        <th>구분</th>
                        <td colspan="3"></td>
                    </tr>
                    <tr class="sub-subject-tr">
                        <th>작성자</th>
                        <td>{{ Custom::userid_to_companyname($post->user_id) }}</td>
                        <th>작성일시</th>
                        <td>{{ $post->created_at }}</td>
                    </tr>
                    <tr class="sub-subject-tr">
                        <th>제목</th>
                        <td colspan="3">{{ $post->subject }}</td>
                    </tr>
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
                    <tr class="sub-subject-tr textarea-tr">
                        <th>내용</th>
                        <td colspan="3"><p class="table-p">{!! $post->content !!}</p></td>
                    </tr>
                    <tr class="sub-subject-tr textarea-tr">
                        <th>답변</th>
                        <td colspan="3"><textarea name="contents" class="inquiry-textarea">{{ $answer->contents ?? "빠른 시일내에 답변 드리겠습니다." }}</textarea></td>
                    </tr>
                </table>
            </div>
            <div class="form-button-wrap">
                <div class="form-button-div"><a class="list-link form-button-link" href="/admin/board/inquiry">목록</a></div>
                <div class="form-button-div"><button class="reply-link form-button-link" >작성</button></div>
            </div>
        </form>
    </section>
@endsection
