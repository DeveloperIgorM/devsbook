<?php

namespace src\handlers;

use \src\models\User;

// classe especifica para fazer verificações de login 

class LoginHandler {
  public static function checkLogin() {
    // SE existir e não tiver vazia
    if (!empty($_SESSION['token'])) {
      $token = $_SESSION['token'];

      $data = User::select()->where('token', $token)->one();
      if (count($data) > 0) {
        //SE achou os dados...

        //preenche as informações
        $loggedUser = new User();
        $loggedUser->id = $data['id'];
        $loggedUser->email = $data['email'];
        $loggedUser->name = $data['name'];

        return $loggedUser;
      }
    }

    return false;
  }

  public static function verifyLogin($email, $password) {

    $user = User::select()->where('email', $email)->one();
    // gera o token
    if ($user) {
      if (password_verify($password, $user['password'])) {
        $token = md5(time() . rand(0, 9999) . time());

        // update no usuario para setar o token no usuário correto
        User::update()
          ->set('token', $token)
          ->where('email', $email)
          ->execute();

        return $token;
      }
    }
  }
}
