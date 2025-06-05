<?php
namespace src\controllers;

use \core\Controller;

class LoginController extends Controller {

    
  public function signin() {
    //render -> renderiza o html no php
    $this->render('login');
  }

  // recebendo os dados
  public function signinAction() {
    echo 'Login - recebido';
  }

  public function signup() {
    echo 'cadastro';
  }
}