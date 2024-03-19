@extends("layouts/layout")

@section("title")
    연차수당 재정산
@endsection

@section("content")


    <div id="reloadOffDay">
        @include("recalculate.annual.modal")
    </div>

    @include("salary.side_nav")
    <style>
        .salary-cal-wrap input[type=radio] + label {
            display: inline;
        }
    </style>
    <link rel="stylesheet" href="/css/member/index.css">

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>연차수당 재정산</h1> <span></span>
            </div>

        </article>

        <div class="search-form">
            <form action="">

                <span>년 검색</span>

                <input type="text" class="from_date" id="from_date" autocomplete="off" name="from_date" readonly value="{{ $from_date ?? "" }}">
                <label for="from_date">
                    <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                </label>

                <span>근로자명</span>
                <input type="text" name="provider_name" value=" {{ $provider_name ?? "" }}">

                <span>생년월일</span>
                <input type="text" name="provider_birth" value="{{ $provider_birth ?? "" }}" placeholder="ex) 200101">

                <span style="margin-right: 10px">계산기준</span>
                <input type="radio" name="calc_standard" value="join_standard" id="join_standard"
                {{ checked($calc_standard, "join_standard") }}
                {{ $calc_standard ? "" : "checked" }}
                >
                <label for="join_standard">입사일 기준</label>

                <input type="radio" name="calc_standard" value="year_standard" id="year_standard"
                {{ checked($calc_standard, "year_standard") }}
                >
                <label for="year_standard">회계연도 기준</label>
                <input type="text" class="from_date" id="year_standard_date" autocomplete="off" name="year_standard_date" readonly disabled value="{{ $year_standard_date ?? "" }}">
                <label for="year_standard_date">
                    <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                </label>

                <button>검색</button>

            </form>
        </div>

        @if ($lists)
            <article id="list_contents" class="m-top-20">


                <table class="recalc-table">
                    <tr>
                        <th>
                            No
                        </th>
                        <th>
                            대상자명
                        </th>
                        <th>
                            생년월일
                        </th>
                        <th>
                            입사일
                        </th>
                        <th>
                            퇴사일
                        </th>
                        <th>
                            1년차 미만
                        </th>
                        <th>
                            1월
                        </th>
                        <th>
                            2월
                        </th>
                        <th>
                            3월
                        </th>
                        <th>
                            4월
                        </th>
                        <th>
                            5월
                        </th>
                        <th>
                            6월
                        </th>
                        <th>
                            7월
                        </th>
                        <th>
                            8월
                        </th>
                        <th>
                            9월
                        </th>
                        <th>
                            10월
                        </th>
                        <th>
                            11월
                        </th>
                        <th>
                            12월
                        </th>
                        <th>
                            사용 연차 수
                        </th>
                        <th>
                            총 연차 수
                        </th>
                        <th>
                            연차수당
                        </th>
                    </tr>
                    @foreach ($lists as $list)
                    <tr data-provider="{{ $list->target_key }}">
                        <td>
                            <form action="" class="offValidateForm">
                                <input type="hidden" name="provider_key" value="{{$list->target_key ?? ""}}">
                                <input type="hidden" name="off_day_total" value="{{ $list->off_day_total ?? "" }}">
                                <input type="hidden" name="year_off_day" value="{{ $list->year_off_day ?? "" }}">
                                <input type="hidden" name="off_pay" value="{{ $list->off_pay ?? "" }}">
                                <input type="hidden" name="dailyPay" value="{{ $list->dailyPay ?? "" }}">
                                <input type="hidden" name="join_date" value="{{ $list->join_date ?? "" }}">
                                <input type="hidden" name="resign_date" value="{{ $list->resign_date ?? "" }}">
                                <input type="hidden" name="less_than_one_year" value="{{ $list->bool_less_than_one_year ?: 2 }}">
                            </form>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            {{ $list->name }}
                        </td>
                        <td>
                            {{ rsno_to_birth($list->birth) }}
                        </td>
                        <td>
                            {{ $list->join_date }}
                        </td>
                        <td>
                            {{ $list->resign_date }}
                        </td>
                        <td>
                            {{ $list->bool_less_than_one_year ? "1년 미만자" : "" }}
                        </td>

                        @foreach (range(1, 12) as $month)
                        <td class="off_date_select" data-month="{{ $month }}">
                            <p>
                                {{ $list->offdays[$month] ?? 0 }}
                            </p>
                        </td>
                        @endforeach
                        <td class="use_off_day">
                            {{ $list->year_off_day }}
                        </td>
                        <td>
                            {{ $list->off_day_total }}
                        </td>
                        <td class="off_pay">
                            {{ number_format($list->off_pay) }}
                        </td>
                    </tr>
                    @endforeach
                </table>

                <div class="off-recalc-btn-wrap">
                    <button type="button" class="button-save">
                        연차수당 저장
                    </button>
                    <form action="/export/excel/recalcoffdaypay" method="post">
                        @csrf
                        <input type="hidden" name="data" value="{{ json_encode($lists) }}">
                        <button class="button-excel-download">
                            엑셀 다운받기
                        </button>
                    </form>
                </div>

            </article> <!-- article list_contents end -->
        @endif
    </section>

    <form name="recalculateOffDaySaveForm" action="/recalculate/offday" method="post">
        @csrf
        <input type="hidden" name="target_ym" value="{{ $from_date ?? "" }}">
        <input type="hidden" name="standard" value="{{ $calc_standard ?? "" }}">
        <input type="hidden" name="year_standard_date" value="{{ $year_standard_date ?? "" }}">
        <input type="hidden" name="data" value="">
    </form>

    <script>

        // 연차수당 저장하기
        $(".button-save").on("click", function () {

            var forms = $("form.offValidateForm");

            var serialize = [];
            var serializeObject = [];
            $.each (forms, function (i, f) {
                serialize[i] = $(f).serializeArray();
            });
            $.each (serialize, function (i,obj) {

                serializeObject[i] = {}

                $.each (obj, function (n, _value) {
                    serializeObject[i][_value.name] = _value.value;
                })

            })

            var serializeStringify = JSON.stringify(serializeObject);
            var saveForm = document.recalculateOffDaySaveForm;

            saveForm.data.value = serializeStringify;
            saveForm.submit();

        });


        // 모달 닫기
        $(document).on("click", "#modal_close", function () {
            $(".modal-wrapper").css("display", "none");
        })


        // 모달 페이지 변경되게하기
        $(document).on("click", ".pagination ul li a", function (e) {
            e.preventDefault();
            var _month = $(this).data("month");
            var provider_key = $("span.provider_info").text();
            var _page = $(this).data("page");

            offReload({
                provider_key: provider_key,
                page: _page
            })

        })

        // 연차 눌렀을때 모달 띄우면서 내용 바꾸기
        $("td.off_date_select").on("click", function () {
            var _month = $(this).data("month");
            var provider_key = $(this).parent("tr").data("provider")

            offReload({
                provider_key: provider_key,
                page: 1
            })
        });


        // 모달 년도검색 / 연차사용일 추가
        $(".modal-action").on("click", function () {

            var _type = $(this).data("type");
            var f = document.modalSearchForm;

            var provider_key = f.provider_key.value
            var year = f.year.value
            var date = f.add.value
            var error = "";

            // 연도검색
            if (_type == "search")
            {
                if (provider_key == "") error += "근로자명을 입력해주세요.";
                if (year == "") error += "대상연도를 선택해주세요.";

                if (error != "") {
                    alert(error);
                    return false;
                }

                offReload({
                    provider_key: provider_key,
                    year: year,
                    page: 1
                })
            }

            // 연차사용일 추가하기
            else if (_type == "add")
            {

                if (provider_key == "") error += "근로자명을 입력해주세요.";
                if (date == "") error += "연차사용일을 입력해주세요.";

                if (error != "") {
                    alert(error);
                    return false;
                }

                var validate = $("tr[data-provider='"+provider_key+"'] td form.offValidateForm");
                var total_off = $("input[name='off_day_total']",validate); // 올해 연차 수
                var use_off = $("input[name='year_off_day']",validate); // 사용 연차 수
                var off_validate = Number(total_off.val()) > Number(use_off.val());


                if(!off_validate) {
                    alert("남은 연차가 없습니다.");
                    return false;
                }


                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'post',
                    dataType: 'json',
                    url: '/work/off/store',
                    data: {
                        provider_key: provider_key,
                        date: date
                    },
                    success: function (data) {

                        if (data.code == 2) {
                            alert("이미 존재하는 일자입니다.");
                            return;
                        }

                        if (data.code == 1) alert("연차 사용일을 추가했습니다.");

                        var _date = new Date(date);
                        var _year = _date.getFullYear();
                        var _month = _date.getMonth() + 1;
                        monthCountAdded(provider_key, _year, _month, +1)

                        offReload({
                            provider_key: provider_key,
                            year: year,
                            page: 1
                        })


                    }
                })

            }

        });

        // 연차삭제하기
        $(document).on("click", "#modal button.off-delete", function () {

            if (!confirm("삭제하시겠습니까?")) {
                return false;
            }

            var _id = $(this).data("id");
            var _year = $(this).data("year");
            var _month = $(this).data("month");
            var f = document.modalSearchForm;
            var provider_key = f.provider_key.value;

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'post',
                dataType: 'json',
                url: '/work/off/delete',
                data: {
                    id: _id,
                    provider_key: provider_key,
                },
                success: function (data) {

                    if (data.code == 1) alert("삭제했습니다.");


                    monthCountAdded(provider_key, _year, _month, -1)

                    offReload({
                        provider_key: provider_key,
                        page: 1
                    })

                }
            })

        })


        // 리스트 x월에 숫자 +,- 하기, (사용 연차 수에도 +,- 됨)
        // 연차수당도 다시 계산해주기
        function monthCountAdded (provider_key, _year, _month, count) {

            var from_date = new Date("{{ $from_date ?? "" }}");
            var from_date_year = from_date.getFullYear();
            if (_year != from_date_year) {
                return;
            }

            // form 에서 바뀌는 부분
            var validate = $("tr[data-provider='"+provider_key+"'] td form.offValidateForm");
            var off_day_total = $("input[name='off_day_total']",validate); // 사용 연차 수
            var use_off = $("input[name='year_off_day']",validate); // 사용 연차 수
            var calc_use_off = Number($(use_off).val()) + count;
            $(use_off).val(calc_use_off);
            var $off_pay = $("input[name='off_pay']", validate); // 인풋 연차수당
            var dailyPay = $("input[name='dailyPay']", validate); // 통상임금(연차수당 하루치)

            var leave_off_day = Number(off_day_total.val()) - Number(use_off.val()); // 남은 연차 수
            var calc_off_pay = Math.round(leave_off_day * Number(dailyPay.val())); // 남은 연차 수 * 통상임금 = 즉, 바뀐 값 적용된 연차수당

            var td_off_pay = $("tr[data-provider='"+provider_key+"'] td.off_pay"); // td 연차수당


            $($off_pay).val(calc_off_pay); // 인풋 연차수당 적용시키기
            $(td_off_pay).html(calc_off_pay.toLocaleString()); // td 연차수당 적용시키기

            var use_off_day = $("tr[data-provider='"+provider_key+"'] td.use_off_day"); // td 사용한 연차수
            use_off_day.html(calc_use_off); // td 사용한 연차수 적용시키기

            var target_month = $("tr[data-provider='"+provider_key+"'] td[data-month='"+_month+"'] p"); // td 바꿀 월의 연차 사용 수
            target_month.html(Number(target_month.html()) + count); // td 바꿀 월의 연차 사용 수 적용시키기
        }


        // 모달 리스트랑 페이징 리로드하기
        function offReload(obj)
        {
            getOffDay({
                provider_key: obj.provider_key,
                page: obj.page,
                type: "list",
                year: obj.year
            });
            getOffDay({
                provider_key: obj.provider_key,
                page: obj.page,
                type: "paging",
                year: obj.year
            })
        }

        // 모달 내용 업데이트하기
        // obj = { provider_key: 제공자키, page: 페이지, type: 타입(list, paging 택 1) }
        function getOffDay(obj)
        {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'get',
                dataType: 'html',
                url: '/work/off',
                data: {
                    provider_key: obj.provider_key,
                    page: obj.page,
                    type: obj.type,
                    year: obj.year
                },
                success: function (data) {

                    var _modal = $(".modal-wrapper");
                    var provider_info = $(".modal-head .provider_info");
                    var $provider_key = $(".modal-body input[name='provider_key']");
                    var modal_off_add = $(".modal-body input#modal_off_add");

                    if (obj.type == "list")
                    {
                        $("#modal tbody.reload").empty().html(data);
                    }
                    else if (obj.type == "paging")
                    {
                        $("#modal .pagination").empty().html(data)
                    }
                    _modal.css("display", "flex");
                    modal_off_add.val("");
                    provider_info.html(obj.provider_key);
                    $provider_key.val(obj.provider_key);

                }
            });
        }



        $("#from_date").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });

        $("#year_standard_date").datepicker({

            language: 'ko',
            navTitles: {
                days: "MM"
            },
            dateFormat:"mm-dd",
            view: 'days',
            minView: 'days',
            clearButton: false,
            autoClose: true,
        });

        $("#modal_off_add").datepicker({

            language: 'ko',
            dateFormat:"yyyy-mm-dd",
            view: 'days',
            minView: 'days',
            clearButton: false,
            autoClose: true,
        });



        $(document).ready(function() {
            $('#calc_result').DataTable({
                searching: false,
                lengthChange: false,
                scrollX:        "2400px",
                scrollY:        "600px",

                scrollCollapse: false,
                paging:         false,
            });
            var calc_standard = $("input[name='calc_standard']:checked").val()
            if (calc_standard == "year_standard") {
                $("#year_standard_date").attr("disabled", false);
            }

            $("ul.ul_2depth li.on").removeClass("on");
            var _focused = $("li[data-uri='/recalcAnnual']");
            _focused.addClass("on");


        } );



        $("input[name='calc_standard']").on("change", function () {
            var _value = $(this).val();
            if (_value == "year_standard") {
                $("#year_standard_date").attr("disabled", false);
            } else {
                $("#year_standard_date").attr("disabled", true);
            }
        })
    </script>
    <link rel="stylesheet" href="/css/member/datatables.css">


    <style>

        .recalc-table {
            border-right: 2px solid white;
            border-left: 2px solid white;
        }

        .recalc-table th,
        .recalc-table td {
            border: 1px solid #9c9c9c;
            text-align: center;
        }

        .recalc-table th {
            background-color: #E7E7E7;
            height: 45px;
            color: #414141;
        }

        .recalc-table tr:nth-child(2n + 1) td {
            background-color: #F3F1F2;
        }

        td.off_date_select {

            cursor: pointer;
        }

        td.off_date_select p {
            display: inline-block;
            color: blue;
            border-bottom: 1px solid blue;
        }

        .modal-wrapper {
            display: none;
            width: 100%;
            min-height: 100vh;
            height: 100%;
            margin: 0 auto;
            text-align: center;
            position: fixed;
            top: 0;
            justify-content: center;
            background-color: rgba(0,0,0,.5);
            align-items: center;
            z-index: 50;
        }

        #modal {
            width: 40%;
            height: 85vh;
            background-color: white;
            padding: 35px 30px;
            text-align: left;
        }

        #modal .modal-head p {
            font-size: 24px;
            font-weight: bold;
            color: black;
            display: inline-block;
        }

        #modal .modal-head button.modal-close {
            float: right;
            height: 44px;
            width: 35px;
            position: relative;
            background-color: transparent;
            border: none;
        }

        #modal .modal-head button.modal-close:after {
            position: absolute;
            top: 0;
            bottom: 0;
            right: 0;
            content: '\d7';
            font-size: 35px;
            color: #808080  ;
            text-align: center;
        }

        #modal .modal-body {
            margin-top: 10px;
        }

        #modal .modal-body .modal-search-form {
            background-color: #ececec;
            width: 100%;
            padding: 15px;
        }

        #modal .modal-body .modal-search-form div,
        #modal .modal-body .modal-search-form p
        {
            display: inline-block;
        }

        #modal .modal-body .modal-search-form p {
            font-weight: bold;
            color: black;
            margin-right: 10px;
            font-size: 15px;
        }

        #modal .modal-body .modal-search-form div + div {
            margin-left: 30px;
        }

        #modal .modal-body .modal-search-form div input {
            padding: 5px;
            width: 150px;
        }

        #modal .modal-body .modal-search-form .search-year button {
            background-color: #252525;
            padding: 5px 10px;
            color: white;
            border: none;
        }

        #modal .modal-body .modal-search-form .off_add button {
            background-color: #EA8626;
            padding: 5px 10px;
            color: white;
            border: none;
        }

        #modal .modal-body .off-list {
            margin-top: 22px;
        }

        #modal .modal-body .off-list table thead th {
            background-color: #363636;
            color: white;
            text-align: center;
            font-size: 15px;
            padding: 8px;
        }

        #modal .modal-body .off-list table tbody tr,
        #modal .modal-body .off-list table thead tr {
            border: 1px solid #b7b7b7;
        }

        #modal .modal-body .off-list table tbody td {
            padding: 10px;
            color: #363636;
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            background-color: #ececec;
        }

        #modal .modal-body .off-list table tbody tr:nth-child(2n-1) td {
            background-color: white;
        }

        #modal .modal-body .off-list table tbody tr td button {
            padding: 5px 15px;
            color: #8a8a8a;
            border: 1px solid #8a8a8a;
            background-color: transparent;
        }


        .search-form {
            width: 100%;
            height: 80px;
            line-height: 80px;
            background-color: #e8e8e8;
            padding: 0 20px;
            font-size: 15px;
        }

        .search-form input {
            width: 120px;
            height: 25px;
            margin-left: 10px;
            margin-right: 10px;
        }

        .search-form button {
            background-color: #363636;
            width: 50px;
            height: 30px;
            line-height: 30px;
            color: white;
            text-align: center;
            border:none;
        }

        label[for='from_date'] {
            margin-left: -35px;
            margin-right: 20px;
            vertical-align: middle;
        }

        .search-form input[name=birth] {
            margin-right: 5px;
        }

        label[for='year_standard_date'] {
            margin-left: -35px;
            margin-right: 20px;
            vertical-align: middle;
        }

        label.from_date {
            margin-left: -35px;
            margin-right: 20px;
            vertical-align: middle;
        }

        #list_contents .off-recalc-btn-wrap {
            display: inline-block;
            float: right;
            margin-top: 20px;
            overflow: hidden;
        }

        #list_contents .off-recalc-btn-wrap button {
            display: inline-block;
            width: 120px;
            height: 40px;
            color: white;
            border: none;
        }

        #list_contents .off-recalc-btn-wrap button.button-save {
            background-color: #363636;
        }

        #list_contents .off-recalc-btn-wrap button.button-excel-download {
            background-color: #197B30;
        }

        #list_contents .off-recalc-btn-wrap form {
            display: inline-block;
        }

    </style>

@endsection
