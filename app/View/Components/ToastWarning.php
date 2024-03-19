<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ToastWarning extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $msg="";
    public $type = "";

    public function __construct($msg, $type="warning")
    {
        $this->msg = $msg;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.toast-warning');
    }
}
