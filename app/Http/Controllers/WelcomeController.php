<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Show the application splash screen.
     *
     * @return Response
     */
    public function show()
    {
        return view('welcome');
    }

    /**
     * Show the docs page.
     *
     * @return Response
     */
    public function docs()
    {
        return view('docs');
    }

    /**
     * Show the blog page.
     *
     * @return Response
     */
    public function blog()
    {
        return view('blog');
    }

    /**
     * Show the support page.
     *
     * @return Response
     */
    public function support()
    {
        return view('support');
    }

    /**
     * Show the support page.
     *
     * @return Response
     */
    public function letsencrypt()
    {
        echo "OMPNteObEaEfolTurkKZVo2SOKf7g8yVNfA34FW5Uj0.F7rRsdhYcyvx6JtdJy7HTmeJ2yEkrlXw_SeEUNfp2sc";
    }

    /**
     * Show the detectify verification page.
     *
     * @return Response
     */
    public function detectify()
    {
        echo "detectify";
    }

    /**
     * Show our privacy policy
     *
     * @return bool
     */
    protected function privacy()
    {
        return view('privacy');
    }
}
