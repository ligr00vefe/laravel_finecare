@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 온라인 문의 내역
@endsection

<?php
use App\Classes\Custom;
use App\Classes\Input;
?>

@section("content")
    <section id="inquiry-view" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>온라인 문의 내역</h3>
            </div>
        </div>
            <div class="table-wrap">
                <table>
                    <tr class="sub-subject-tr">
                        <th>구분</th>
                        <td colspan="3">{{ $post->category }}</td>
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
                        <td colspan="3"><p>{!! $answer->contents ?? "빠른 시일 내에 답변드리겠습니다" !!}</p></td>
                    </tr>
                </table>
            </div>
            <div class="form-button-wrap">
                <div class="form-button-div"><a class="list-link form-button-link" href="/admin/board/inquiry">목록</a></div>
                <form action="/admin/board/inquiry/destroy/{{$post->id}}" method="post" style="display: inline-block" onsubmit="if(!confirm('삭제하시겠습니까?')) { return false; }">
                    @csrf
                    @method("delete")
                    <div class="form-button-div"><button class="delete-link form-button-link">삭제</button></div>
                </form>
                <div class="form-button-div"><a class="modify-link form-button-link" href="/admin/board/inquiry/modify/{{$post->id}}">수정</a></div>
                <div class="form-button-div"><a class="reply-link form-button-link" href="/admin/board/inquiry/modify/{{$post->id}}">답변</a></div>
            </div>
    </section>
@endsection
