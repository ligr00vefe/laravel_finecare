<table>
    <thead class="thead-origin">
    <tr class="table-top">
        <th>
            No
        </th>
        <th>
            대상년월
        </th>
        <th>
            대상자
        </th>
        <th>
            대상자 생년월일
        </th>
        <th>
            제공인력
        </th>
        <th>
            제공인력 생년월일
        </th>
        <th>
            서비스 시작시간
        </th>
        <th>
            서비스 종료시간
        </th>
        <th>
            결제시간
        </th>
        <th>
            총 결제 금액
        </th>
        <th>
            가산금액
        </th>
        <th>
            지원지자체 구분
        </th>
        <th>
            지원기관
        </th>
        <th>
            비고
        </th>
        <th>
            등록일
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($lists as $list)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $list->target_ym }}
            </td>
            <td>
                {{ $list->target_name }}
            </td>
            <td>
                {{ $list->target_birth }}
            </td>
            <td>
                {{ $list->provider_name }}
            </td>
            <td>
                {{ $list->provider_birth }}
            </td>
            <td>
                {{ $list->service_start_date_time }}
            </td>
            <td>
                {{ $list->service_end_date_time }}
            </td>
            <td>
                {{ $list->payment_time }}
            </td>
            <td>
                {{ $list->confirm_pay }}
            </td>
            <td>
                {{ $list->add_price }}
            </td>
            <td>
                {{ $list->local_government_name }}
            </td>
            <td>
                {{ $list->organization }}
            </td>
            <td>
                {{ $list->etc }}
            </td>
            <td>
                {{ $list->created_at }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

