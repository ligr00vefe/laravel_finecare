@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 동영상 관리
@endsection


@section("content")
    <section id=gallery class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>동영상 관리</h3>
                <div class="right-buttons">
                    <ul class="right_button_ul">
                        <li class="right_button_li">
                            <form action="/admin/board/video" method="post" onsubmit="return DELETE_MULTIPLE(this)" style="display:inline-block">
                                @csrf
                                @method("delete")
                                <input type="hidden" name="id" value="">
                                <button class="select_delete_button">선택삭제</button>
                            </form>
                            <a href="/admin/board/video/write" class="board_write_button">글쓰기</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="total_board">
                <p>총 게시글 수 <span>{{ $paging }}</span> 개</p>
            </div>
            <div class="search">
                <select class="search_select">
                    <option>게시판 ID</option>
                </select>
                <input type="text" class="search_input"/>
                <a href="#" class="search_button"></a>
            </div>
            <div class="gallery_wrap">
                <ul class="gall_ul">
                    @foreach ($lists as $list)
                    <li class="gall_li">
                        <td>
                            <input type="checkbox" name="id[]" id="board_id_{{ $list->id }}" value="{{ $list->id }}">
                            <label for="board_id_{{ $list->id }}"></label>
                        </td>
                        <div class="gall_box">
                            <div class="gall_chk"></div>
                            <div class="gall_content">
                                <div class="gall_img"><a class="gall_img_link" href="/admin/board/video/show/{{ $list->id }}"><img class="gall_img_source" src="/storage/img/no_img.png" alt="이미지"></a></div>
                                <div class="gall_text">{{ $list->subject }}</div>
                                <div class="gall_date">{{ date("y-m-d", strtotime($list->created_at)) }}</div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>
@endsection
