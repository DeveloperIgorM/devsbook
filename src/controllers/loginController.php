<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;


class LoginController extends Controller {


  public function signin() {
    $flash = '';
    if (!empty($_SESSION['flash'])) {
      $flash = $_SESSION['flash'];
      $_SESSION['flash'] = '';
    }
    //render -> renderiza o html no php
    $this->render('signin', [
      'flash' => $flash
    ]);
  }

  public function signinAction() {
    // recebendo os dados
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');

    // verifica se está correto
    if ($email && $password) {

      $token = LoginHandler::verifyLogin($email, $password);

      if ($token) {

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
    $flash = '';
    if (!empty($_SESSION['flash'])) {
      $flash = $_SESSION['flash'];
      $_SESSION['flash'] = '';
    }
    //render -> renderiza o html no php
    $this->render('signup', [
      'flash' => $flash
    ]);
  }

  public function signupAction() {
    // recebendo os dados
    $name = filter_input(INPUT_POST, 'name');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = filter_input(INPUT_POST, 'password');
    $birthdate = filter_input(INPUT_POST, 'birthdate');

    // SE todos os campos foram preenchidos
    if ($name && $email && $password && $birthdate) {
      $birthdate = explode('/', $birthdate);

      if (count($birthdate) != 3) {
        $_SESSION['flash'] = 'Data de Nascimento inválida!';
        $this->redirect('/cadastro');
      }
      
      // Invertendo a order da data para data internacional yyyy/mm/dd
      $birthdate = $birthdate[2] . '-' . $birthdate[1] . '-' . $birthdate[0];

      // stringToTime -> tranforma uma string(tempo) em tempo real (milisegundos)
      if (strtotime($birthdate) === false) {
        $_SESSION['flash'] = 'Data de Nascimento inválida!';
        $this->redirect('/cadastro');
      }

      // Parte do cadastro efetivamente
      if(LoginHandler::emailExists($email) === false) {
        $token = LoginHandler::addUser($name, $email, $password, $birthdate);
        $_SESSION['token'] = $token;
        $this->redirect('/');

      } else {
        $_SESSION['flash'] = 'E-mail já cadastrado!';
        $this->redirect('/cadastro');
      }

    } else {
      $this->redirect('/cadastro');
    }
  }
}
