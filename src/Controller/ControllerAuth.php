<?php

namespace MyApp\Controller;

class ControllerAuth {

    private $auth;
    private $post;
    private $comment;
    private $tools;

    public function __construct() {
        $this->auth = new \MyApp\Model\Auth();
        $this->post = new \MyApp\Model\Post();
        $this->comment = new \MyApp\Model\Comment();
        $this->tools = new \MyApp\Tools\Tools();
    }
    
    // Do a random key with the characters, lenght = number of characters
    public function random($length) {
    $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
    return substr( str_shuffle( str_repeat( $alphabet, $length ) ), 0, $length );
    }
    
    // Hash the password
    public function hashPassword($password){
        return password_hash($_POST['password'], PASSWORD_BCRYPT);
    }
    
    // Get the password and verify if it match
    public function tryPassword($password, $username){
        $match = null;
        $passwordHashed = $this->auth->getPassword($username);
        if($passwordHashed){
            if(password_verify($password, $passwordHashed['password'])){
                return $match = true;
            } else {
                return $match = false;
            }  
        }
    }
    
    // Verify the username
    public function usernameIsUniq($username){
        if($this->auth->usernameIsUniqAuth($username)){
            $users = $this->auth->usernameIsUniqAuth($username);
            return $users;
        }
    }
    
    // Verify the email
    public function emailIsUniq($email){
        if($this->auth->emailIsUniqAuth($email)){
            $users = $this->auth->emailIsUniqAuth($email);
            return $users;
        }
    }
    
    // Register the user by hashing the password, building a token with a lenght of 60, sending a email
    public function register($username, $email, $password){
        $password = $this->hashPassword($password);
        $token = $this->random(60);
        $user = $this->auth->registerAuth($username, $email, $password, $token);
        $user_id = $user['id'];
        $mail = new \MyApp\Tools\FunctionMail();
        $subject = "La page web EsMater Confirmation de votre compte";
        $messageContent = "Afin de valider votre compte merci de cliquer sur ce lien : <a href='http://localhost/projets/EsMater/index.php?action=confirm&id=$user_id&token=$token'>Cliquer ici</a>";
        $sender = $email;
        $mail->sendmail($subject, $messageContent, $sender);
        $this->tools->sessionOn();
        $this->tools->flashMessage("success", "Un email de confirmation vous a été envoyé pour valider votre compte", "Login");
    }
    
    // Show the error page with error/s
    public function errors($errors){
        $this->tools->sessionOn();
        $view = new \MyApp\View\View("Register");
        $view->generate(array('errors' => $errors));
    }
    
    // Confirm the token if the link been visited
    public function confirmationToken($user_id, $token){
        $user = $this->auth->confirmationTokenAt($user_id, $token);
         $this->tools->sessionOn();
        if(!$user){
            $this->tools->flashMessage("danger", "Ce lien n'est plus valide", "Login");
        }else {
            $_SESSION["flash"]["success"] = "Votre compte à bien été validé";
            $_SESSION["auth"] = $user;
            $this->auth->deleteTokenAt($user_id);
            $this->tools->redirectionAccount($_SESSION["auth"]['id']);
        }
    }
    
    // Login the user
    public function loginUser($username, $password, $remember){
        $match = $this->tryPassword($password, $username);
        if($match){
            $user = $this->auth->loginUserAuth($username, $password);
            $this->tools->sessionOn();
            if($remember){
                $_SESSION["flash"]["success"] = "Vous êtes maintenant connecté";
                $_SESSION["auth"] = $user;
                $token = $this->random(250);
                $user_id = $user['id'];
                $this->auth->rememberTokenAuth($token, $user_id);
                setcookie('remember', $user_id . '==' . $token . sha1($user_id . "ravioli"), time() + 60 * 60 * 24 * 7);
                $this->tools->redirectionAccount($_SESSION["auth"]['id']);
            } else {
                $_SESSION["flash"]["success"] = "Vous êtes maintenant connecté";
                $_SESSION["auth"] = $user;
                $this->tools->redirectionAccount($_SESSION["auth"]['id']);
            }
        } else {
            $this->tools->flashMessage("danger", "Identifiant ou mot de passe incorrecte", "Login");
        }
    }

    // Modify the password
    public function updatePassword($user_id, $password){
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = $this->auth->updatePasswordAuth($user_id, $passwordHash);
        $_SESSION["flash"]["success"] = "Votre mot de passe a bien été mis à jours";
        $this->tools->redirectionAccount($_SESSION["auth"]['id']);
    }
    
