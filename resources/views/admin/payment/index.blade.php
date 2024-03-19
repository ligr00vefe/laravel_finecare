@extends("layouts/admin_layout")

@section("title")
    관리자페이지 - 결제관리
@endsection


@section("content")
    <section id="payment" class="wrapper">
        <div class="title_wrap">
            <div class="title">
                <h3>결제 관리</h3>
                <div class="right-buttons">
                    <ul class="right_button_ul">
                        <li class="right_button_li">
                            <form class="excel-form" action="">
                                <input type="hidden" name="board" value="">
                                <input type="hidden" name="category" value="">
                                <input type="hidden" name="keyword" value="">
                                <input type="hidden" name="start_date" value="">
                                <input type="hidden" name="end_date" value="">
                                <button class="excel_download_link" id="excel"></button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="contents">

            <div class="total_board">
                <p>총 게시글<span> {{ $paging }}</span> 개</p>
            </div>


            <form action="">
                {{--<div class="search">--}}
                    {{--<select class="search_select">--}}
                        {{--<option value=""></option>--}}
                    {{--</select>--}}
                    {{--<input type="text" class="search_input"/>--}}
                    {{--<a href="#" class="search_button"></a>--}}
                {{--</div>--}}
                <input type="hidden" name="auto_date">
                <div class="check-select-box">
                    <div class="sub-date-line">
                        <div class="sub-title"><p class="sub-subject">결제상품</p></div>
                        <ul class="product-ul pay-ul">
                            <li><input name="goods_id" id="all-check-product" type="checkbox" value="all"><label for="all-check-product">전체</label></li>
                            <li><input name="goods_id" id="one-month" type="checkbox" value="1"><label for="one-month">1개월 이용권</label></li>
                            <li><input name="goods_id" id="three-month" type="checkbox" value="2"><label for="three-month">3개월 이용권</label></li>
                            <li><input name="goods_id" id="six-month" type="checkbox" value="3"><label for="six-month">6개월 이용권</label></li>
                            <li><input name="goods_id" id="twelve-month" type="checkbox" value="3"><label for="twelve-month">12개월 이용권</label></li>
                        </ul>
                    </div>
                    <div>
                        <div class="sub-title"><p class="sub-subject">결제수단</p></div>
                        <ul class="payment-ul pay-ul">
                            <li><input name="payment_type" id="all-check-payment" type="checkbox" value="all-payment"><label for="all-check-payment">전체</label></li>
                            <li><input name="payment_type" id="credit-card" type="checkbox" value="카드결제"><label for="credit-card">카드결제</label></li>
                        </ul>
                    </div>
                    <div>
                        <div class="sub-title"><p class="sub-subject">결제일자</p></div>
                        <input class="sub-day-1" name="from_date" type="text" autocomplete="off">
                        <span class="day-spacer">~</span>
                        <input class="sub-day-2" name="to_date" type="text" autocomplete="off">
                        <ul class="day-ul pay-ul">
                            <li class="auto_date_changer" data-type="1">
                                <button type="button">오늘</button>
                            </li>
                            <li class="auto_date_changer" data-type="2">
                                <button type="button">어제</button>
                            </li>
                            <li class="auto_date_changer" data-type="3">
                                <button type="button">이번주</button>
                            </li>
                            <li class="auto_date_changer" data-type="4">
                                <button type="button">이번달</button>
                            </li>
                            {{--<li class="auto_date_changer" data-type="5">--}}
                                {{--<button type="button">지난주</button>--}}
                            {{--</li>--}}
                            <li class="auto_date_changer" data-type="6">
                                <button type="button">지난달</button>
                            </li>
                            <li class="auto_date_changer" data-type="7">
                                <button type="button">전체</button>
                            </li>
                            <li class="day-button">
                                <button>검색</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </form>

            <style>
                .day-ul li button {
                    border:none;
                    background-color: transparent;
                    color: #ffffff;
                    display: block;
                    height: 100%;
                    width: 100%;
                }

                .day-ul li.on {
                    background-color: #0c0c0c !important;
                }
            </style>

            <div class="board_table_wrap">
                <table class="board_table">
                    <thead>
                    <tr class="board_thead_tr">
                        {{--<th class="board_th_check_all">--}}
                            {{--<input type="checkbox" name="check_all" id="board_check_all">--}}
                            {{--<label for="board_check_all"></label>--}}
                        {{--</th>--}}
                        <th class="board_th_index01">결제상품</th>
                        <th class="board_th_index02">업체명/회원ID</th>
                        <th class="board_th_index03">결제수단</th>
                        <th class="board_th_index04">금액</th>
                        <th class="board_th_index05">결제일</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $total = 0; ?>
                    @foreach($lists as $list)
                        <?php $total += $list->goods_price ?>
                    <tr class="board_tbody_tr">
                        {{--<td>--}}
                            {{--<input type="checkbox" name="id[]" id="board_id_{{ $list->id }}" value="{{ $list->id }}">--}}
                            {{--<label for="board_id_{{ $list->id }}"></label>--}}
                        {{--</td>--}}
                        <td>{{ $list->goods_name }}</td>
                        <td>{{ $list->company_name }}/{{ $list->account_id }}</td>
                        <td>{{ $list->payment_type }}</td>
                        <td>{{ number_format($list->goods_price) }}</td>
                        <td>{{ date("Y-m-d", strtotime($list->payment_date)) }}</td>
                    </tr>
                    @endforeach
                    <tr class="board_result_tr">
                        <td></td>
                        <td></td>
                        <td>합계</td>
                        <td>{{ number_format($total) }}</td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>

                {!! pagination2(10, ceil($paging/15)) !!}

            </div>
        </div>
    </section>


    <script>
        $("input[name='from_date'], input[name='to_date']").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm-dd",
            clearButton: false,
            autoClose: true,
        });

        $(".auto_date_changer").on("click", function () {
            var _var = $(this).data("type");
            $(".auto_date_changer").removeClass("on");
            $(this).addClass("on");
            $("input[name='auto_date']").val(_var)
        })

    </script>

@endsection
