@extends("layouts/layout")

@section("title")
    추가 서비스내역 관리
@endsection

@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("service.side_nav")
<section id="container" class="counseling-write">

    <article id="contents" class="service-manage-write">
        <div class="contents-top">
            <h3>추가 서비스내역 관리</h3>
        </div>

        <div class="contents-body m-top-20">

            <form action="/service/extra" method="post">
                @csrf
                <table>
                    <tbody>
                    <tr>
                        <th>
                            <label for="target_ym" class="required_mark">대상년월</label>
                        </th>
                        <td colspan="3">
                            <input type="text" name="target_ym" id="target_ym" autocomplete="off" readonly >
                            <label for="from_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="target_name" class="required_mark">대상자 이름</label>
                        </th>
                        <td>
                            <input type="text" name="target_name" id="target_name">
                        </td>
                        <th>
                            <label for="target_birth" class="required_mark">대상자 생년월일</label>
                        </th>
                        <td>
                            <input type="text" name="target_birth" id="target_birth" class="input-middle" placeholder="(필수) 6자리">
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="provider_name" class="required_mark">제공인력 이름</label>
                        </th>
                        <td>
                            <input type="text" name="provider_name" id="provider_name">
                        </td>
                        <th>
                            <label for="provider_birth" class="required_mark">제공인력 생년월일</label>
                        </th>
                        <td>
                            <input type="text" name="provider_birth" id="provider_birth" class="input-middle" placeholder="(필수) 6자리">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            서비스 시작시간
                        </th>
                        <td>
                            <input type="hidden" name="service_start_date_time">
                            <input type="text" name="from_date" id="from_date" autocomplete="off" readonly data-timepicker="true" data-time-format='hh:ii'>
                            <label for="from_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                            <input type="text" name="from_date_time" id="from_date_time" placeholder="시간입력" readonly autocomplete="off">
                        </td>
                        <th>
                            서비스 종료시간
                        </th>
                        <td>
                            <input type="hidden" name="service_end_date_time">
                            <input type="text" name="to_date" id="to_date" autocomplete="off" readonly data-timepicker="true" data-time-format='hh:ii'>
                            <label for="to_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                            <input type="text" name="to_date_time" id="to_date_time" readonly autocomplete="off" placeholder="시간입력">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="payment_time" class="required_mark">결제시간</label>
                        </th>
                        <td>
                            <input type="text" name="payment_time" id="payment_time" class="input-middle" placeholder="">
                            <p class="help">30분 단위로 계산해서 입력해주세요 ex) 3.0</p>
                        </td>
                        <th>
                            <label for="add_price">가산금액</label>
                        </th>
                        <td>
                            <input type="text" name="add_price" id="add_price"  placeholder="">
                        </td>
                    </tr>

                    <tr>
                        <th>
                            <label for="local_government_name" class="required_mark">지원지자체 구분</label>
                        </th>
                        <td>
                            <select name="local_government_name" id="local_government_name">
                                <option value="">선택</option>
                            </select>
                        </td>
                        <th>
                            <label for="organization" class="required_mark">지원기관</label>
                        </th>
                        <td>
                            <select name="organization" id="organization">
                                <option value="">선택</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <label for="etc">비고</label>
                        </th>
                        <td colspan="3">
                            <textarea name="etc" id="etc"></textarea>
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="btn-wrap m-top-20">
                    <button type="button" onclick="history.back()" class="btn-cancel">취소</button>
                    <button class="btn-submit">등록</button>
                </div>
            </form>


        </div>

    </article>

</section>

<script>

    $("input[name='target_ym']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm",
        view: 'months',
        minView: 'months',
        timepicker: false,
        clearButton: false,
        autoClose: true,
    });


    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm-dd",
        timepicker: true,
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

            console.log(dateText);
            $("input[name='service_start_date_time']").val(dateText);
            var _dateText = dateText.split(" ");

            var am_pm = _dateText[2] == "am" ? "오전" : "오후";

            $("input[name='from_date']").val(_dateText[0]);
            $("input[name='from_date_time']").val(am_pm + " " + _dateText[1]);

            $("input[name='to_date']").datepicker({
                minDate: new Date(dateText),
                dateFormat:"yyyy-mm-dd",
                clearButton: false,
                autoClose: true,
            })
        }
    });


    $("input[name='to_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm-dd",
        timepicker: true,
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

            $("input[name='service_end_date_time']").val(dateText);
            var _dateText = dateText.split(" ");

            var am_pm = _dateText[2] == "am" ? "오전" : "오후";

            $("input[name='to_date']").val(_dateText[0]);
            $("input[name='to_date_time']").val(am_pm + " " + _dateText[1]);

        }
    })

    $("a[href='/service/extra/list']").parent("li").addClass("on");
</script>

@endsection
