<?php

namespace App\View\Components;

use Illuminate\View\Component;




class ServiceCalendar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $type = "";

    public $year; // 이번년
    public $month; // 이번달
    public $week_day; // 1일의 요일 숫자. 일~토 = 0~6
    public $endDay; // 이번달의 마지막 일
    public $dayWeek; // 1일의 요일

    public $lastMonthEndDay; // 저번달의 마지막 일.

    public function __construct($type)
    {
        $week_list = [ "일", "월", "화", "수", "목", "금", "토" ];

        $this->type = $type;

        $getYM = $_GET['from_date'] ?? date("Y-m");

        $day =  date("Y-m-d", strtotime(date("Y-m", strtotime($getYM))."-01"));
        $this->year = date("Y", strtotime($day));
        $this->month = date("m", strtotime($day));
        $this->week_day = date("w", strtotime($day));
        $this->dayWeek = $week_list[$this->week_day];
        $this->endDay = date("t", strtotime($day));
        $this->lastMonthEndDay = date("t", strtotime($day. "-1 day"));
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.service-calendar');
    }
}
