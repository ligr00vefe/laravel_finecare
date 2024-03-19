<table>
    <thead class="thead-origin">
    <tr>
    </tr>
    <tr class="table-top">
        <th>구분</th>
        <th>성명</th>
        <th>주민(외국인)등록번호</th>
        <th>근로월</th>
        @foreach (range(1, 31) as $i)
            <th>{{ $i }}</th>
        @endforeach
        <th>총근로일수</th>
    </tr>
    <tr>
        <th colspan="4">합계</th>
    </tr>
    </thead>
    <tbody>
    @forelse($lists as $key => $list)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $list['User']['name'] ?? "정보없음" }}
            </td>
            <td>
                {{ $list['User']['target_id'] ?? $key }}
            </td>
            <td>
                {!! isset($list['User']['target_id']) ? "" : "<span style=color:red>미등록</span>"  !!}
            </td>
            <td>
                {{ $list['User']['join_date'] ?? "정보없음" }}
            </td>
            <td>
                {{ $list['User']['resign_date'] ?? "정보없음" }}
            </td>
            <td>
                {{ $list['User']['national_pension'] ?? "미가입" }}
            </td>
            <td>
                {{ $list['User']['health_insurance'] ?? "미가입" }}
            </td>
            <td>
                {{ $list['User']['employment_insurance'] ?? "미가입" }}
            </td>
            <td>
                {{ $list['User']['retirement_pay_contract'] ?? "미가입" }}
            </td>
            <td>
                {{ $list['User']['year_rest_count'] ?? "정보없음" }}
            </td>
            {{-- 국가비 --}}

            <td>
                {{-- 국비 근무일수 --}}
                {{ number_format(count($list['Voucher']['COUNTRY']['DAY'])) }}
            </td>
            <td>
                {{-- 국비 승인금액 --}}
                {{ number_format($list['Voucher']['COUNTRY']['PAYMENT_TOTAL']) }}
            </td>
            <td>
                {{-- 국비 가산금 --}}
                {{ number_format($list['Voucher']['COUNTRY']['PAYMENT_EXTRA']) }}
            </td>
            <td>
                {{-- 국비 총 시간 --}}
                {{ number_format($list['Voucher']['COUNTRY']['TIME_TOTAL']) }}
            </td>
            <td>
                {{-- 국비 휴일시간 --}}
                {{ number_format($list['Voucher']['COUNTRY']['TIME_HOLIDAY']) }}
            </td>
            <td>
                <!-- 국비 야간시간 -->
                {{ number_format($list['Voucher']['COUNTRY']['TIME_NIGHT']) }}
            </td>

            {{-- 시도비 --}}
            <td>
                {{ number_format(count($list['Voucher']['CITY']['DAY'])) }}
            </td>
            <td>
                {{-- 시도비 승인금액 --}}
                {{ number_format($list['Voucher']['CITY']['PAYMENT_TOTAL']) }}
            </td>
            <td>
                {{-- 시도비 승인금액 --}}
                {{ number_format($list['Voucher']['CITY']['PAYMENT_EXTRA']) }}
            </td>
            <td>
                {{-- 시도비 총 시간 --}}
                {{ number_format($list['Voucher']['CITY']['TIME_TOTAL']) }}
            </td>
            <td>
                {{-- 시도비 휴일시간 --}}
                {{ number_format($list['Voucher']['CITY']['TIME_HOLIDAY']) }}
            </td>
            <td>
                <!-- 시도비 야간시간 -->
                {{ number_format($list['Voucher']['CITY']['TIME_NIGHT']) }}
            </td>

            {{-- 승인합계 --}}
            <td>
                <!-- 근무일수 -->
                {{
                count($list['Voucher']['COUNTRY']['DAY'])
                + count($list['Voucher']['CITY']['DAY'])
                }}
            </td>
            <td>
                <!-- 승인금액 -->
                {{
                number_format($list['Voucher']['COUNTRY']['PAYMENT_TOTAL']
                + $list['Voucher']['CITY']['PAYMENT_TOTAL'])
                }}
            </td>
            <td>
                <!-- 가산금 -->
                {{
                number_format($list['Voucher']['COUNTRY']['PAYMENT_EXTRA']
               + $list['Voucher']['CITY']['PAYMENT_EXTRA'])
               }}
            </td>
            <td>
                <!-- 총시간 -->
                {{
                number_format($list['Voucher']['COUNTRY']['TIME_TOTAL']
                + $list['Voucher']['CITY']['TIME_TOTAL'])
                }}
            </td>
            <td>
                <!-- 휴일시간 -->
                {{
                number_format($list['Voucher']['COUNTRY']['TIME_HOLIDAY']
                + $list['Voucher']['CITY']['TIME_HOLIDAY'])
                }}
            </td>
            <td>
                <!-- 야간시간 -->
                {{
                number_format($list['Voucher']['COUNTRY']['TIME_NIGHT']
                + $list['Voucher']['CITY']['TIME_NIGHT'])
                }}
            </td>

            {{-- 승인금액 분리 --}}
            <td>
                <!-- 기본급 -->
                {{
                number_format($list['Voucher']['COUNTRY']['PAYMENT_NORMAL']
                + $list['Voucher']['CITY']['PAYMENT_NORMAL'])
                }}
            </td>
            <td>
                <!-- 휴일수당 -->
                {{
                number_format($list['Voucher']['COUNTRY']['PAYMENT_HOLIDAY']
                + $list['Voucher']['CITY']['PAYMENT_HOLIDAY'])
                }}
            </td>
            <td>
                <!-- 야근수당 -->
                {{
                number_format($list['Voucher']['COUNTRY']['PAYMENT_NIGHT']
                + $list['Voucher']['CITY']['PAYMENT_NIGHT'])
                }}
            </td>
            <td>
                <!-- 승인금액차 -->
                {{
                number_format(
                ($list['Voucher']['COUNTRY']['PAYMENT_TOTAL'] + $list['Voucher']['CITY']['PAYMENT_TOTAL'])
                - ($list['Voucher']['COUNTRY']['PAYMENT_NORMAL']
                + $list['Voucher']['CITY']['PAYMENT_NORMAL']
                + $list['Voucher']['COUNTRY']['PAYMENT_HOLIDAY']
                + $list['Voucher']['CITY']['PAYMENT_HOLIDAY']
                + $list['Voucher']['COUNTRY']['PAYMENT_NIGHT']
                + $list['Voucher']['CITY']['PAYMENT_NIGHT']))
                }}
            </td>

            {{-- 기타청구 내역 --}}
            <td>
                <!-- 시/군/구비 시간(수기입력) -->
                {{ number_format($list['etc_charge_time']) }}
            </td>
            <td>
                <!-- 시/군/구비 금액(수기입력) -->
                {{ number_format($list['etc_charge_pay']) }}
            </td>
            <td>
                <!-- 예외청구 -->
                {{ number_format($list['except_charge_time']) }}
            </td>
            <td>
                <!-- 예외청구 -->
                {{ number_format($list['except_charge_pay']) }}
            </td>
            <td>
                <!-- 기타청구내역합계시간 -->
                {{ number_format($list['etc_charge_time'] + $list['except_charge_time']) }}
            </td>
            <td>
                <!-- 기타청구내역합계금액 -->
                {{ number_format($list['etc_charge_pay'] + $list['except_charge_pay']) }}
            </td>

            {{-- 반납승인내역 --}}
            <td>
                <!-- 반납승인내역 국비반납 근무일수 -->
                {{ number_format(count($list['Return']['COUNTRY']['DAYS'])) }}
            </td>
            <td>
                <!-- 반납승인내역 국비반납 승인금액 -->
                {{ number_format($list['Return']['COUNTRY']['PAYMENT_TOTAL']) }}
            </td>
            <td>
                <!-- 반납승인내역 총 시간 -->
                {{ number_format($list['Return']['COUNTRY']['TIME_TOTAL']) }}
            </td>
            <td>
                <!-- 반납승인내역 도비반납 근무일수 -->
                {{ number_format(count($list['Return']['CITY']['DAYS'])) }}
            </td>
            <td>
                <!-- 반납승인내역 도비반납 승인금액 -->
                {{ number_format($list['Return']['CITY']['PAYMENT_TOTAL']) }}
            </td>
            <td>
                <!-- 반납승인내역 총 시간 -->
                {{ number_format($list['Return']['CITY']['TIME_TOTAL']) }}
            </td>
            <td>
                <!-- 반납합계 근무일수 -->
                {{
                    number_format(count($list['Return']['COUNTRY']['DAYS']) + count($list['Return']['CITY']['DAYS']))
                }}
            </td>
            <td>
                <!-- 반납합계 승인금액 -->
                {{
                    number_format($list['Return']['COUNTRY']['PAYMENT_TOTAL'] + $list['Return']['CITY']['PAYMENT_TOTAL'])
                }}
            </td>
            <td>
                <!-- 반납합계 승인시간 -->
                {{
                    number_format($list['Return']['COUNTRY']['TIME_TOTAL'] + $list['Return']['CITY']['TIME_TOTAL'])
                }}
            </td>


            {{-- 제공자 법정 지급항목 시뮬레이션(근로기준법) --}}
            <td>
                <!-- 바우처상 지급합계(기본지급, 바우처상 계산 총합이다) -->
                {{ number_format($list['Payment']) }}
            </td>
            <td>
                <!-- 기본급 시간 -->
                {{ number_format($list['Standard']['TIME_BASIC']) }}
            </td>
            <td>
                <!-- 기본급 금액 -->
                {{ number_format($list['Standard']['PAY_BASIC']) }}
            </td>
            <td>
                <!-- 연장수당 시간 -->
                {{ number_format($list['Standard']['TIME_OVERTIME']) }}
            </td>
            <td>
                <!-- 연장수당 금액 -->
                {{ number_format($list['Standard']['PAY_OVERTIME']) }}
            </td>
            <td>
                <!-- 휴일수당 시간 -->
                {{ number_format($list['Standard']['TIME_HOLIDAY']) }}
            </td>
            <td>
                <!-- 휴일수당 금액 -->
                {{ number_format($list['Standard']['PAY_HOLIDAY']) }}
            </td>
            <td>
                <!-- 야근수당 시간 -->
                {{ number_format($list['Standard']['TIME_NIGHT']) }}
            </td>
            <td>
                <!-- 야근수당 금액 -->
                {{ number_format($list['Standard']['PAY_NIGHT']) }}
            </td>
            <td>
                <!-- 주휴수당 시간 -->
                {{ number_format($list['Standard']['ALLOWANCE_WEEK_TIME']) }}
            </td>
            <td>
                <!-- 주휴수당 금액 -->
                {{ number_format($list['Standard']['ALLOWANCE_WEEK_PAY']) }}
            </td>
            <td>
                <!-- 연차수당 시간 -->
                {{ number_format($list['Standard']['ALLOWANCE_YEAR_TIME']) }}
            </td>
            <td>
                <!-- 연차수당 금액 -->
                {{ number_format($list['Standard']['ALLOWANCE_YEAR_PAY'] + $list['Standard']['ALLOWANCE_YEAR_PAY_LESS_THAN_1_YEAR']) }}
            </td>
            <td>
                <!-- 근로자의날 시간 -->
                {{ number_format($list['Standard']['WORKERS_DAY_TIME']) }}
            </td>
            <td>
                <!-- 근로자의날 금액 -->
                {{ number_format($list['Standard']['WORKERS_DAY_PAY']) }}
            </td>
            <td>
                <!-- 공휴일 유급휴일 시간 -->
                {{ number_format($list['Standard']['PUBLIC_HOLIDAY_TIME']) }}
            </td>
            <td>
                <!-- 연차수당 금액 -->
                {{ number_format($list['Standard']['PUBLIC_HOLIDAY_PAY']) }}
            </td>
            <td>
                <!-- 적용합계(근로기준법합계) -->
                {{ number_format($list['Standard']['PAY_TOTAL']) }}
            </td>
            <td>
                <!-- 차액(법정제수당) -->
                {{ number_format($list['Payment'] - $list['Standard']['PAY_TOTAL']) }}
            </td>
            <td>
                <!-- 보전수당 수기입력-->
                {{ number_format($list['bojeon']) }}
            </td>
            <td>
                <!-- 자부담급여 수기입력-->
                {{ number_format($list['jaboodam']) }}
            </td>
            <td>
                <!-- 10월법정제수당 수기입력-->
                {{ number_format($list['jaesoodang']) }}
            </td>
            <td>
                <!-- 반납추가(빈칸) 수기입력-->
                {{ number_format($list['bannap']) }}
            </td>
            <td>
                {{-- 지급총액 --}}
                <span class="standard_pay_total">{{ number_format($list['Payment']) }}</span>
            </td>

            {{-- 제공자 급여공제내역 --}}
            <td>
                {{-- 국민연금 --}}
                <span class="worker_tax">{{ number_format($list['Tax']['WORKER_NATIONAL']) }}</span>
            </td>
            <td>
                {{-- 건강보험 --}}
                <span class="worker_tax">{{ number_format($list['Tax']['WORKER_HEALTH']) }}</span>
            </td>

            <td>
                {{-- 고용보험 --}}
                <span class="worker_tax">{{ number_format($list['Tax']['WORKER_EMPLOY']) }}</span>
            </td>

            <td>
                {{-- 갑근세 --}}
                <span class="worker_tax">{{ number_format($list['Tax']['CLASS_A_WAGE']) }}</span>
            </td>

            <td>
                {{-- 주민세 --}}
                <span class="worker_tax">{{ number_format($list['Tax']['RESIDENT_TAX']) }}</span>
            </td>
            <td>
                {{-- 건보정산 --}}
                {{ number_format($list['gunbo_tax']) }}
            </td>
            <td>
                {{-- 연말정산 --}}
                {{ number_format($list['year_total_tax']) }}
            </td>
            <td>
                {{-- 부정수급환수 --}}
                {{ number_format($list['bad_income_get']) }}
            </td>
            <td>
                {{-- 기타공제1 --}}
                {{ number_format($list['etc_tax_1']) }}
            </td>
            <td>
                {{-- 기타공제2 --}}
                {{ number_format($list['etc_tax_2']) }}
            </td>
            <td>
                {{-- 공제합계 --}}
                <span class="worker_tax_total">{{ number_format($list['WorkerTaxTotal']) }}</span>
            </td>

            {{-- 급여지급 및 이체현황 --}}
            <td>
                {{-- 차인지급액(세후) --}}
                <span class="tax_sub">{{ number_format($list['Payment'] - $list['WorkerTaxTotal']) }}</span>
            </td>
            <td>
                <!-- 은행명 -->
                {{ $list['User']->bank_name ?? "정보없음" }}
            </td>
            <td>
                <!-- 계좌번호 -->
                {{ $list['User']->bank_account_number ?? "정보없음" }}
            </td>

            {{-- 사업주(기관) 세금공제 --}}
            <td>
                {{-- 사업수입 (바우처합계+기타청구)-근로기준법합계 --}}
                <span class="CompanyBusinessTotal">{{ number_format($list['CompanyBusinessTotal']) }}</span>
            </td>
            <td>
                <!-- 국민연금기관 -->
                {{ number_format($list['Tax']['COMPANY_NATIONAL']) }}
            </td>
            <td>
                <!-- 건강보험기관 -->
                {{ number_format($list['Tax']['COMPANY_HEALTH']) }}
            </td>
            <td>
                <!-- 고용보험기관 -->
                {{ number_format(round($list['Tax']['COMPANY_EMPLOY'])) }}
            </td>
            <td>
                <!-- 산재보험기관 -->
                {{ number_format($list['Tax']['COMPANY_INDUSTRY']) }}
            </td>
            <td>
                <!-- 퇴직적립금 -->
                {{ number_format($list['Retirement']) }}
            </td>
            <td>
                <!-- 반납승인(사업주) -->
                {{ number_format(($list['Return']['COUNTRY']['PAYMENT_TOTAL'] + $list['Return']['CITY']['PAYMENT_TOTAL']) * 0.236) }}
            </td>
            <td>
                <!-- 사업주부담합계 -->
                {{ number_format($list['CompanyTaxTotal']) }}
            </td>
            <td>
                <!-- 차감 사업주 수익(사업수입-사업주공제) -->
                {{ number_format($list['CompanyBusinessTotal'] - $list['CompanyTaxTotal']) }}
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="91"></td>
        </tr>
    @endforelse
    </tbody>
</table>

