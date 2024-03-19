<ul>
    @if (isset($pagination))
        @for ($i=1; $i<=$pagination; $i++)
            <li>
                <a href="#" data-page="{{ $i }}" class="{{ $page == $i ? "on" : "" }}">
                    {{ $i }}
                </a>
            </li>
        @endfor
    @endif
</ul>
