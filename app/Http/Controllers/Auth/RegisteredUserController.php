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
            Notification::route('mail', $athlete->email)->notify(new UserInvited());
            Utility::flashMessage('success', "Invito inviato a {$athlete->email}");
        }catch( \Exception $e){
            Utility::flashMessage('error', 'Qualcosa è andato storto');
        }
        
        return redirect(route('athletes.index'));
     }
    
    
     public function create(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users', 'exists:athletes'],
        ]);

        $athlete = Athlete::where('email', $request->get('email'))->firstOrFail();

        $first_name = $athlete->name;
        $last_name = $athlete->surname;
        $email = $athlete->email;

        return view('auth.register', compact('first_name', 'last_name', 'email'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:191'],
            'last_name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name.' '.$request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // username
        $username = intval(config('app.initial_username')) + $user->id;
        $user->username = strval($username);
        $user->save();

        $athlete = Athlete::where('email', $request->email)->first()->user()->associate($user);
        $athlete->save();

        event(new Registered($user));
        event(new UserRegistered($user));

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        app()['cache']->forget('spatie.permission.cache');

        $user->assignRole(Roles::Athlete);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
