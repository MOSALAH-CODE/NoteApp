<?php

use Core\Authenticator;
use Http\Forms\RegisterForm;

$form = RegisterForm::validate($attributes = [
    'email' => $_POST['email'],
    'password' => $_POST['password']
]);

$registered = (new Authenticator)->attemptRegister(
    $attributes['email'], $attributes['password']
);

if (!$registered) {
    $form->error(
        'email', 'Email already used.'
    )->throw();
}


