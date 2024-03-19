@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - FAQ 관리
@endsection


@section("content")
    <section id="faq" class="wrapper">

        <div class="title_wrap">
            <div class="title">
                <h3>FAQ 관리</h3>
                <div class="right-buttons">
                    <ul class="right_button_ul">
                        <li class="right_button_li">
                            <form action="{{ route("admin.board.faq.destroy.all") }}" method="post" onsubmit="return DELETE_MULTIPLE(this)" style="display: inline-block">
                                @csrf
                                @method("delete")
                                <input type="hidden" name="id" value="">
                                <button class="select_delete_button" style="border: none;">선택삭제</button>
                            </form>
                            <a href="/admin/board/faq/write" class="board_write_button">글쓰기</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="contents">
            <div class="total_board">
                <p>총 문의내역 <span>{{ $paging }}</span> 개</p>
            </div>
            <div class="search">
                <form action="">
                    <select class="search_select" name="filter">
                        <option value="subject">제목</option>
                    </select>
                    <input type="text" name="keyword" class="search_input"/>
                    <button class="search_button" style="border: none;"></button>
                </form>
            </div>
            <div class="board_table_wrap">
                <table class="board_table">
                    <thead>
                    <tr class="board_thead_tr">
                        <th class="board_th_check_all">
                            <input type="checkbox" name="check_all" id="board_check_all">
                            <label for="board_check_all"></label>
                        </th>
                        <th class="board_th_index01">No</th>
                        <th class="board_th_index02">질문</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($lists as $list)
                    <tr class="board_tbody_tr">
                        <td>
                            <input type="checkbox" name="id[]" id="board_id_{{$list->id}}" value="{{ $list->id }}">
                            <label for="board_id_{{$list->id}}"></label>
                        </td>
                        <td>{{ $list->id }}</td>
                        <td><a href="/admin/board/faq/view/{{ $list->id }}">{{ $list->subject }}</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <script>

        $("#board_check_all").on("change", function () {
            if ($(this).is(":checked")) {
                $("input[name='id[]']").prop("checked", true);
            } else {
                $("input[name='id[]']").prop("checked", false);
            }
        });

        function DELETE_MULTIPLE(f)
        {
            if (!confirm("선택한 게시글을 삭제하시겠습니까?")) {
                return false;
            }

            var _checked = $("input[name='id[]']:checked");
            var ids = "";

            $.each (_checked, function (i, v) {
                if (ids != "") ids += ",";
                ids += $(v).val();
            });

            if (ids == "") {
                alert("삭제할 게시글을 선택해주세요");
                return false;
            }

            f.id.value = ids;
        }
    </script>
@endsection
