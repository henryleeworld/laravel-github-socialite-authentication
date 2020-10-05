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
            $user = Socialite::driver('github')->user();
            $finduser = User::where('github_id', $user->id)->first();
            if($finduser) {
                Auth::login($finduser);
                return redirect('/dashboard');
            }else{
                $newUser = User::create([
                    'name'            => $user->name,
                    'email'           => $user->email,
                    'github_id'       => $user->id,
                    'github_nickname' => $user->nickname,
                    'github_avatar'   => $user->avatar,
                    'password'        => encrypt('123456dummy')
                ]);
                Auth::login($newUser);
     
                return redirect('/dashboard');
            }
    
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }
}

