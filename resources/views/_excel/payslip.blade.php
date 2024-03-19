@php
    use \App\Classes\Custom;
@endphp

{{--{{dd($lists)}}--}}

<div class="payslip-wrap">
    <div id="payslip" class="payslip">

        @foreach($lists['payment'] as $key => $list)
            <table>
                <thead>
                <tr>
                    <th style="font-size: 22px" align="center" class="payslip-head__text" colspan="4">
                        <p class="payslip-head__text">{{ $year }}년 {{ $month }}월 급여명세서</p>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7" >
                        근로자명
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{$lists['worker'][$key]->provider_name ?? ""}}
                    </td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        생년월일
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{Custom::regexOnlyNumber($lists['worker'][$key]->provider_key ?? 0)}}
                    </td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        입사일자
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{date("Y-m-d", strtotime($lists['worker'][$key]->join_date)) === "1970-01-01" ? "" : date("Y-m-d", strtotime($lists['worker'][$key]->join_date))}}
                    </td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        근속기간
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{
                            isset($lists['worker']->join_date)
                            ? Custom::calcLongevity($lists['worker'][$key]->join_date, $lists['worker'][$key]->resign_date)
                            : ""
                        }}
                    </td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        연령
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        재직여부
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ Custom::inOfficeCheck($lists['worker'][$key]->provider_key ?? "") }}
                    </td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        부양가족수
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{$lists['worker'][$key]->dependents}}
                    </td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        비고
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                <tr>
                    <th colspan="4" style="font-size: 15px; text-align: left">근로시간 및 급여내역</th>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        총근로시간
                    </th>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        휴일시간
                    </th>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        야간시간
                    </th>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        지급금액
                    </th>
                </tr>
                <tr>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['payment'][$key]['time_total'] }}
                    </td>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['payment'][$key]['time_holiday'] }}
                    </td>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['payment'][$key]['time_night'] }}
                    </td>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['payment'][$key]['pay_total'] }}
                    </td>
                </tr>
                <tr>
                    <th colspan="4" style="font-size: 15px; text-align: left">공제내역</th>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        국민연금
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['tax'][$key]->tax_nation_pension }}
                    </td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        건강보험
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['tax'][$key]->tax_health }}
                    </td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        고용보험
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        소득세
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        고용보험
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['tax'][$key]->tax_employ }}
                    </td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        소득세
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        주민세
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['tax'][$key]->tax_joominse }}
                    </td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        건강보험정산
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        연말정산
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        단말기보증금
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        기타공제
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        공제총액
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                        {{ $lists['tax'][$key]->tax_total }}
                    </td>
                </tr>
                <tr>
                    <th colspan="4" style="font-size: 15px; text-align: left; border: 1px solid #b7b7b7">
                        차인지급액
                    </th>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                        지급총액
                    </th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">{{ $lists['payment'][$key]['pay_total'] }}</td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">공제총액</th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">{{ $lists['tax'][$key]->tax_total }}</td>
                </tr>
                <tr>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">실지급액</th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">{{ $lists['tax'][$key]->tax_sub_payment }}</td>
                    <th style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">비고</th>
                    <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                </tr>
                </tbody>
            </table>
        @endforeach
    </div>
</div>

