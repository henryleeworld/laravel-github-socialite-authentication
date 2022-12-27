<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Socialite;
  
class GithubController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }
      
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            $user = User::updateOrCreate([
                'github_id'            => $githubUser->id,
            ], [
                'name'                 => $githubUser->name,
                'email'                => $githubUser->email,
                'password'             => encrypt('123456dummy'),
                'github_nickname'      => $githubUser->nickname,
                'github_avatar'        => $githubUser->avatar,
                'github_token'         => $githubUser->token,
                'github_refresh_token' => $githubUser->refreshToken,
            ]);
            Auth::login($user);
            return redirect('/dashboard');
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

