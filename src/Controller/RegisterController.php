<?php

namespace App\Controller;

use Pimcore\Model\DataObject\User; // Import the User class from DataObject
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Pimcore\Model\Asset;
use Pimcore\Model\Asset\Image;
use Pimcore\Controller\FrontendController;
use \Pimcore\Model\DataObject;


class RegisterController extends Frontendcontroller
{   

    public function register(Request $request): Response
    {
        // Retrieve the form data
        $username = $request->request->get('name');
        //remove whitespaces
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

        // Now you have the path to the uploaded image, you can save it or use it as needed.
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Create a new User object (Pimcore DataObject)
        $user = new User();
        $userFolder = DataObject::getByPath("/User");
        $userFolderId = $userFolder->getId();
        $user->setKey($username); // Set a unique key, typically username or email
        $user->setParentId($userFolderId); // Set the parent folder's ID where users are stored
        $user->setPublished(true); // Mark the user as published
       
        // Set user properties using setter methods
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($hashedPassword); // Set the hashed password
        $user->save();
        $userId = $user->getId();
        $newAsset = new \Pimcore\Model\Asset\Image();
        $newAsset->setFilename($userId."Profileimage.png");
        $newAsset->setData(file_get_contents($imagePath));
        $newAsset->setParent(\Pimcore\Model\Asset::getByPath("/Users"));
        $newAsset->save();
        $user->setImage($newAsset);
        $user->save();
        return $this->render('onepager/onepager.html.twig');
    }
}