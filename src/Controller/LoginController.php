<?php

namespace App\Controller;

use Pimcore\Model\DataObject;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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

        // Fetch the user from the "User" folder using manual search
        $users = new DataObject\User\Listing();        
        $users->setCondition("email = ?", [$email]);
        $users->setLimit(1);
        foreach ($users as $user) {
            if ($user instanceof User) {
                // Verify the password
                if (password_verify($password, $user->getPassword())) {
                    dd($user);
                    // Password is correct, proceed with the login logic
                    $session = $request->getSession();
                    $session->set('user_logged_in', true);

                    return $this->render('onepager/onepager.html.twig');
                }
            }
        }

        // User not found or incorrect password, handle the error accordingly
        return new Response('Invalid email or password!');
    }

    public function logout(Request $request): Response
    {
        // Clear the user's session
        $session = $request->getSession();
        $session->remove('user_logged_in');

        // Redirect to the login page
        return $this->render('onepager/onepager.html.twig'); 
    }
}
