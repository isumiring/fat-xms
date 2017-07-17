<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;


class DashboardController extends Controller
{
    /**
     * Set globally for this controller.
     * 
     * @var array
     */
    protected $parse = [];

    /**
     * Prefix routing.
     * 
     * @var string
     */
    protected $prefix_routes;

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {
        $this->prefix_routes = config('constant.location.backend_path');

        $this->parse['head_title']  = 'Dashboard';
    }

    /**
     * Index page.
     * 
     * @return layout
     */
    public function index()
    {
        return view($this->prefix_routes. '.dashboard', $this->parse);
    }
}
