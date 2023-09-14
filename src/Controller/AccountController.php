<?php

namespace App\Controller;

use Pimcore\Model\DataObject\User;
use Pimcore\Controller\FrontendController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \Pimcore\Model\DataObject;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;



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
        
        // Find the user by email
        $user = $this->findUserByEmail($email);
    
        if (!$user instanceof User || !password_verify($password, $user->getPassword())) {
            return new Response("Error: Überprüfe deine Eingaben!", 400);
        }
    
        // Successful login
        $session = $request->getSession();
        $session->set('user_logged_in', true);
        $session->set('user', $user);
    
        // Generate the route using Symfony's path function
        $onepagerRoute = $this->generateUrl('onepager');
    
        return new Response($onepagerRoute, 201);
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
        // Remove whitespaces from the username
        $username = str_replace(' ', '', $username);
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');
        
        // Validate the form data
        if ($password !== $confirmPassword) {
            return new Response('Passwords do not match!', 400);
        }
    
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Create a new User object (Pimcore DataObject)
        $user = new User();
        $userFolder = DataObject::getByPath("/User");
        $userFolderId = $userFolder->getId();
        $user->setKey($username); 
        $user->setParentId($userFolderId);
        $user->setPublished(true);
    
        // Set user properties using setter methods
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $user->save();
    
        // Create new Asset from uploaded image
        $uploadedFile = $request->files->get('profileimage');
        $imagePath = $uploadedFile ? $uploadedFile->getPathname() : __DIR__ . '/../../public/static/assets/img/user-profile-with-cross-grey-icon-doctor-vector-32550061.png';
        
        $this->setProfileImage($user, $imagePath);
    
        return new Response('Account erfolgreich angelegt! Log dich ein!', 200);
    }
    
    public function setProfileImage($user, $imagePath)
    {
        $userId = $user->getId();
        
        $filename = $userId . "Profileimage.png";
        
        $parentFolder = \Pimcore\Model\Asset::getByPath("/Users");
        
        if (!$parentFolder instanceof \Pimcore\Model\Asset\Folder) {
            $parentFolder = new \Pimcore\Model\Asset\Folder();
            $parentFolder->setKey("Users");
            $parentFolder->setParentId(1); 
            $parentFolder->save();
        }

        $newAsset = new \Pimcore\Model\Asset\Image();
        $newAsset->setFilename($filename);
        $newAsset->setData(file_get_contents($imagePath));
    
        $newAsset->setParent($parentFolder);
        
        $newAsset->save();

        $user->setImage($newAsset);
    
        $user->save();
    }
    
    public function sendPasswordResetEmail(Request $request, MailerInterface $mailer, UrlGeneratorInterface $urlGenerator): Response
    {
        // Retrieve the user's email from the form data
        $email = $request->request->get('email');
        $user = $this->findUserByEmail($email);

        if($user instanceof User) {        
            $token = $this->createUniqueToken($user);
            $params = array('username' => $user->getUsername());        

            $resetPasswordUrl = $urlGenerator->generate('setNewPassword', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
            // Create the HTML content for the email
            $htmlContent = '<p>Hallo ' .  $params['username'] . ',</p> <br>'
                        . '<p>Hier ist der Link zum Zurücksetzen deines Passworts: <a href="'. $resetPasswordUrl .'">Passwort zurücksetzen</a></p><br>'.
                        '<p>Dieser Link wird nur für die nächsten 20 Minuten gültig sein!</p>';

            // Create an Email instance
            $email = (new Email())
                ->from('THMStudyPlanner@gmail.com') // Set the sender's email address here
                ->to($email) // Use the provided email from the form data
                ->subject('Zurücksetzen deines Passworts')
                ->html($htmlContent);

            // Sending the email
            $mailer->send($email);        
            return new Response('Die Email zum Zurücksetzen des Passworts wurde erfolgreich versendet, schau in dein Mailfach!', 200);

        } else {
            return new Response('Überprüfe deine Mail oder wende dich an unseren Support!', 400);
        }
    }
    
    public function showResetPasswordTemplate(Request $request, string $token): Response
    {
        // Calculate the current time + 20 minutes        
        $user = $this->findUserByToken($token);
        $currentTime = time();
        $tokenProperty = $user->getClass()->getFieldDefinition('token');
        $expiryTime = $tokenProperty->getTooltip();; // 20 minutes in seconds

        if ($currentTime > $expiryTime) {
            return new Response('Your token expired, request a new password reset!', 400);
        }

        // Render the password reset form, passing the user
        return $this->render('resetPassword/resetPassword.html.twig', [
            'user' => $user,
        ]);
    }

    public function setNewUserPassword(Request $request, string $email): Response
    {
        $user = $this->findUserByEmail($email);
        $newPassword = $request->request->get('newPassword');
        $confirmPassword = $request->request->get('confirmNewPassword');
        if ($newPassword !== $confirmPassword) {
            return new Response('Passwords do not match!', 400);
        }
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->setPassword($hashedNewPassword); // Set the new hashed password
        $user->save();
        return $this->render('onepager/onepager.html.twig');
    }

    public function createUniqueToken(object $user): string {
        $token = bin2hex(random_bytes(16));
        $userId = $user->getId();
        $combinedToken = $userId . '_' . $token;
        $tokenExpiryTime =  time() + (20 * 60); // 20 minutes in seconds
        $user->setToken($combinedToken);
        // Manually set the tooltip for the token property
        $tokenProperty = $user->getClass()->getFieldDefinition('token');
        $tooltip = $tokenExpiryTime; // Adjust this tooltip text as needed
        $tokenProperty->setTooltip($tooltip);
    
        // Save the user with updated token and tooltip
        $user->save();
        // Return the combined token
        return $combinedToken;
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

    public function findUserByToken($token) {
        $users = new User\Listing(); // Get a listing of User objects
        $users->setCondition("token = ?", [$token]);
        $users->setLimit(1);
        
        foreach ($users as $user) {
            if ($user instanceof User) {
                return $user;
            }
        }
    }
}
