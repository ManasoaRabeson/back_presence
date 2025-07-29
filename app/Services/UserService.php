<?php
namespace App\Services;

use App\Interfaces\UserInterface;
use App\Models\User;

class UserService implements UserInterface{
    public function store($matricule = null, $name, $firstName = null, $email, $phone = null, $password): mixed
    {
        $user = new User();
        $user->matricule = $matricule;
        $user->name = $name;
        $user->firstName = $firstName;
        $user->email = $email;
        $user->phone = $phone;
        $user->password = $password;
        $user->save();

        return $user;
    }
}