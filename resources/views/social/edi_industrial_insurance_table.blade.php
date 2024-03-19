<table class="member-list b-last-bottom">
    <thead>
    <tr>
        <th>
            No
        </th>
        <th>
            근로자구분
        </th>
        <th>
            근로자명
        </th>
        <th>
            생년월일
        </th>
        <th>
            고용일
        </th>
        <th>
            고용 종료일
        </th>
        <th>
            보험료
        </th>
    </tr>
    </thead>
    <tbody>
    @foreach ($lists as $list)
        <tr>
            <td>
                {{ (($page - 1) * 15) + $loop->iteration }}
            </td>
            <td>
                {{ $list->worker_type }}
            </td>
            <td>
                {{ $list->name }}
            </td>
            <td>
                {{ $list->birth }}
            </td>
            <td>
                {{ $list->employ_start_date }}
            </td>
            <td>
                {{ $list->employ_end_date }}
            </td>
            <td>
                {{ $list->acc_ins_price }}
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
