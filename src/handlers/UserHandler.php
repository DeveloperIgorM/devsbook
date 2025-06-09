<?php

namespace src\handlers;

use \src\models\User;

// classe especifica para fazer verificações de login 

class UserHandler {
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
        $loggedUser->name = $data['name'];
        $loggedUser->avatar = $data['avatar'];

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

  public static function idExists($id) {
    $user = User::select()->where('email', $id)->one();
    return $user ? true : false;
  }
  public static function emailExists($email) {
    $user = User::select()->where('email', $email)->one();
    return $user ? true : false;
  }

  public static function getUser($id) {
    $data = User::select()->where('id', $id)->one();

    if ($data) {
      $user = new User();
      $user->id = $data['id'];
      $user->name = $data['name'];
      $user->birthdate = $data['birthdate'];
      $user->city = $data['city'];
      $user->work = $data['work'];
      $user->avatar = $data['avatar'];
      $user->cover = $data['cover'];

      return $user;
    }

    return false;
  }


  public static function addUser($name, $email, $password, $birthdate) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $token = md5(time() . rand(0, 9999) . time());

    User::insert([
      'email' => $email,
      'password' => $hash,
      'name' => $name,
      'birthdate' => $birthdate,
      'token' => $token
    ])->execute();

    return $token;
  }
}
