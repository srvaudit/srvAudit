<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InstallController extends Controller
{
    /**
     * Serve the Ubuntu install script.
     *
     * @return Response
     */
    public function install()
    {
        return view('install');
    }

    /**
     * Serve the CentOS install script.
     *
     * @return Response
     */
    public function installcentos()
    {
        return view('installcentos');
    }

    /**
     * Serve the Docker install script.
     *
     * @return Response
     */
    public function dockerinstall()
    {
        return view('dockerinstall');
    }
}
