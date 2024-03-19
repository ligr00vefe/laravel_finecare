@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 결제상품 관리
@endsection


@section("content")
    <section id="payment-product" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>결제상품 관리</h3>
                <div class="right-buttons">
                    <ul class="right_button_ul">
                        <li class="right_button_li">
                            {{--<form class="excel-form" action="">--}}
                                {{--<input type="hidden" name="board" value="">--}}
                                {{--<input type="hidden" name="category" value="">--}}
                                {{--<input type="hidden" name="keyword" value="">--}}
                                {{--<input type="hidden" name="start_date" value="">--}}
                                {{--<input type="hidden" name="end_date" value="">--}}
                                {{--<button class="excel_download_link" id="excel"></button>--}}
                            {{--</form>--}}
                            <form action="/admin/product" method="post" onsubmit="return UPDATE_MULTIPLE(this)">
                                @csrf
                                @method("put")
                                <input type="hidden" name="ids" value="">
                                <input type="hidden" name="prices" value="">
                                <input type="hidden" name="states" value="">
                                <button class="select_modify_button" style="border:none;">선택수정</button>
                            </form>
                            {{--<a href="#" class="select_delete_button">선택삭제</a>--}}
                            {{--<a href="#" class="board_write_button">글쓰기</a>--}}
                        </li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="contents">
            <div class="total_board">
                <p>총 문의내역 <span>1</span> 개</p>
            </div>
            <div class="search">
                <form action="">
                <select class="search_select" name="filter">
                    <option value="name">결제상품명</option>
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
                        <th class="board_th_index01">결제상품명</th>
                        <th class="board_th_index02">금액</th>
                        <th class="board_th_index03">상태</th>
                        <th class="board_th_index04">비고</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($lists as $list)
                    <form action="/admin/product/{{$list->id}}" method="post" onsubmit="return UPDATE(this)">
                        @csrf
                        @method("put")
                        <tr class="board_tbody_tr">
                            <td>
                                <input type="checkbox" name="id[]" id="board_id_{{ $list->id }}" value="{{ $list->id }}">
                                <label for="board_id_{{ $list->id }}"></label>
                            </td>
                            <td>
                                <p>{{ $list->name }}</p>
                            </td>
                            <td>
                                <input type="text" name="price" value="{{ $list->price }}">
                            </td>
                            <td>
                                <select name="state">
                                    <option value="1" {{ $list->state == 1 ? "selected" : "" }}>사용</option>
                                    <option value="0" {{ $list->state == 0 ? "selected" : "" }}>미사용</option>
                                </select>
                            </td>
                            <td>
                                    <button style="border:none" class="board_reply_button">수정</button>
                                {{--<a href="" class="board_delete_button">삭제</a>--}}
                            </td>
                        </tr>
                    </form>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center;">데이터가 없습니다</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>


    <script>
        function UPDATE_MULTIPLE(f)
        {
            var ids = $("input[name='id[]']:checked");

            var _ids = "";
            var prices = "";
            var states = "";

            if (ids.length == 0) {
                alert("수정할 상품을 선택해주세요");
                return false;
            }


            $.each (ids, function (i, v) {

                var _val = $(v).val();
                var prt = $(v).parents("tr");
                var price = $("input[name='price']", prt).val();
                var state = $("select[name='state']", prt).val();

                console.log(state);

                if (_ids != "") _ids += ",";
                if (price != "") price += ",";
                if (states != "") states += ",";

                _ids += _val;
                prices += price;
                states += state;

            });

            f.ids.value = _ids;
            f.prices.value = prices;
            f.states.value = states;
        }

        function UPDATE(f)
        {
            if (!confirm("수정하시겠습니까?")) return false;
        }

    </script>
@endsection
