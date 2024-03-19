<?php



// rewrite용 페이징
function get_paging_rewrite($write_pages, $total_page, $add="")
{

    $url = explode("/", $_SERVER['REQUEST_URI']);
    $cur_page = end($url);

    $str = '';
    if ($cur_page > 1) {
        $str .= "<a href='/{$url[1]}/1' class='pg_page pg_start'>처음</a>".PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;

    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($cur_page > 1) {
        $cur_page_sub = $cur_page-1;
        $str .= "<a href='/{$url[1]}/{$cur_page_sub}{$add}' class='pg_page pg_prev'>이전</a>".PHP_EOL;
    }

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<a href="/'.$url[1].'/'.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            else
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<a href="/'.$url[1].'/'.($end_page+1).$add.'" class="pg_page pg_next">다음</a>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<a href="'.$url[1].'/'.$total_page.$add.'" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
    }

    if ($str)
        return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
    else
        return "";
}
