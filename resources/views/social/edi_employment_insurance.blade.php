<table class="member-list b-last-bottom">
    <thead>
    <tr>
        <th>
            No
        </th>
        <th>
            성명
        </th>
        <th>
           생년월일
        </th>
        <th>
            근로자 실업급여 보험료료
        </th>
        <th>
            사업주 실업급여 보험료
        </th>
        <th>
            사업주 고안직능 보험료
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach($lists as $list)
        <tr>
            <td>
                {{ $loop->iteration }}
            </td>
            <td>
                {{ $list->name }}
            </td>
            <td>
                {{ $list->birth }}
            </td>
            <td>
                {{ $list->total_worker_unemploy_benefit }}
            </td>
            <td>
                {{ $list->total_owner_unemploy_benefit }}
            </td>
            <td>
                {{ $list->total_owner_goan_ins_price }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
