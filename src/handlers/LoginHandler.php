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
      if(count($data) > 0) {
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
}
