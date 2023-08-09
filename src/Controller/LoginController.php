<?php

namespace App\Controller;

use Pimcore\Model\User;
use Pimcore\Controller\FrontendController;
use Pimcore\Db;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController extends FrontendController
{   
    /**
     * @param Request $request
     * @return Response
     */
    
    public function defaultAction(Request $request): Response
    {
        return $this->render('main/main.html.twig');
    }
    public function login(Request $request): Response
{
    // Retrieve the form data
    $email = $request->request->get('email');
    $password = $request->request->get('password');

    // Fetch the user by email using a custom SQL query
    $query = "
        SELECT * FROM users
        WHERE email = :email
    ";

    $params = [
        'email' => $email,
    ];

    $user = Db::get()->fetchRow($query, $params);

    // Check if a user with the given email exists
    if (!$user) {
        // User not found, handle the error accordingly
        return new Response('Invalid email or password!');
    }
    // Verify the password
    if (!password_verify($password, $user['password'])) {
        // Incorrect password, handle the error accordingly
        return new Response('Invalid email or password!');
    }

    // Password is correct, proceed with the login logic

    // Redirect the user to a protected page or perform any other actions

    return new Response('Login successful!');
}

}
