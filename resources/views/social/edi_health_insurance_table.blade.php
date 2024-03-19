<table class="member-list b-last-bottom table-3x-large">
    <thead>
    <tr>
        <th>
            No
        </th>
        <th>
            고지년월
        </th>
        <th>
            사업장관리번호
        </th>
        <th>
            단위사업장<br>(단위기관)
        </th>
        <th>
            고지차수
        </th>
        <th>
            회계
        </th>
        <th>
            증번호
        </th>
        <th>
            성명
        </th>
        <th>
            생년월일
        </th>
        <th>
            감면사유
        </th>
        <th>
            직종
        </th>
        <th>
            등급
        </th>
        <th>
            보수월액
        </th>
        <th>
            산출보험료
        </th>
        <th>
            정산사유
        </th>
        <th>
            시작월
        </th>
        <th>
            종료월
        </th>
        <th>
            정산금액
        </th>
        <th>
            고지금액
        </th>
        <th>
            연말정산
        </th>
        <th>
            취득일
        </th>
        <th>
            상실일
        </th>
        <th>
            요양산출<br>보험료
        </th>
        <th>
            요양정산<br>사유코드
        </th>
        <th>
            요양시작월
        </th>
        <th>
            요양종료월
        </th>
        <th>
            요양정상<br>보험료
        </th>
        <th>
            요양고지<br>보험료
        </th>
        <th>
            요양연말<br>정산보험료
        </th>
        <th>
            산출보험료<br>계(건강+요양)
        </th>
        <th>
            정신보험료<br>계(건강+요양)
        </th>
        <th>
            고지보험료<br>
            계(건강+요양)
        </th>
        <th>
            연말정산보험료<br>
            계(건강+요양)
        </th>
        <th>
            건강환급금<br>
            이자
        </th>
        <th>
            요양환급금<br>
            이자
        </th>
        <th>
            가입자총납부할<br>
            보험료
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($lists as $i => $list)
        <tr>
            <td>{{ ($i+1) + (($page-1) * 15) }}</td>
            <td>
                {{ $list->target_ym }}
            </td>
            <td>
                {{ $list->business_license }}
            </td>
            <td>
                {{ $list->unit_office }}
            </td>
            <td>
                {{ $list->high_order_number }}
            </td>
            <td>
                {{ $list->accounting }}
            </td>
            <td>
                {{ $list->proof_number }}
            </td>
            <td>
                {{ $list->name }}
            </td>
            <td>
                {{ convert_birth($list->rsNo) }}
            </td>
            <td>
                {{ $list->reduction_reason }}
            </td>
            <td>
                {{ $list->job_type }}
            </td>
            <td>
                {{ $list->grade }}
            </td>
            <td>
                {{ number_format($list->monthly_bosu_price) }}
            </td>
            <td>
                {{ number_format($list->cal_ins_price) }}
            </td>
            <td>
                {{ $list->account_reason }}
            </td>
            <td>
                {{ $list->start_date }}
            </td>
            <td>
                {{ $list->end_date }}
            </td>
            <td>
                {{ number_format($list->account_price) }}
            </td>
            <td>
                {{ number_format($list->notice_price) }}
            </td>
            <td>
                {{ number_format($list->year_end_tax) }}
            </td>
            <td>
                {{ $list->gave_date }}
            </td>
            <td>
                {{ $list->lose_date }}
            </td>
            <td>
                {{ number_format($list->recup_cal_ins_price) }}
            </td>
            <td>
                {{ number_format($list->recup_acc_reason_code) }}
            </td>
            <td>
                {{ number_format($list->recup_start_date) }}
            </td>
            <td>
                {{ number_format($list->recup_end_date) }}
            </td>
            <td>
                {{ number_format($list->recup_acc_ins_price) }}
            </td>
            <td>
                {{ number_format($list->recup_notice_ins_price) }}
            </td>
            <td>
                {{ number_format($list->recup_year_end_tax_ins_price) }}
            </td>
            <td>
                {{ number_format($list->total_cal_ins_price) }}
            </td>
            <td>
                {{ number_format($list->total_acc_ins_price) }}
            </td>
            <td>
                {{ number_format($list->total_notice_ins_price) }}
            </td>
            <td>
                {{ number_format($list->total_year_end_ins_price) }}
            </td>
            <td>
                {{ number_format($list->health_return_price_interest) }}
            </td>
            <td>
                {{ number_format($list->recup_return_price_interest) }}
            </td>
            <td>
                {{ number_format($list->user_total_ins_price) }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
