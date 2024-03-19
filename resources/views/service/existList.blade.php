@extends("layouts/layout")

@section("title")
    서비스내역 - 서비스내역 조회(기존)
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("service.side_nav")

    <section id="member_wrap" class="service-list list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>서비스내역 조회(기존)</h1>
            </div>

            <div class="search-wrap">
                <form action="/service/list/exist/1" method="get" name="member_search">
                    <div class="limit-wrap">
                        <div class="limit-wrap">
                            <span>대상자명</span>
                            <input type="text" name="target_name" class="type1" value="{{ $_GET['target_name'] ?? "" }}">
                            <span>제공인력명</span>
                            <input type="text" name="provider_name" class="type1"  value="{{ $_GET['provider_name'] ?? "" }}">
                            <button type="submit">조회</button>
                        </div>
                    </div>
                    {{--<div class="search-input">--}}
                        {{--<input type="text" name="term" placeholder="검색">--}}
                        {{--<button type="submit">--}}
                            {{--<img src="/storage/img/search_icon.png" alt="검색하기">--}}
                        {{--</button>--}}
                    {{--</div>--}}
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents" style="overflow-x: auto;">

            <p class="m-bottom-10">서비스내역 총 <b class="acc-orange">{{ $paging }}</b> 건</p>

            <table class="member-list b-last-bottom table-6x-large">
                <thead>
                <tr>
                    <th class="t-left">
                        <input type="checkbox" name="check_all" value="1" id="check_all" class="p-m-clear m-clear">
                        <label for="check_all"></label>
                    </th>
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
                        대상자ID
                    </th>
                    <th>
                        시/도 코드
                    </th>
                    <th>
                        시/도명
                    </th>
                    <th>
                        시/군/구 코드
                    </th>
                    <th>
                        시/군/구명
                    </th>
                    <th>
                        대상자등급
                    </th>
                    <th>
                        제공인력명
                    </th>
                    <th>
                        생년월일
                    </th>
                    <th>
                        결제유형
                    </th>
                    <th>
                        단말기모델
                    </th>
                    <th>
                        CAT-ID
                    </th>
                    <th>
                        결제폰정보
                    </th>
                    <th>
                        시리얼번호
                    </th>
                    <th>
                        결제일시
                    </th>
                    <th>
                        승인번호
                    </th>
                    <th>
                        대상년월
                    </th>
                    <th>
                        승인금액
                    </th>
                    <th>
                        정부지원금 합계
                    </th>
                    <th>
                        본인부담금 합계
                    </th>
                    <th>
                        기본급여 정부지원금
                    </th>
                    <th>
                        기본급여 본인부담금
                    </th>
                    <th>
                        추가급여 정부지원금
                    </th>
                    <th>
                        추가급여 본인부담금
                    </th>
                    <th>
                        결제구분
                    </th>
                    <th>
                        결제인원
                    </th>
                    <th>
                        사업구분
                    </th>
                    <th>
                        사업유형
                    </th>
                    <th>
                        서비스유형
                    </th>
                    <th>
                        시작시간
                    </th>
                    <th>
                        종료시간
                    </th>
                    <th>
                        서비스 제공시간 합계
                    </th>
                    <th>
                        사회활동지원
                    </th>
                    <th>
                        신체활동지원
                    </th>
                    <th>
                        가사활동지원
                    </th>
                    <th>
                        기타서비스
                    </th>
                    <th>
                        차량내입욕
                    </th>
                    <th>
                        가정내입욕
                    </th>
                    <th>
                        기본간호
                    </th>
                    <th>
                        치료간호
                    </th>
                    <th>
                        교육상담
                    </th>
                    <th>
                        방문간호지시사
                    </th>
                    <th>
                        일괄결제사유
                    </th>
                    <th>
                        지급일자
                    </th>
                    <th>
                        반납구분
                    </th>
                    <th>
                        반납승인일자
                    </th>
                    <th>
                        가산여부
                    </th>
                    <th>
                        가산금액
                    </th>
                    <th>
                        지급보류내역
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($lists as $i => $list)
                    <tr>
                        <td class="t-left">
                            <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}" value="{{$list->id}}" class="p-m-clear m-clear">
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>{{ ($i+1) + (15 * ($page-1)) }}</td>
                        <td>{{ $list->target_name }}</td>
                        <td>{{ convert_birth($list->target_birth) }}</td>
                        <td>{{ $list->target_id }}</td>
                        <td>{{ $list->city_code }}</td>
                        <td>{{ $list->city_name }}</td>
                        <td>{{ $list->sigungu_code }}</td>
                        <td>{{ $list->sigungu_name }}</td>
                        <td>{{ $list->target_grade }}</td>
                        <td>
                            {{ $list->provider_name }}
                        </td>
                        <td>{{ $list->provider_birth }}</td>
                        <td>
                            {{ $list->payment_type }}
                        </td>
                        <td>{{ $list->device_model }}</td>
                        <td>{{ $list->cat_id }}</td>
                        <td>
                            {{ $list->payment_phone_info }}
                        </td>
                        <td>
                            {{ $list->serial_number }}
                        </td>
                        <td>
                            {{ $list->payment_datetime }}
                        </td>
                        <td>
                            {{ $list->confirm_number }}
                        </td>
                        <td>
                            {{ $list->target_ym }}
                        </td>
                        <td>
                            {{ $list->confirm_price }}
                        </td>
                        <td>
                            {{ $list->government_support_total }}
                        </td>
                        <td>
                            {{ $list->personal_charge_total }}
                        </td>
                        <td>
                            {{ $list->basic_pay_government_support }}
                        </td>
                        <td>
                            {{ $list->basic_pay_personal_charge }}
                        </td>
                        <td>
                            {{ $list->add_pay_government_support }}
                        </td>
                        <td>
                            {{ $list->add_pay_personal_charge }}
                        </td>
                        <td>
                            {{ $list->payment_division }}
                        </td>
                        <td>
                            {{ $list->payment_personnel }}
                        </td>
                        <td>
                            {{ $list->business_division }}
                        </td>
                        <td>
                            {{ $list->business_type }}
                        </td>
                        <td>
                            {{ $list->service_type }}
                        </td>
                        <td>
                            {{ $list->start_date_mdhi }}
                        </td>
                        <td>
                            {{ $list->end_date_mdhi }}
                        </td>
                        <td>
                            {{ $list->service_provide_time_total }}
                        </td>
                        <td>
                            {{ $list->social_activity_support }}
                        </td>
                        <td>
                            {{ $list->physical_activity_support }}
                        </td>
                        <td>
                            {{ $list->housekeeping_activity_support }}
                        </td>
                        <td>
                            {{ $list->etc_service }}
                        </td>
                        <td>
                            {{ $list->car_bath }}asd
                        </td>
                        <td>
                            {{ $list->home_bath }}
                        </td>
                        <td>
                            {{ $list->basic_care }}
                        </td>

                        <td>
                            {{ $list->cure_care }}
                        </td>
                        <td>
                            {{ $list->edu_counseling }}
                        </td>
                        <td>
                            {{ $list->visit_care_order }}
                        </td>
                        <td>{{ $list->batch_payment_reason }}</td>
                        <td>{{ $list->payment_date }}</td>
                        <td>
                            {{ $list->return_sort }}
                        </td>
                        <td>
                            {{ $list->return_confirm_date }}
                        </td>
                        <td>
                            {{ $list->addition_check }}
                        </td>

                        <td>
                            {{ $list->add_price }}
                        </td>

                        <td>
                            {{ $list->payment_hold_log }}
                        </td>

                    </tr>
                @endforeach
                @if ($lists->isEmpty())
                    <tr>
                        <td colspan="52" class="t-left" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
                    </tr>
                @endif
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">
            {!! pagination(15, ceil($paging/15)) !!}
        </article> <!-- article list_bottom end -->

    </section>

    <script>

        // 짝으로 붙여줘야 함.
        var datepicker_selector = [
            "#from_date", "#to_date"
        ];

        $.each(datepicker_selector, function(idx, target) {

            $(target).datepicker({

                language: 'ko',
                dateFormat:"yyyy-mm",
                view: 'months',
                minView: 'months',
                clearButton: false,
                autoClose: true,
                onSelect: function(dateText, inst) {

                    // 반복이 짝수일땐 다음거 최소날짜 설정해주기
                    if (idx%2 == 0) {
                        $(datepicker_selector[idx+1]).datepicker({
                            minDate: new Date(dateText),
                            dateFormat:"yyyy-mm",
                            clearButton: false,
                            autoClose: true,
                        })
                    }

                }
            });

        });


    </script>

@endsection