    // If ask for a new password, send a key link by email
    public function newPassword($email){
        $user = $this->auth->sendEmailRemember($email);
        $this->tools->sessionOn();
        if(!$user){
            $this->tools->flashMessage("danger", "Aucun compte ne correspond à cette adresse", "Forget");
        }else {
            $reset_token = $this->random(60);
            $this->auth->resetToken($reset_token, $user['id']);
            $mail = new \MyApp\Tools\FunctionMail();
            $subject = "La page web EsMater Réinitialisation de votre mot de passe";
            $messageContent = "Afin de réinitialisation votre mot de passe merci de cliquer sur ce lien : <a href='http://localhost/projets/EsMater/index.php?action=forgetPasswordAuth&id={$user['id']}&token=$reset_token'>Cliquer ici</a>";
            $sender = $email;
            $mail->sendmail($subject, $messageContent, $sender);
            $this->tools->flashMessage("success", "Un email pour réinitialiser votre mot de passe à bien été envoyé", "Login");
        }
    }

    // Verify the token for update a forgotten password
    public function verifyTokenAuth($user_id, $token){
        $user = $this->auth->verifyTokenAuth($user_id, $token);
        if(!$user){
            $this->tools->sessionOn();
            $this->tools->flashMessage("danger", "Ce lien n'est pas valide", "Login");
        } else {
            $this->tools->sessionOn();
            $view = new \MyApp\View\View("Reset");
            $view->generate(array('user' => $user));
        }
    }
    
    // Reset the password
    public function resetPassword($user_id, $password){
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $this->auth->resePasswordAuth($user_id, $passwordHash);
        $user = $this->auth->getAuth($user_id);
        $this->tools->sessionOn();
        $_SESSION["flash"]["success"] = "Votre mot de passe a bien été modifié";
        $_SESSION["auth"] = $user;
        $this->tools->redirectionAccount($_SESSION["auth"]['id']);
        exit();
    }
    
    // If a login token is on, and the user connect by using it, refresh the timer of the token
    public function loginByRememberToken($remember_token){
        $parts = explode('==', $remember_token);
        $user_id = $parts[0];
        $user = $this->auth->getAuthByRememberToken($user_id);
        if($user){
           $expected  = $user_id . '==' . $user['remember_token'] . sha1($user_id . "ravioli");
            if($expected == $remember_token){
                $this->tools->sessionOn();
                $_SESSION["auth"] = $user;
                setcookie('remember', $remember_token, time() + 60 * 60 * 24 * 7);
            } else {
                setcookie('remember', NULL, -1);
            }
        } else {
            setcookie('remember', NULL, -1);
        }  
    }
    
    // Add an avatar
    public function addAvatar($img, $authId, $userId){
        $maxSize = 5242880;
        $ext = strtolower(substr(strrchr($img['name'], '.'), 1));
        $allow_ext = array('jpg', 'jpeg', 'gif', 'png');
        if($img['size'] <= $maxSize){
            if(in_array($ext, $allow_ext)){
                $nameImg = $userId . "." . $ext;
                $result = move_uploaded_file($img['tmp_name'], "src/Content/images/avatars/" . $nameImg);
                if($result){
                    $user = $this->auth->updateAvatar($authId, $nameImg);
                    $this->comment->updateAvatarComment($authId, $nameImg);
                    $this->post->updateAvatarPost($authId, $nameImg);
                    if($user){
                        $user = $this->auth->getAuth($authId);
                        $_SESSION["flash"]["success"] = "Votre photo de profil à bien été modifié";
                        $_SESSION["auth"] = $user;
                        $this->tools->redirectionAccount($_SESSION["auth"]['id']);
                    }
                } else{
                    $_SESSION['flash']['danger'] = "Erreur durant l'importation de votre photo de profil";
                    $this->tools->redirectionAccount($_SESSION["auth"]['id']);
                }
            } else{
                $_SESSION['flash']['danger'] = "Votre photo de profil doit être au format jpg, jpeg, gif ou png";
                $this->tools->redirectionAccount($_SESSION["auth"]['id']);
            }
        } else{
            $_SESSION['flash']['danger'] = "Votre photo de profil ne doit pas dépasser 5Mo";
            $this->tools->redirectionAccount($_SESSION["auth"]['id']);
        }
    }
    
}