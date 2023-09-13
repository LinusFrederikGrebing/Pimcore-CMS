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
        $user= $this->findUserByEmail($email);
        if(!$user instanceof User || !password_verify($password, $user->getPassword())) {
          return new Response("Error: Überprüfe deine Eingaben!"); // Return the route
        } else {
            // Successful login
            $session = $request->getSession();
            $session->set('user_logged_in', true);
            $session->set('user', $user);

            // Use Symfony's path function to generate the route
            $onepagerRoute = $this->generateUrl('onepager');
            return new Response($onepagerRoute); // Return the route
        }
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
        $confirmPassword = $request->request->get('confirmPassword');
        // Validate the form data, perform any necessary checks
        if ($password !== $confirmPassword) {
            return new Response('Passwords do not match!');
        }

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
        //Create new Asset from uploaded image
        if($request->files->get('profileimage')) {
            $uploadedFile = $request->files->get('profileimage');
            $imagePath = $uploadedFile->getPathname();
            $this->setProfileImage($user, $imagePath);
        } else {
            $imagePath = __DIR__ . '/../../public/static/assets/img/user-profile-with-cross-grey-icon-doctor-vector-32550061.png';
            $this->setProfileImage($user, $imagePath );
        }
        return new Response('Account erfolgreich angelegt!Log dich ein!', 200);
    }
    public function setProfileImage($user, $imagePath) {
        $userId = $user->getId();
        $newAsset = new \Pimcore\Model\Asset\Image();
        $newAsset->setFilename($userId."Profileimage.png");
        $newAsset->setData(file_get_contents($imagePath));
        $newAsset->setParent(\Pimcore\Model\Asset::getByPath("/Users"));
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
            return new Response('Die Email zum Zurücksetzen des Passworts wurde erfolgreich versendet, schau in dein Mailfach!');

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
            return new Response('Your token expired, request a new password reset!');
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
            return new Response('Passwords do not match!');
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
        $users = new User\Listing();
        $users->setCondition("email = ?", [$email]);
        $users->setLimit(1);
        
        foreach ($users as $user) {
            if ($user instanceof User) {
                return $user;
            }
        }
    }

    public function findUserByToken($token) {
        $users = new User\Listing(); 
        $users->setCondition("token = ?", [$token]);
        $users->setLimit(1);
        
        foreach ($users as $user) {
            if ($user instanceof User) {
                return $user;
            }
        }
    }

    public function updateProfile(Request $request): Response {
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $session = $request->getSession();
        $user = $session->get('user');
        $uploadedFile = $request->files->get('profileimage');
        if($uploadedFile) {
            $imagePath = $uploadedFile->getPathname();
            $this->setProfileImage($user, $imagePath);
        }
        if($name) {
            $user->setUsername($name);
        }
        if($email) {
            $user->setEmail($email);
        }
        $user->save();
        return new Response('Aktualisiert!');
    }
}
