@extends("layouts/layout")

@section("title")
    사회보험 - 사회보험 가입정보 관리
@endsection

@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    @include("salary.side_nav")


    <section id="member_wrap" class="social-insurance-list list_wrapper">

        <article id="list_head">

            <div class="head-info">
                <h1>사회보험 가입정보 관리 <span>활동지원사의 사회보험 가입 정보를 일괄로 수정합니다</span></h1>
                <div class="right-buttons">
                    <button class="btn-black-middle">
                        선택수정
                    </button>
                </div>
            </div>

            <div class="search-wrap">
                <form action="" method="get" name="member_search">
                    <div class="limit-wrap">
                        <p>
                            * 체크박스로 수정한 항목을 선택한 후 수정 버튼을 눌러주셔야 반영됩니다.
                        </p>
                        <p>
                            * 기관에서 관리하는 활동지원사의 기본 정보 중 사회보험(국민, 건강, 고용, 산재)의 가입여부, 보수월액 및 사회보험 정보들에 대해 일괄로 저장하실 수 있습니다.
                        </p>
                    </div>
                    <div class="search-input">
                        <input type="text" name="term" placeholder="검색">
                        <button type="submit">
                            <img src="/storage/img/search_icon.png" alt="검색하기">
                        </button>
                    </div>
                </form>
            </div>

        </article> <!-- article list_head end -->

        <article id="list_contents">

            <table class="member-list b-last-bottom">
                <colgroup>
                    <col width="2%">
                    <col width="4%">
                </colgroup>
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="check_all" id="check_all">
                        <label for="check_all"></label>
                    </th>
                    <th>No</th>
                    <th>이름</th>
                    <th>생년월일</th>
                    <th>접수일</th>
                    <th>입사일</th>
                    <th>퇴사일자</th>
                    <th>국민연급 가입</th>
                    <th>국민연습<br>보수월액</th>
                    <th>건강보험 가입</th>
                    <th>건강보험<br>보수월액</th>
                    <th>장기요양 경감</th>
                    <th>고용보험 가입</th>
                    <th>고용보험<br>보수월액</th>
                    <th>고용보험<br>65세 이후</th>
                    <th>산재보험 가입</th>
                    <th>산재보험<br>보수월액</th>
                </tr>
                </thead>
                <tbody>
                @for ($i=0; $i<15; $i++)
                    <tr>
                        <td>
                            <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}">
                            <label for="check_{{$i}}"></label>
                        </td>
                        <td>
                            {{$i+1}}
                        </td>
                        <td>홍길동</td>
                        <td>57-07-30</td>
                        <td>2020-01-01</td>
                        <td>2020-01-01</td>
                        <td>202001-01</td>
                        <td>
                            <select name="national_pension_join_check_{{$i}}" id="national_pension_join_check_{{$i}}">
                                <option value="">미가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="national_pension_monthly_price_{{$i}}">
                        </td>
                        <td>
                            <select name="health_insurance_{{$i}}" id="health_insurance_{{$i}}">
                                <option value="">미가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="health_insurance_monthly_price_{{$i}}">
                        </td>
                        <td>
                            <input type="checkbox" name="long_term_care_sub_check_{{$i}}" id="long_term_care_sub_check_{{$i}}">
                            <label for="long_term_care_sub_check"></label>
                        </td>
                        <td>
                            <select name="employ_insurance_{{$i}}" id="employee_insurance_{{$i}}">
                                <option value="">미가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="employ_insurance_monthly_price{{$i}}">
                        </td>
                        <td>
                            <input type="checkbox" name="employ_after_65age_{{$i}}" id="employ_after_65age_{{$i}}">
                            <label for="employ_after_65age_{{$i}}"></label>
                        </td>
                        <td>
                            <select name="industrial_insurance_{{$i}}" id="industrial_insurance_{{$i}}">
                                <option value="">미가입</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="industrial_insurance_monthly_price{{$i}}">
                        </td>
                    </tr>
                @endfor
                </tbody>
            </table>

        </article> <!-- article list_contents end -->

        <article id="list_bottom">

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
