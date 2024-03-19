@extends("layouts/layout")

@section("title")
    활동지원사 - 수당 지급내역 현황
@endsection


@section("content")
<link rel="stylesheet" href="/css/member/index.css">
@include("worker.side_nav")



<section id="member_wrap" class="worker_allowance list_wrapper">

    <article id="list_head">

        <div class="head-info exist-right">
            <h1>수당 지급내역 현황 <span>활동지원급여를 지급하 수당 현황을 조회합니다</span></h1>
        </div>

        <div class="right-buttons">
            <ul>
                <li>
                    <button type="button" class="orange">엑셀 내려받기</button>
                </li>
            </ul>
        </div>

        <div class="search-wrap">
            <form action="" method="post" name="member_search">
                <div class="limit-wrap">
                    <div class="limit-wrap">
                        <span><b>연도</b></span>
                        <a href="#" class="btn-arrow"> < </a>
                        <input type="text" name="from_date" autocomplete="off" readonly id="from_date">
                        <label for="from_date">
                            <img src="/storage/img/icon_calendar.png" alt="시작날짜">
                        </label>
                        <a href="#" class="btn-arrow arrow-right"> > </a>

                        <span><b>분류</b></span>
                        <select name="filter" id="filter">
                            <option value="">기본급 및 수당</option>
                        </select>

                        <button type="submit">조회</button>
                    </div>
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

    <article id="list_contents" class="over-x-auto">
        
        <table class="table-2x-large table-1">
            <colgroup>
                <col width="2%">
                <col width="4%">
                <col width="4%">
                <col width="4%">
                <col width="4%">
                <col width="4%">
            </colgroup>
            <thead>
            <tr class="date-into">
                <th rowspan="2">
                    <input type="checkbox" name="check_all" id="check_all">
                    <label for="check_all"></label>
                </th>
                <th rowspan="2">No</th>
                <th rowspan="2">이름</th>
                <th rowspan="2">생년월일</th>
                <th rowspan="2">입사일자</th>
                <th rowspan="2">퇴사일자</th>
                <th colspan="2">2020-01</th>
                <th colspan="2">2020-02</th>
                <th colspan="2">2020-03</th>
                <th colspan="2">2020-04</th>
                <th colspan="2">2020-05</th>
                <th colspan="2">2020-06</th>
                <th colspan="2">2020-07</th>
                <th colspan="2">2020-08</th>
                <th colspan="2">2020-09</th>
                <th colspan="2">2020-10</th>
            </tr>
            <tr class="monthly-info">
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
                <th>기본급</th>
                <th>수당합계</th>
            </tr>
            </thead>
            <tbody>
            <tr class="total">
                <th></th>
                <th>합계</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>

                <th>17,058,574</th>
                <th>17,058,574</th>

                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>
                <th>0</th>

            </tr>

            @for($i=0; $i<15; $i++)
                <tr class="data">
                    <td>
                        <input type="checkbox" name="check_{{$i}}" id="check_{{$i}}">
                        <label for="check_{{$i}}"></label>
                    </td>
                    <td>{{$i+1}}</td>
                    <td>홍길동</td>
                    <td>57-03-03</td>
                    <td>2020-10-07</td>
                    <td></td>
                    <td>19</td>
                    <td>1</td>
                    <td>73</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>

                </tr>
            @endfor
            </tbody>
        </table>

    </article> <!-- article list_contents end -->

    <p class="acc-orange">
        * 급여계산시 저장된 내역으로 조회하므로, 이전에 급여계산 시 반영되지 않았던 기간의 근로일수, 활동시간은 실제와 다를 수 있습니다.
    </p>

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