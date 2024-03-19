@php
    use \App\Classes\Custom;
@endphp

<head>
    <meta http-ｅquiv='Content-Type' content='text/html; charset=utf-8'/>
    <title>PDF</title>
</head>
<style>

    @font-face {
        font-family: 'Nanum Gothic';
        src: url({{storage_path('fonts/NanumGothic.ttf')}}) format("truetype");
    }
    @font-face {
        font-family: 'Nanum Gothic-Bold';
        src: url({{storage_path('fonts/NanumGothicBold.ttf')}}) format("truetype");
    }

    *{font-family:'Nanum Gothic-Bold', '나눔고딕', 'dotum', '돋움'; font-size: 11px;}

    .bold{font-family: 'Nanum Gothic-Bold'}

    .page-break {page-break-after: always;}

    .pdf-wrap{height: 820px}
    .title-background{background-color: #f3f3f3;}
    td{width: 160px; height: 47px}

</style>




<div class="pdf-wrap">
    @foreach($lists['payment'] as $key => $list)

        <table class="page-break" style="margin:0 auto">

            <tr>
                <td colspan="4" style="text-align: center">
                    <p style="font-size: 22px;" class="bold">{{ $year }}년 {{ $month }}월 급여명세서</p>
                </td>
            </tr>
            <tbody>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7" >
                    근로자명
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{$list['data']->provider_name ?? ""}}
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    생년월일
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{Custom::regexOnlyNumber($list['data']->provider_key ?? 0)}}
                </td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    입사일자
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{date("Y-m-d", strtotime($list['data']->join_date)) === "1970-01-01" ? "" : date("Y-m-d", strtotime($list['data']->join_date))}}
                </td>
                <td class="content" class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    근속기간
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ isset($list['data']->join_date)
                        ? Custom::calcLongevity($list['data']->join_date, $list['data']->resign_date) : ""
                    }}
                </td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    연령
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    재직여부
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ Custom::inOfficeCheck($list['data']->provider_key ?? 0) }}
                </td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    부양가족수
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{$list['data']->dependents}}
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    비고
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
            </tr>
            <tr>
                <td colspan="4" style="font-size: 15px; padding-left: 20px; text-align: left">근로시간 및 급여내역</td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    총근로시간
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    휴일시간
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    야간시간
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    지급금액
                </td>
            </tr>
            <tr>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['time_total'] }}
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['time_holiday'] }}
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['time_holiday'] }}
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['pay_total'] }}
                </td>
            </tr>
            <tr>
                <td colspan="4" style="font-size: 15px; padding-left: 20px; text-align: left">공제내역</td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    국민연금
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{  $list['data']->tax_nation_pension }}
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    건강보험
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['data']->tax_health }}
                </td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    고용보험
                </td>
                <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    소득세
                </td>
                <td style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    고용보험
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['data']->tax_employ }}
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    소득세
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    주민세
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['data']->tax_joominse }}
                </td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    건강보험정산
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    연말정산
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    단말기보증금
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    기타공제
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">
                    공제총액
                </td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">
                    {{ $list['data']->tax_total }}
                </td>
            </tr>
            <tr>
                <td colspan="4" style="font-size: 15px; padding-left: 20px; text-align: left;">차인지급액</td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">지급총액</td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">{{ $list['pay_total'] ?? "" }}</td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">공제총액</td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">{{ $list['data']->tax_total }}</td>
            </tr>
            <tr>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">실지급액</td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7">{{ $list['data']->tax_sub_payment }}</td>
                <td class="title-background" style="font-size: 15px; text-align: center; border: 1px solid #b7b7b7">비고</td>
                <td class="content" style="text-align: center; color:#707070; font-size: 15px; border: 1px solid #b7b7b7"></td>
            </tr>
            </tbody>
        </table>
    @endforeach

</div>

