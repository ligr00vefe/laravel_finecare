@php
$sub_menus = $config['menu'][2]['sub_menu'];
$access_menus_arr = explode("?", $_SERVER['REQUEST_URI']);
$access_menus = $access_menus_arr[0];
@endphp

<aside>

    <section class="sub-menu__list">

        <div class="list-info">
            <h3>활동지원사</h3>
        </div>

        <ul>
            @foreach($sub_menus as $key => $sub)
                <li class="depth1_menu" data-uri="{{ $sub['uri'] }}">
                    <a href="{{ $sub['uri'] ?? "" }}">{{ $sub['name'] ?? "" }}</a>
                    <ul class="ul_2depth">
                        @if (isset($sub['sub']))
                            @foreach ($sub['sub'] as $depth2)

                                <li class="" data-uri="{{ $depth2['uri'] }}">
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


</script>
