<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create(){
        return view('auth.register');
    }

    public function store(){
        //validation
        $validatedAttributes = request()->validate([
            'first_name'=>['required'],
            'last_name'=>['required'],
            'email'=>['required','email'],
            'phone'=>['required'],
            'password'=>['required', Password::min(6), 'confirmed'],// avec sa laral controle une autre entré portant le nom password_confirm
        ]);
        //création de l'utilisateur
        $user = User::create($validatedAttributes);

        //log in
        Auth::login($user);

        //redirecte
        return redirect('/jobs');

    }
}
