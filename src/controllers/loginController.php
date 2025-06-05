<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;


class LoginController extends Controller {

    
  public function signin() {
    $flash = '';
    if(!empty($_SESSION['flash'])) {
      $flash = $_SESSION['flash'];
      $_SESSION['flash'] = '';
    }
    //render -> renderiza o html no php
    $this->render('login', [
      'flash' => $flash
    ]);
  }

  public function signinAction() {
    // recebendo os dados
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    // verifica se está correto
    if($email && $password) {

      $token = LoginHandler::verifyLogin($email, $password);

      if($token) {

        $_SESSION['token'] = $token;
        $this->redirect('/');

      } else {

        $_SESSION['flash'] = 'E-mail e/ ou senha não conferem.';
        $this->redirect('/login');
      }

    } else {
      // Menssagem de aviso 'flash'
      $_SESSION['flash'] = 'Digite os campos de e-mail e/ou senha.';
      $this->redirect('/login');
    }
  }

  public function signup() {
    echo 'cadastro';
  }
}