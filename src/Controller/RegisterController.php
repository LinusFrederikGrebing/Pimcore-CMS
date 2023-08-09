<?php

namespace App\Controller;

use Pimcore\Model\DataObject\User; // Import the User class from DataObject
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimcore\Model\Asset;

class RegisterController
{
    public function register(Request $request): Response
    {
        // Retrieve the form data
        $username = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirm_password');
        
        // Validate the form data, perform any necessary checks
        if ($password !== $confirmPassword) {
            return new Response('Passwords do not match!');
        }
        
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Create a new User object (Pimcore DataObject)
        $user = new User();
        $user->setKey($username); // Set a unique key, typically username or email
        $user->setParentId(7); // Set the parent folder's ID where users are stored
        $user->setPublished(true); // Mark the user as published
        
        // Set user properties using setter methods
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($hashedPassword); // Set the hashed password

        $image = Asset\Image::getByPath("/Team/lisa_ranold.png");
        $user->setImage($image);
        // Save the user object
        $user->save();
        
        // Redirect the user to a success page
        return new Response('User registered successfully!');
    }
    
}
