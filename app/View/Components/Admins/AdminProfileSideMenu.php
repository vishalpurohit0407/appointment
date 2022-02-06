<?php

namespace App\View\Components\Admins;

use Illuminate\View\Component;

class AdminProfileSideMenu extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admins.admin-profile-side-menu');
    }
}
