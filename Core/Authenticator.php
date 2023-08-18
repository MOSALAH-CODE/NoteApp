<?php

namespace Core;

class Authenticator
{
    public function attemptLogin($email, $password)
    {
        $user = App::resolve(Database::class)
            ->query('select * from users where email = :email', [
                'email' => $email
            ])->find();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $this->login([
                    'email' => $email
                ]);

                return true;
            }
        }

        return false;
    }

    public static function login($user)
    {
        $_SESSION['user'] = [
            'email' => $user['email']
        ];

        session_regenerate_id(true);
    }

    public function attemptRegister($email, $password)
    {
        $user = App::resolve(Database::class)
            ->query('select * from users where email = :email', [
                'email' => $email
            ])->find();

        if ($user) {
            return false;
        }

        App::resolve(Database::class)
            ->query('INSERT INTO users(email, password) VALUES(:email, :password)', [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT)
            ]);

        $this->login([
            'email' => $email
        ]);

        return true;
    }

    public static function logout()
    {
        Session::destroy();
    }
}