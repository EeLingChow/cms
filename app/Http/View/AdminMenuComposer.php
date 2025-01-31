<?php

namespace App\Http\View;

use Illuminate\View\View;

use Auth;

class AdminMenuComposer
{

    public function compose(View $view)
    {
        $admin = Auth::guard('admin')->user();
        $view->with('_menu', $admin->getMenuLinks());
    }
}
