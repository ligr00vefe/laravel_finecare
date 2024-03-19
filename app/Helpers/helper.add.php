<?php


function getAverageData($first, $second)
{
    return @intval($first / $second) ? @round($first / $second, 1) : 0;
}
