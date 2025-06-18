<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ConfigController extends Controller {

    private $loggedUser;


    public function __construct() {
        // checando se o usuário está logado
        $this->loggedUser = UserHandler::checkLogin();
        if ($this->loggedUser === false) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $user = UserHandler::getUser($this->loggedUser->id);

        $flash = '';
        if(!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }

        $this->render('config', [
            'loggedUser' => $this->loggedUser,
            'user'=> $user,
            'flash' => $flash
        ]);
    }

    public function save() {
        // recebendo os dados
        $name = filter_input(INPUT_POST, 'name');
        $birthdate = filter_input(INPUT_POST, 'birthdate');
        $email = filter_input(INPUT_POST, 'email');
        $city = filter_input(INPUT_POST, 'city');
        $work = filter_input(INPUT_POST, 'work');
        $password = filter_input(INPUT_POST, 'password');
        $passwordConfirm = filter_input(INPUT_POST, 'password_confirm');


        if ($name && $email) {
            $updateFields = [];

            $user = UserHandler::getUser($this->loggedUser->id);

            // E-mail
            if($user->email != $email) {
                if(!UserHandler::emailExists($email)) {

                    $updateFields['email'] = $email;
                } else {
                    $_SESSION['flash'] = 'E-mail já existe!';
                    $this->redirect('/config');
                }
            }
            
          // BIRTHDATE
          $birthdate = explode('/', $birthdate);
          if(count($birthdate) != 3) {
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            $this->redirect('/config');
          }
          $birthdate = $birthdate[2].'-'.$birthdate[1].'-'.$birthdate[0];
          if(strtotime($birthdate) === false) {
            $_SESSION['flash'] = 'Data de nascimento inválida!';
            $this->redirect('/config');
          }
          $updateFields['birthdate'] = $birthdate;

          // PASSWORD
          if(!empty($password)) {
            if($password === $passwordConfirm) {
                $updateFields['password'] = $password;
            } else {
                $_SESSION['flash'] = 'As senha não batem.';
                $this->redirect('/config');
            }
          }

          // CAMPOS NORMAIS
          $updateFields['name'] = $name;
          $updateFields['city'] = $city;
          $updateFields['work'] = $work;

          UserHandler::updateUser($updateFields, $this->loggedUser->id);
        }

        $this->redirect('/config');
    }
}
