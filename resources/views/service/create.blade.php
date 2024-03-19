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

            <table>
                <tbody>
                <tr>
                    <th>
                        <label for="target_name" class="required_mark">대상자 이름</label>
                    </th>
                    <td>
                        <input type="text" name="target_name" id="target_name">
                    </td>
                    <th>
                        <label for="rs_num_1" class="required_mark">주민등록번호</label>
                    </th>
                    <td>
                        <input type="text" name="rs_num_1" id="rs_num_1" class="input-middle" placeholder="(필수) 6자리">
                        -
                        <input type="text" name="rs_num_2" id="rs_num_2" class="input-middle" placeholder="(선택) 7자리">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="provide_name" class="required_mark">제공인력 이름</label>
                    </th>
                    <td>
                        <input type="text" name="provide_name" id="provide_name">
                    </td>
                    <th>
                        <label for="provide_rs_num_1" class="required_mark">제공인력 주민등록번호</label>
                    </th>
                    <td>
                        <input type="text" name="provide_rs_num_1" id="provide_rs_num_1" class="input-middle" placeholder="(필수) 6자리">
                        -
                        <input type="text" name="provide_rs_num_2" id="provide_rs_num_2" class="input-middle" placeholder="(선택) 7자리">
                    </td>
                </tr>

                <tr>
                    <th>
                        서비스 시작시간
                    </th>
                    <td>
                        <input type="text" name="from_date" id="from_date" autocomplete="off" readonly>
                        <label for="from_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                        <input type="text" name="from_date_time" id="from_date_time" placeholder="시간입력" readonly autocomplete="off">

                    </td>
                    <th>
                        서비스 종료시간
                    </th>
                    <td>
                        <input type="text" name="to_date" id="to_date" autocomplete="off" readonly>
                        <label for="to_date" class="label-date-img"><img src="{{__IMG__}}/icon_calendar.png" alt="달력켜기"></label>
                        <input type="text" name="to_date_time" id="to_date_time" readonly autocomplete="off" placeholder="시간켜기">
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
                        <label for="payment_time" class="required_mark">지원지자체 구분</label>
                    </th>
                    <td>
                        <select name="local_government_category" id="local_government_category">
                            <option value="">선택</option>
                        </select>
                    </td>
                    <th>
                        <label for="support_organi" class="required_mark">지원기관</label>
                    </th>
                    <td>
                        <select name="support_organi" id="support_organi">
                            <option value="">선택</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="content">비고</label>
                    </th>
                    <td colspan="3">
                        <textarea name="content" id="content"></textarea>
                    </td>
                </tr>
                </tbody>
            </table>

            <div class="btn-wrap m-top-20">
                <button type="button" class="btn-cancel">취소</button>
                <button type="button" class="btn-submit">등록</button>
            </div>

        </div>

    </article>

</section>

<script>
    $("input[name='from_date']").datepicker({
        language: 'ko',
        dateFormat:"yyyy-mm-dd",
        timepicker: true,
        clearButton: false,
        autoClose: true,
        onSelect: function(dateText, inst) {

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

            var _dateText = dateText.split(" ");

            var am_pm = _dateText[2] == "am" ? "오전" : "오후";

            $("input[name='to_date']").val(_dateText[0]);
            $("input[name='to_date_time']").val(am_pm + " " + _dateText[1]);

        }
    })
</script>

@endsection
