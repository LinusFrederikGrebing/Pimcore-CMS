<?php

namespace App\Controller;

use Pimcore\Model\DataObject\User;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Image;
use \Pimcore\Model\DataObject;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Part\HtmlPart;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;



class AccountController extends FrontendController
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

        $user= $this->findUserByEmail($email);
       
        // Verify the password
        if (password_verify($password, $user->getPassword())) {
            // Password is correct, proceed with the login logic
            $session = $request->getSession();
            $session->set('user_logged_in', true);
            $session->set('user', $user);
            return $this->render('onepager/onepager.html.twig');
        
        }

        // User not found or incorrect password, handle the error accordingly
        return new Response('Invalid email or password!');
    }

    public function logout(Request $request): Response
    {
        // Clear the user's session
        $session = $request->getSession();
        $session->remove('user_logged_in');
        $session->remove('user');
        // Redirect to the main page
        return $this->render('onepager/onepager.html.twig'); 
    }
    public function register(Request $request): Response
    {
        // Retrieve the form data
        $username = $request->request->get('name');
        //remove whitespaces from username
        $username = str_replace(' ', '', $username);
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirm_password');
        
        // Validate the form data, perform any necessary checks
        if ($password !== $confirmPassword) {
            return new Response('Passwords do not match!');
        }
        $uploadedFile = $request->files->get('profileimage');
        $imagePath = $uploadedFile->getPathname();

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Create a new User object (Pimcore DataObject)
        $user = new User();
        $userFolder = DataObject::getByPath("/User");
        $userFolderId = $userFolder->getId();
        $user->setKey($username); 
        // Set the parent folder's ID where users are stored
        $user->setParentId($userFolderId); 
        // Mark the user as published
        $user->setPublished(true); 
       
        // Set user properties using setter methods
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($hashedPassword); // Set the hashed password
        $user->save();
        $userId = $user->getId();
        //Create new Asset from uploaded image
        $newAsset = new \Pimcore\Model\Asset\Image();
        $newAsset->setFilename($userId."Profileimage.png");
        $newAsset->setData(file_get_contents($imagePath));
        $newAsset->setParent(\Pimcore\Model\Asset::getByPath("/Users"));
        $newAsset->save();
        $user->setImage($newAsset);
        $user->save();
        return $this->render('onepager/onepager.html.twig');
    }

    public function sendPasswordResetEmail(Request $request, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        // Retrieve the user's email from the form data
        $email = $request->request->get('email');
        $user = $this->findUserByEmail($email);
        $params = array('username' => $user->getUsername());        

        $resetPasswordUrl = $urlGenerator->generate('setNewPassword', ['linkTimestamp' => time(), 'email' => $email], UrlGeneratorInterface::ABSOLUTE_URL);
        // Create the HTML content for the email
        $htmlContent = '<p>Hello ' .  $params['username'] . ',</p>'
                    . '<p>Your password reset link: <a href="'. $resetPasswordUrl .'">Reset Password</a></p>'.
                    '<p>This link will expire in 20 minutes!</p>';

        // Create an Email instance
        $email = (new Email())
            ->from('your@email.com') // Set the sender's email address here
            ->to($email) // Use the provided email from the form data
            ->subject('Password Reset')
            ->html($htmlContent);

        // Sending the email
        $mailer->send($email);

        // Return a response
        return new Response('Password reset email sent successfully');
    }
    
    public function showResetPasswordTemplate(Request $request, string $linkTimestamp, string $email): Response
    {
        // Find the user by token
        // Get the token timestamp from your database or source

        // Calculate the current time + 20 minutes
        $currentTime = time();
        $expiryTime = $linkTimestamp + (20 * 60); // 20 minutes in seconds
        $user = $this->findUserByEmail($email);
        if ($currentTime > $expiryTime) {
            return new Response('Your token expired, request a new password reset!');
        }

        // Render the password reset form, passing the user
        return $this->render('resetPassword/resetPassword.html.twig', [
            'user' => $user,
        ]);
    }

    public function findUserByEmail($email) {
        $users = new User\Listing(); // Get a listing of User objects
        $users->setCondition("email = ?", [$email]);
        $users->setLimit(1);

        foreach ($users as $user) {
            if ($user instanceof User) {
                return $user;
            }
        }
    }
}
    
