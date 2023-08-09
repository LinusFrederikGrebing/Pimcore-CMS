<?php

namespace App\Controller;

use Pimcore\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterController
{
    public function register(Request $request): Response
    {
        // Retrieve the form data
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirm_password');

        // Validate the form data, perform any necessary checks

        if ($password !== $confirmPassword) {
            return new Response('Passwords do not match!');
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create a new user
        $user = new User();
        $user->setUsername($name);
        $user->setEmail($email);
        $user->setPassword($hashedPassword); // Set the hashed password
        $user->save();

        // Optionally, perform additional actions such as sending a confirmation email, etc.

        // Redirect the user to a success page
        return new Response('User registered successfully!');
    }
}

