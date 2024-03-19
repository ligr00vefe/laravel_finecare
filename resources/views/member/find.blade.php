@extends("layouts/layout")

@section("title")
    이용자 찾기
@endsection

<?php
use App\Classes\Custom;
use App\Classes\Input;
?>

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("member.side_nav")

    <section id="member_find">

        <div class="head-info">
            <h1>이용자 찾기</h1>
        </div>

        <article id="find_top">

            <div class="search-wrap">
                <form action="" method="get">
                    <h3>활동지원 서비스 요구</h3>
                    <table>
                        <tr>
                            <th>
                                지역
                            </th>
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

                        </tr>
                        <tr>
                            {{--<th>가능활동서비스</th>--}}
                            {{--<td>--}}
                                {{--<input type="checkbox" name="possible_service[]" id="possible_service_1" value="신체서비스" disabled--}}
                                   {{--{{ Input::checked($possible_service , "신체서비스") }}--}}
                                {{-->--}}
                                {{--<label for="possible_service_1">신체서비스</label>--}}
                                {{--<input type="checkbox" name="possible_service[]" id="possible_service_2" value="가사서비스" disabled--}}
                                    {{--{{ Input::checked($possible_service , "가사서비스") }}--}}
                                {{-->--}}
                                {{--<label for="possible_service_2">가사서비스</label>--}}
                                {{--<input type="checkbox" name="possible_service[]" id="possible_service_3" value="사회활동서비스" disabled--}}
                                    {{--{{ Input::checked($possible_service , "사회활동서비스") }}--}}
                                {{-->--}}
                                {{--<label for="possible_service_3">사회활동서비스</label>--}}
                            {{--</td>--}}
                            <th>
                                성별
                            </th>
                            <td>
                                <input type="checkbox" name="member_gender[]" id="member_gender_1" value="male"
                                    {{ Input::checked($member_gender , "male") }}
                                >
                                <label for="member_gender_1">남성</label>
                                <input type="checkbox" name="member_gender[]" id="member_gender_2" value="female"
                                    {{ Input::checked($member_gender , "female") }}
                                >
                                <label for="member_gender_2">여성</label>
                                {{--<input type="checkbox" name="member_gender[]" id="member_gender_3" value="any"--}}
                                    {{--{{ Input::checked($member_gender , "any") }}--}}
                                {{-->--}}
                                {{--<label for="member_gender_3">무관</label>--}}
                            </td>
                        </tr>
                        <tr>
                            <th>연령대</th>
                            <td>
                                <input type="checkbox" name="member_age_type[]" id="member_age_type_1" value="아동"
                                    {{ Input::checked($member_age_type , "아동") }}
                                >
                                <label for="member_age_type_1">아동</label>
                                <input type="checkbox" name="member_age_type[]" id="member_age_type_2" value="청소년"
                                    {{ Input::checked($member_age_type , "청소년") }}
                                >
                                <label for="member_age_type_2">청소년</label>
                                <input type="checkbox" name="member_age_type[]" id="member_age_type_3" value="성인"
                                    {{ Input::checked($member_age_type , "성인") }}
                                >
                                <label for="member_age_type_3">성인</label>
                                <input type="checkbox" name="member_age_type[]" id="member_age_type_4" value="노인"
                                    {{ Input::checked($member_age_type , "노인") }}
                                >
                                <label for="member_age_type_4">노인</label>
                                <input type="checkbox" name="member_age_type[]" id="member_age_type_5" value="무관"
                                    {{ Input::checked($member_age_type , "무관") }}
                                >
                                <label for="member_age_type_5">무관</label>
                            </td>
                            <th>
                                와상장애 제외여부
                            </th>
                            <td>
                                <input type="checkbox" name="member_trauma_disorder_check" id="member_trauma_disorder_check" value="1"
                                        {{ Input::checked($member_trauma_disorder_check , "1") }}
                                >
                                <label for="member_trauma_disorder_check">와상장애 제외</label>
                            </td>
                        </tr>
                    </table>
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
                    <th>계약시작일</th>
                    <th>주장애</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($lists as $list)
                    <tr>
                        <td>
                            {{ (( $page - 1) * 15) + $loop->iteration }}
                        </td>
                        <td>
                            {{ $list->name  }}
                        </td>
                        <td>
                            {{ Custom::rsno_to_birth($list->rsNo) }}
                        </td>
                        <td>
                            {{ Custom::rsno_to_gender($list->rsNo) }}
                        </td>
                        <td>
                            {{ $list->phone  }}
                        </td>
                        <td>
                            {{ $list->address }}
                        </td>
                        <td>
                            {{ Custom::contact_status($list->contract_start_date, $list->contract_end_date) }}
                        </td>
                        <td>
                            {{ $list->regdate ?? "" }}
                        </td>
                        <td>
                            {{ $list->contract_start_date }}
                        </td>
                        <td>
                            {{ $list->main_disable_name ?? "" }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10">
                        </td>
                    </tr>
                @endforelse
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
    </script>

@endsection
