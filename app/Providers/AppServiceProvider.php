<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(isset($_SERVER['REQUEST_URI'])) {
            $this->urlfrag = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $this->urlfragm = substr(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH),0,7);
        } else {
            $this->urlfrag = '/';
            $this->urlfragm = '/';
        }
        view()->share('urlfrag', $this->urlfrag);
        view()->share('urlfragm', $this->urlfragm);
        view()->share('footicon', $this->footicon());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    public function footicon()
    {
        $icons = array(
            'key',
            'rocket',
            'magic',
            'shield',
            'heart',
            'hand-peace-o',
            'hand-spock-o',
            'lightbulb-o',
            'globe',
            'plane',
            'paper-plane',
            'pencil',
            'glass',
            'flash',
            'cutlery',
            'cloud',
            'bug',
            'terminal',
            'stack-overflow',
            'slack',
            'reddit-alien',
            'linux',
            'gitlab',
            'hacker-news',
            'firefox',
            'dollar',
            'linode',
            'microchip',
            'grav',
            'cc-visa',
            'bank',
            'car',
            'google',
            'html5',
            'apple',
            'pied-piper',
            'trello',
            'cc-stripe',
            'btc',
            'gamepad',
            'desktop',
            'laptop',
            'mobile',
            'spinner fa-pulse',
            'beer fa-rotate-180'
        );
        $icon = $icons[mt_rand(0, count($icons) - 1)];
        return $icon;
    }
}
