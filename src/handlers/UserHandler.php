<?php

namespace src\handlers;

use \src\models\User;
use \src\models\UsersRelation;
use \src\handlers\PostHandler;

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

  // Função que verfica se já existe o ID no banco de dados
  public static function idExists($id) {
    $user = User::select()->where('id', $id)->one();
    return $user ? true : false;
  }

  // Função que verfica se o Email enviado já existe 
  public static function emailExists($email) {
    $user = User::select()->where('email', $email)->one();
    return $user ? true : false;
  }

  // Quando o usuário mandar FULL como true, quer dizer que ele quer as informações completas do usuário
  public static function getUser($id, $full = false) {
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

      if ($full) {
        $user->followers = [];
        $user->following = [];
        $user->photos = [];


        // Lista de pessoas que seguem X usuários

        // FOLLOWERS -> Seguidores
        $followers = UsersRelation::select()->where('user_to', $id)->get();  //pegando as relações
        foreach ($followers as $follower) {
          $userData = User::select()->where('id', $follower['user_from'])->one();  // user_from -> quem é que seguiu o usuário que estou acessando 


          // preenchendo o objeto de usuário
          $newUser = new User();
          $newUser->id = $userData['id'];
          $newUser->name = $userData['name'];
          $newUser->avatar = $userData['avatar'];

          // Adicionando o objeto de usuário no array de seguidores
          $user->followers[] = $newUser;
        }


        // FOLLOWING -> Seguindo
        $following = UsersRelation::select()->where('user_from', $id)->get();  // Aqui o User_from sou EU
        foreach ($following as $follower) {
          $userData = User::select()->where('id', $follower['user_to'])->one();  // E o User_to é uma outra pessoa que eu sigo

          // preenchendo o objeto de usuário
          $newUser = new User();
          $newUser->id = $userData['id'];
          $newUser->name = $userData['name'];
          $newUser->avatar = $userData['avatar'];

          // Adicionando o objeto de usuário no array de seguidores
          $user->following[] = $newUser;
        }

        // PHOTOS
        $user->photos = PostHandler::getPhotosFrom($id); // Pegando as fotos do usuários (posts)

      }

      return $user;
    }

    return false;
  }

  // Adiciona o usuário ao banco de dados
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

  // Função que verifica, se está seguindo ou não o usuário
  public static function isFollowing($from, $to) {
    $data = UsersRelation::select()
      ->where('user_from', $from)
      ->where('user_to', $to)
    ->one();

    if ($data) {
      return true;
    } else {
      return false;
    }
  }

  public static function follow($from, $to) {
    // Criando um registro
    UsersRelation::insert([
      'user_from' => $from,
      'user_to' => $to
    ])->execute();
  }

  public static function unfollow($from, $to) {
    // Deletando um registro
    UsersRelation::delete()
      ->where('user_from' ,$from)
      ->where('user_to', $to)
    ->execute();
    
  }

}
