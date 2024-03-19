@php

    $list = [
        [
            "id" => 1,
            "name" => "홍길동",
            "birth" => "2020-10-27",
            "gender" => "남자",
            "tel" => "010-1111-2222",
            "addr" => "창원시 중동 의창대로 282번길 33 둥지빌 203호",
            "status"=> "이용중",
            "regdate" => "2020-10-10",
            "from_date" => "2020-10-10",
            "matching" => 3
        ]
    ];
@endphp

<table class="table-1">

    <colgroup>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
        <col>
    </colgroup>
    <thead>
    <tr>
        <th>No</th>
        <th>이름</th>
        <th>생년월일</th>
        <th>성별</th>
        <th>전화번호</th>
        <th>주소</th>
        <th>상태</th>
        <th>접수일</th>
        <th>계약시작일</th>
        <th>매칭현황</th>
    </tr>
    </thead>
    <tbody>
    @for($i=0; $i<15; $i++)
    <tr>
        <td>
            {{ $i  }}
        </td>
        <td>
            {{ $list[0]['name']  }}
        </td>
        <td>
            {{ $list[0]['birth']  }}
        </td>
        <td>
            {{ $list[0]['gender']  }}
        </td>
        <td>
            {{ $list[0]['tel']  }}
        </td>
        <td>
            {{ $list[0]['addr']  }}
        </td>
        <td>
            {{ $list[0]['status']  }}
        </td>
        <td>
            {{ $list[0]['regdate']  }}
        </td>
        <td>
            {{ $list[0]['from_date']  }}
        </td>
        <td>
            {{ $list[0]['matching']  }}
        </td>
    </tr>
    @endfor
    </tbody>

</table>