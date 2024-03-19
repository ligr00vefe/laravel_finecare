@extends("layouts/layout")

@section("title")
    급여관리 - 급여명세서
@endsection

@php
use \App\Classes\Custom;
@endphp


@section("content")
    <link rel="stylesheet" href="/css/member/index.css">
    <link rel="stylesheet" href="/css/member/payslip.css">

    @include("salary.side_nav")

    <section id="member_wrap" class="salary-cal-wrap  list_wrapper">
        <article id="list_head">

            <div class="head-info">
                <h1>급여명세서</h1>
            </div>

             <div class="form-wrap">
                 <form action="">
                     <ul>
                         <li>
                             지급연월
                             <input type="text" name="from_date" id="from_date" value="{{ $from_date }}" class="input-datepicker" autocomplete="off" readonly>
                             <label for="from_date">
                                 <img src="/storage/img//icon_calendar.png" alt="달력켜기">
                             </label>
                         </li>
                         <li>
                             성명
                             <input type="text" name="name" value="{{ $name }}">
                         </li>
                         <li>
                             주민등록번호 앞자리
                             <input type="text" name="birth" id="birth" placeholder="" value="{{ $birth }}">
                         </li>
                         <li>
                             <button class="btn-black-small">검색</button>
                         </li>
                     </ul>
                 </form>
             </div>
            @if (isset($list['voucher']) && $multiple_check === 1)
            <div class="contents">
                <div class="payslip-wrap">
                    <div id="payslip" class="payslip">

                        <div class="payslip-head">
                            <p class="payslip-head__text">{{ $year }}년 {{ $month }}월 급여명세서</p>
                        </div>
                        <div class="payslip-body">


                            <div class="body__user m-top-25">
                                <table>
                                    <tr>
                                        <th>근로자명</th>
                                        <td>{{ $list['worker']->provider_name ?? "" }}</td>
                                        <th>생년월일</th>
                                        <td>{{ Custom::regexOnlyNumber($provider_key) }}</td>
                                    </tr>
                                    <tr>
                                        <th>입사일자</th>
                                        <td>{{ date("Y-m-d", strtotime($list['worker']->join_date)) == "1970-01-01" ? "" : date("Y-m-d", strtotime($list['worker']->join_date)) }}</td>
                                        <th>근속기간</th>
                                        <td>
                                            {{ isset($list['worker']->join_date)
                                                ? Custom::calcLongevity($list['worker']->join_date, $list['worker']->resign_date)
                                                : ""
                                            }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>연령</th>
                                        <td></td>
                                        <th>재직여부</th>
                                        <td>{{ Custom::inOfficeCheck($provider_key) }}</td>
                                    </tr>
                                    <tr>
                                        <th>부양가족수</th>
                                        <td>{{ $list['worker']->dependents }}</td>
                                        <th>비고</th>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>

                            <div class="body__pay m-top-25">
                                <p class="table-title before-dot">근로시간 및 급여내역</p>
                                <table>
                                    <tr>
                                        <th>총근로시간</th>
                                        <th>휴일시간</th>
                                        <th>야간시간</th>
                                        <th>지급금액</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $list['payment']['time_total'] }}</td>
                                        <td>{{ $list['payment']['time_holiday'] }}</td>
                                        <td>{{ $list['payment']['time_night'] }}</td>
                                        <td>{{ $list['payment']['pay_total'] }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="body__tax m-top-25">
                                <p class="table-title before-dot">공제내역</p>
                                <table>
                                    <tr>
                                        <th>국민연금</th>
                                        <td>{{ $list['tax']->tax_nation_pension }}</td>
                                        <th>건강보험</th>
                                        <td>{{ $list['tax']->tax_health }}</td>
                                    </tr>
                                    <tr>
                                        <th>고용보험</th>
                                        <td>{{ $list['tax']->tax_employ }}</td>
                                        <th>소득세</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>주민세</th>
                                        <td>{{ $list['tax']->tax_joominse }}</td>
                                        <th>건강보험정산</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>연말정산</th>
                                        <td></td>
                                        <th>단말기보증금</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>기타공제</th>
                                        <td></td>
                                        <th>공제총액</th>
                                        <td>{{ $list['tax']->tax_total }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div class="body__result m-top-25">
                                <p class="table-title before-dot">차인지급액</p>

                                <table>
                                    <tr>
                                        <th>지급총액</th>
                                        <td>{{ $list['payment']['pay_total'] }}</td>
                                        <th>공제총액</th>
                                        <td>{{ $list['tax']->tax_total }}</td>
                                    </tr>
                                    <tr>
                                        <th>실지급액</th>
                                        <td>{{ $list['tax']->tax_sub_payment }}</td>
                                        <th>비고</th>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="btn-wrap">
                    <button type="button" id="savePdf" class="payslip-download__button">PDF다운</button>
                    <button type="button" id="print" class="payslip-action__button ">출력하기</button>
                </div>

            </div>
            @elseif($multiple_check === 2)
                <div class="payslip-result">
                    <span class="payslip-point">{{count($list['payment'])}}</span> 명 조회됩니다. 다운로드해서 확인해주세요.
                </div>

                <div class="btn-wrap">
                    <form action="{{ route("pdf.payslip") }}" method="post" class="payslip-pdf-form">
                        @csrf
                        <input type="hidden" name="from_date" value="{{$from_date}}">
                        <input type="hidden" name="name" value="{{$name}}">
                        <input type="hidden" name="birth" value="{{$birth}}">
                        <input type="submit" class="payslip-download__button" value="PDF 다운로드">
                    </form>
                    <button type="button" id="print" class="payslip-action__button ">출력하기</button>
                </div>
            @endif
        </article>
    </section>

    <script>
        $("#print").on("click", function () {
            const completeParam = makeHtml();
            reportPrint(completeParam);
        });

        function makeHtml() {
            const obj = {html : ''};
            let html = '<div class="printPop">';
            html += $(".payslip")[0].innerHTML;
            html += '</div>';
            obj.html = html;
            return obj;
        }

        function reportPrint(param) {
            const setting = "width=890, height=841";
            const objWin = window.open('', 'print', setting);
            objWin.document.open();
            objWin.document.write('<html><head><title></title>');
            objWin.document.write('<link rel="stylesheet" type="text/css" href="/css/member/payslip.css"/>');
            objWin.document.write('</head><body>');
            objWin.document.write(param.html);
            objWin.document.write('</body></html>');
            objWin.focus();
            objWin.document.close();

            setTimeout(function(){objWin.print();objWin.close();}, 1000);
        }


        $(document).ready(function() {
            $('#savePdf').click(function() { // pdf저장 button id

                var payslip = $("#payslip").clone();
                payslip.addClass("cloned");
                $("body").prepend(payslip);
                $("body").css("margin", 0).css("padding", 0);
                $(".cloned").css("width", "50%").css("padding", "20px 20px").css("margin", "10px 0 0 0");
                window.scrollTo(0,0);
                $("#app").hide();


                html2canvas($('.cloned')[0]).then(function(canvas) { //저장 영역 div id

                    // 캔버스를 이미지로 변환
                    var imgData = canvas.toDataURL('image/png');

                    var imgWidth = 190; // 이미지 가로 길이(mm) / A4 기준 210mm
                    var pageHeight = 295+40;  // 출력 페이지 세로 길이 계산 A4 기준
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;
                    var margin = 10; // 출력 페이지 여백설정
                    var doc = new jsPDF('p', 'mm');
                    var position = 0;

                    // 첫 페이지 출력
                    doc.addImage(imgData, 'PNG', margin, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    // 한 페이지 이상일 경우 루프 돌면서 출력
                    while (heightLeft >= 20) {
                        position = heightLeft - imgHeight;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // 파일 저장
                    doc.save('{{ "{$from_date}_{$provider_key}_급여명세서" }}.pdf');
                });

                $(".cloned").remove();
                $("#app").show();

            });

        });



        $("#from_date").datepicker({
            language: 'ko',
            dateFormat:"yyyy-mm",
            view: 'months',
            minView: 'months',
            clearButton: false,
            autoClose: true,
        });


        $("a[href='/salary/payslip']").parent("li").addClass("on");


    </script>

@endsection
