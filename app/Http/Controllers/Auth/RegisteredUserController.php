<?php

namespace App\Http\Controllers\Auth;

use App\Classes\Utility;
use App\Enums\Roles;
use App\Events\Frontend\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Notifications\UserInvited;
use Illuminate\Support\Facades\Notification;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    
     public function invite(Athlete $athlete)
     {
        try{
            Notification::route('mail', $athlete->email)->notify(new UserInvited($athlete));
            Utility::flashMessage('success', "Invito inviato a {$athlete->email}");
        }catch( \Exception $e){
            Utility::flashMessage('error', 'Qualcosa è andato storto');
        }
        
        return redirect(route('athletes.index'));
     }
    
    
     public function create(Request $request, Athlete $athlete)
    {
        if($athlete->user){
            return redirect()->route('login');
        }

        return view('auth.register', compact('athlete'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Athlete $athlete)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $athlete->user()->associate($user);
        $athlete->save();

        app()['cache']->forget('spatie.permission.cache');

        $user->assignRole(Roles::User);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
