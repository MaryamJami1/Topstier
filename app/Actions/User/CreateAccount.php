<?php

namespace App\Actions\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAccount {
    public function handel($data)
    {
        $user = new User([
            'name' =>$data['name'],
            'email' =>$data['email'],
            'phone' =>$data['phone_number'],
            'password' => Hash::make($data['password']),
            'verification_code' => rand(100000, 999999)
        ]);
        $user->save();

        return $user;
    }
}