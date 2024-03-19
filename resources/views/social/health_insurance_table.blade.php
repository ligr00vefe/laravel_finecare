<table class="member-list b-last-bottom table-3x-large">
    <thead>
    <tr>
        <th>
            No
        </th>
        <th>
            증번호
        </th>
        <th>
            생년월일
        </th>
        <th>
            가입자명
        </th>
        <th>
            보수월액
        </th>
        <th>구분</th>
        <th>
            산출보험료
        </th>
        <th>
            정산보험료
        </th>
        <th>
            정산사유
        </th>
        <th>
            정산적용기간
        </th>
        <th>
            감면사유
        </th>
        <th>
            연말정산
        </th>
        <th>
            환급금이자
        </th>
        <th>
            고지보험료
        </th>
        <th>
            회계
        </th>
        <th>
            영업소기호
        </th>
        <th>
            직종
        </th>
        <th>
            취득일
        </th>
        <th>
            구분
        </th>
        <th>
            산출보험료
        </th>
        <th>
            정산보험료
        </th>
        <th>
            정산사유
        </th>
        <th>
            정산적용기간
        </th>
        <th>
            감면사유
        </th>
        <th>
            연말정산
        </th>
        <th>
            환급금이자
        </th>
        <th>
            고지보험료
        </th>
        <th>
            회계
        </th>
        <th>
            영업소기호
        </th>
        <th>
            직종
        </th>
        <th>
            상실일
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($lists as $i => $list)
        <tr>
            <td>{{ ($i+1) + (($page-1) * 15) }}</td>
            <td>{{ $list->proof_number }}</td>
            <td>{{ convert_birth($list->rsNo) }}</td>
            <td>{{ $list->name }}</td>
            <td>{{ $list->monthly_price }}</td>
            <td>{{ $list->division }}</td>
            <td>{{ $list->prod_insurance_price }}</td>
            <td>{{ $list->cal_insurance_price }}</td>
            <td>{{ $list->cal_reason }}</td>
            <td>{{ date("y-m-d", strtotime($list->cal_period)) }}</td>
            <td>{{ $list->reduction_reason }}</td>
            <td>{{ $list->year_end_tax }}</td>
            <td>{{ $list->refund_interest }}</td>
            <td class="t-right">
                {{ $list->notice_insurance_price }}
            </td>
            <td>{{ $list->accounting }}</td>
            <td>{{ $list->business_symbol }}</td>
            <td>{{ $list->job_type }}</td>
            <td>{{ date("y-m-d", strtotime($list->gain_date)) }}</td>
            <td>{{ $list->division2 }}</td>
            <td class="t-right">
                {{ $list->prod_insurance_price2 }}
            </td>
            <td class="t-right">
                {{ $list->cal_insurance_price2 }}
            </td>
            <td class="t-right">
                {{ $list->cal_reason2 }}
            </td>
            <td>
                {{ date("y-m-d", strtotime($list->cal_period2)) }}
            </td>
            <td>{{ $list->reduction_reason2 }}</td>
            <td>{{ $list->year_end_tax2 }}</td>
            <td>{{ $list->refund_interest2 }}</td>
            <td class="t-right">
                {{ $list->notice_insurance_price2 }}
            </td>
            <td class="t-right">
                {{ $list->accounting2 }}
            </td>
            <td>{{ $list->business_symbol2 }}</td>
            <td>{{ $list->job_type2 }}</td>
            <td>
                {{ date("y-m-d", strtotime($list->loss_date)) }}
            </td>
        </tr>
    @endforeach
    @if ($lists->isEmpty())
        <tr>
            <td colspan="31" style="border-bottom:1px solid #b7b7b7;">데이터가 없습니다.</td>
        </tr>
    @endif
    </tbody>
</table>