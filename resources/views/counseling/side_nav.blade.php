@php
$sub_menus = $config['menu'][5]['sub_menu'];
$access_menus_arr = explode("?", $_SERVER['REQUEST_URI']);
$access_menus = $access_menus_arr[0];
@endphp

<aside>

    <section class="sub-menu__list">

        <div class="list-info">
            <h3>부가기능</h3>
        </div>

        <ul>
            @foreach($sub_menus as $key => $sub)
                <li class="depth1_menu">
                    <a href="{{ $sub['uri'] ?? "" }}">{{ $sub['name'] ?? "" }}</a>
                    <ul class="ul_2depth">
                        @if (isset($sub['sub']))
                            @foreach ($sub['sub'] as $depth2)
                                @php
                                    $on = "";
                                    $on = strpos($depth2['uri'] ?? "", $access_menus) !== false ? "on" : "";
                                    if (isSet($depth2['group_uri'])) {
                                        if (in_array($access_menus, $depth2['group_uri'])) {
                                            $on = "on";
                                        }
                                    }
                                @endphp
                                <li class="{{ $on }}">
                                    <a href="{{ $depth2['uri'] }}">- {{ $depth2['name'] }}</a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endforeach
        </ul>

    </section>

</aside>

<script>

    var pathname = window.location.pathname;
    var memberlist = ["/member/add"];

    if(pathname.indexOf(memberlist) != -1) {
        $(".sub-menu__list ul li:first-child").addClass("on");
    }

</script>
