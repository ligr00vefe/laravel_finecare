@extends("layouts/layout")

@section("title")
    활동지원사 찾기
@endsection

<?php
use App\Classes\Input;
use App\Classes\Custom;
?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("worker.side_nav")

    <style>

        #member_find.worker_find #find_top .search-wrap table {
            margin-bottom: 0;
        }

        #member_find.worker_find #find_top .search-wrap table tr th {
            width: 10%;
        }

        #member_find.worker_find #find_top .search-wrap .table-title {
            font-family: "Noto Sans CJK KR Bold";
            font-weight: bold;
            font-size: 15px;
            color: #363636;
        }

        .worker_find .table-divider {
            margin: 25px 0;
            border-bottom: 1px solid #b7b7b7;
        }

    </style>

    <section id="member_find" class="worker_find">

        <div class="head-info">
            <h1>활동지원사 찾기</h1>
        </div>

        <article id="find_top">

            <div class="search-wrap">
                <form action="" method="get">
                    <table>
                        <tr>
                            <th>지역</th>
                            <td>
                                <select name="location_1" id="location_1" data-depth="1" class="location">
                                    <option value="">선택해주세요</option>
                                    @foreach ($locations as $location)
                                        <option value="{{ $location->name }}"
                                                {{ $location->name == $location_1 ? "selected" : "" }}
                                        >{{ $location->name }}</option>
                                    @endforeach
                                </select>
                                <div class="location_2depth dis-ib">
                                    <select name="location_2" id="location_2" data-depth="2" class="location">
                                        <option value="">선택해주세요</option>

                                    </select>
                                </div>

                                <div class="location_3depth dis-ib">
                                    <select name="location_3" id="location_3" data-depth="3" class="location">
                                        <option value="">선택해주세요</option>

                                    </select>
                                </div>
                            </td>
                            {{--<th>결혼여부</th>--}}
                            {{--<td>--}}
                                {{--<input type="checkbox" name="married_check[]" value="미혼" id="married_check_1" {{ Input::checked($married_check, "미혼") }}>--}}
                                {{--<label for="married_check_1">미혼</label>--}}
                                {{--<input type="checkbox" name="married_check[]" value="기혼" id="married_check_2" {{ Input::checked($married_check, "기혼") }}>--}}
                                {{--<label for="married_check_2">기혼</label>--}}
                                {{--<input type="checkbox" name="married_check[]" value="기타" id="married_check_3" {{ Input::checked($married_check, "기타") }}>--}}
                                {{--<label for="married_check_3">기타</label>--}}
                            {{--</td>--}}
                        </tr>

                        <tr>
                            {{--<th>학력</th>--}}
                            {{--<td>--}}
                                {{--<input type="checkbox" name="academic_career[]" value="고졸이하" id="academic_career_1" {{ Input::checked($academic_career, "고졸이하") }}>--}}
                                {{--<label for="academic_career_1">고졸이하</label>--}}
                                {{--<input type="checkbox" name="academic_career[]" value="대학교재학" id="academic_career_2" {{ Input::checked($academic_career, "대학교재학") }}>--}}
                                {{--<label for="academic_career_2">대학교재학</label>--}}
                                {{--<input type="checkbox" name="academic_career[]" value="대졸이상" id="academic_career_3" {{ Input::checked($academic_career, "대졸이상") }}>--}}
                                {{--<label for="academic_career_3">대졸이상</label>--}}
                            {{--</td>--}}
                            <th>연령대</th>
                            <td>
                                <input type="checkbox" name="age[]" id="age_1" value="20대" {{ Input::checked($age, "20대") }}>
                                <label for="age_1">20대</label>
                                <input type="checkbox" name="age[]" id="age_2" value="30대" {{ Input::checked($age, "30대") }}>
                                <label for="age_2">30대</label>
                                <input type="checkbox" name="age[]" id="age_3" value="40대" {{ Input::checked($age, "40대") }}>
                                <label for="age_3">40대</label>
                                <input type="checkbox" name="age[]" id="age_4" value="50대" {{ Input::checked($age, "50대") }}>
                                <label for="age_4">50대</label>
                                <input type="checkbox" name="age[]" id="age_5" value="60대" {{ Input::checked($age, "60대") }}>
                                <label for="age_5">60대</label>
                                <input type="checkbox" name="age[]" id="age_6" value="70대" {{ Input::checked($age, "70대") }}>
                                <label for="age_6">70대</label>
                            </td>
                        </tr>

                        <tr>
                            {{--<th>건강상태</th>--}}
                            {{--<td>--}}
                                {{--<input type="checkbox" name="healthy[]" id="healthy_1" value="양호" {{ Input::checked($healthy, "양호") }}>--}}
                                {{--<label for="healthy_1">양호</label>--}}
                                {{--<input type="checkbox" name="healthy[]" id="healthy_2" value="보통" {{ Input::checked($healthy, "보통") }}>--}}
                                {{--<label for="healthy_2">보통</label>--}}
                                {{--<input type="checkbox" name="healthy[]" id="healthy_3" value="약함" {{ Input::checked($healthy, "약함") }}>--}}
                                {{--<label for="healthy_3">약함</label>--}}
                            {{--</td>--}}
                            <th>성별</th>
                            <td>
                                <input type="checkbox" name="gender[]" id="gender_1" value="male" {{ Input::checked($gender, "male") }}>
                                <label for="gender_1">남자</label>
                                <input type="checkbox" name="gender[]" id="gender_2" value="female" {{ Input::checked($gender, "female") }}>
                                <label for="gender_2">여자</label>
                            </td>
                        </tr>

                        {{--<tr>--}}
                            {{--<th>차량소지</th>--}}
                            {{--<td>--}}
                                {{--<input type="checkbox" name="has_car[]" id="has_car_1" value="1">--}}
                                {{--<label for="has_car_1">예</label>--}}
                                {{--<input type="checkbox" name="has_car[]" id="has_car_2" value="2">--}}
                                {{--<label for="has_car_2">아니오</label>--}}
                            {{--</td>--}}
                        {{--</tr>--}}

                    </table>

                    {{--<div class="table-divider"></div>--}}

                    {{--<p class="table-title">가능한 근무내용</p>--}}

                    {{--<table>--}}

                        {{--<tr>--}}
                            {{--<th>가능활동서비스</th>--}}
                            {{--<td colspan="3">--}}
                                {{--<input type="checkbox" name="possible_service[]" id="possible_service_1" value="신체서비스">--}}
                                {{--<label for="possible_service_1">신체서비스</label>--}}
                                {{--<input type="checkbox" name="possible_service[]" id="possible_service_2" value="가사서비스">--}}
                                {{--<label for="possible_service_2">가사서비스</label>--}}
                                {{--<input type="checkbox" name="possible_service[]" id="possible_service_3" value="사회활동서비스">--}}
                                {{--<label for="possible_service_3">사회활동서비스</label>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                            {{--<th>연령대</th>--}}
                            {{--<td colspan="3">--}}
                                {{--<input type="checkbox" name="member_age_type[]" id="member_age_type_1" value="아동">--}}
                                {{--<label for="member_age_type_1">아동</label>--}}
                                {{--<input type="checkbox" name="member_age_type[]" id="member_age_type_2" value="청소년">--}}
                                {{--<label for="member_age_type_2">청소년</label>--}}
                                {{--<input type="checkbox" name="member_age_type[]" id="member_age_type_3" value="성인">--}}
                                {{--<label for="member_age_type_3">성인</label>--}}
                                {{--<input type="checkbox" name="member_age_type[]" id="member_age_type_4" value="노인">--}}
                                {{--<label for="member_age_type_4">노인</label>--}}
                                {{--<input type="checkbox" name="member_age_type[]" id="member_age_type_5" value="무관">--}}
                                {{--<label for="member_age_type_5">무관</label>--}}
                            {{--</td>--}}
                        {{--</tr>--}}

                    {{--</table>--}}

                    {{--<div class="table-divider"></div>--}}

                    {{--<p class="table-title">근무 가능시간</p>--}}

                    {{--<table>--}}
                        {{--<tr>--}}
                            {{--<th>희망 근로시간</th>--}}
                            {{--<td colspan="3">--}}
                                {{--<select name="hope_work_time[]" id="hope_work_time" class="select-middle">--}}
                                    {{--<option value="">선택</option>--}}
                                {{--</select>--}}
                            {{--</td>--}}
                        {{--</tr>--}}
                    {{--</table>--}}

                    <div class="btn-wrap">
                        <button class="findAction">조회</button>
                    </div>
                </form>
            </div>

        </article>


        <article id="list_content">

            <div class="content-info">
                <p>총 검색결과 <b class="acc-orange">{{ $paging }}</b> 건</p>
            </div>

            <table class="table-1">

                <colgroup>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                    <col>
                </colgroup>
                <thead>
                <tr>
                    <th>No</th>
                    <th>이름</th>
                    <th>생년월일</th>
                    <th>성별</th>
                    <th>전화번호</th>
                    <th>주소</th>
                    <th>상태</th>
                    <th>접수일</th>
                    <th>입사일</th>
                    <th>근속기간</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lists as $list)
                    <tr>
                        <td>
                            {{ (($page - 1 ) * 15 ) + $loop->iteration }}
                        </td>
                        <td>
                            {{ $list->name }}
                        </td>
                        <td>
                            {{ $list->birth }}
                        </td>
                        <td>
                            {{ Custom::rsno_to_gender($list->birth) }}
                        </td>
                        <td>
                            {{ $list->phone }}
                        </td>
                        <td>
                            {{ $list->address }}
                        </td>
                        <td>
                            {{ Custom::contact_status($list->contract_start_date, $list->contract_end_date) }}
                        </td>
                        <td>
                            {{ date("Y-m-d", strtotime($list->contract_start_date)) }}
                        </td>
                        <td>
                            {{ date("Y-m-d", strtotime($list->contract_start_date)) }}
                        </td>
                        <td>
                            {{ Custom::calcLongevity($list->contract_start_date) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            {!! pagination2(10, ceil($paging/15)) !!}

        </article>

    </section>

    <script>

        document.addEventListener("DOMContentLoaded", function () {

            var location_1 = "{{ $location_1 }}";
            var location_2 = "{{ $location_2 }}";
            var location_3 = "{{ $location_3 }}";

            if (location_1 != "") {

                // 지역 2단계 선택있다면 selected 처리
                getLocation(location_1, 2)
                    .done(function (data) {

                        if (location_2 != "") {

                            $("#location_2").val(location_2);

                            // 지역 3단계 선택있다면 selected 처리
                            getLocation(location_2, 3)
                                .done(function (data) {
                                    $("#location_3").val(location_3);
                                })
                        }

                    })
            }

        });

        // 지역 바꾸기
        $("select.location").on("change", function () {

            var keyword = $(this).val();
            var depth = $(this).data("depth");

            if (depth == 3) return false;

            var targetDepth = depth < 3 ? depth + 1 : 3;
            getLocation(keyword, targetDepth);

        })

        function getLocation(keyword, depth) {

            return $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: 'get',
                url: '{{ route('location.get') }}',
                data: {
                    keyword: keyword,
                    depth: depth
                },
                success: function (data) {

                    var _target = $("select[data-depth='"+ depth +"']");
                    var _depth = depth - 1;

                    if (_depth == 1) {
                        $("select[data-depth='2']").empty();
                        $("select[data-depth='3']").empty();
                    }
                    else if (_depth == 2) {
                        $("select[data-depth='3']").empty();
                    }

                    var $str = "<option value=''>선택해주세요</option>";

                    data.forEach(function (i, v)  {
                        $str += "<option value='"+ i.name +"'>"+ i.name +"</option>";
                    });

                    $(_target).append($str);

                },
            })

        }


        $(".sub-menu__list ul li.on").removeClass("on");
        $(".sub-menu__list ul li[data-uri='/worker/find']").addClass("on");
    </script>

@endsection
