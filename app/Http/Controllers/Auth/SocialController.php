<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Social\JiraServiceProvider;
use App\Http\Controllers\Controller;
use App\Social\GithubServiceProvider;
use App\Social\GoogleServiceProvider;
use App\Social\TwitterServiceProvider;
use App\Social\FacebookServiceProvider;
use GuzzleHttp\Exception\ClientException;
use Laravel\Socialite\Two\InvalidStateException;
use League\OAuth1\Client\Credentials\CredentialsException;

class SocialController extends Controller
{
    protected $providers = [
        'github' => GithubServiceProvider::class,
        'google' => GoogleServiceProvider::class,
        'jira' => JiraServiceProvider::class,
        'twitter' => TwitterServiceProvider::class,
        'facebook' => FacebookServiceProvider::class,
    ];

    /**
     *  Create a new controller instance
     * 
     * @return  void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     *  Redirect the user to provider authentication page
     * 
     *  @param  string $driver
     *  @return \Illuminate\Http\Response
     */
    public function redirectToProvider($driver)
    {
        return (new $this->providers[$driver])->redirect();        
    }

    /**
     *  Handle provider response
     * 
     *  @param  string $driver
     *  @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($driver)
    {   
        try {
            return (new $this->providers[$driver])->handle();
        } catch (InvalidStateException $e) {
            dd($e);
            return $this->redirectToProvider($driver);
        } catch (ClientException $e) {
            dd($e);
            return $this->redirectToProvider($driver);
        } catch (CredentialsException $e) {
            dd($e);
            return $this->redirectToProvider($driver);
        }
    }
}
