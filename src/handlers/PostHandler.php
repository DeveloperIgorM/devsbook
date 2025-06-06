<?php

namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UsersRelation;

class PostHandler {

  public static function addPost($idUser, $type, $body) {
    
    $body = trim($body);

    // Verificação para saber se idUser está preenchido
    if(!empty($idUser) && !empty($body)) {

      Post::insert([
        'id_user'=> $idUser,
        'type' => $type,
        'created_at' => date('Y-m-d H:i:s'),
        'body' => $body
      ])->execute();
    }
  }

  public static function getHomeFeed($idUser) {
    // 1. pegar lista de usuários que EU sigo.
    // pegando todos os registros que em 'user_from' sou eu
    $userList = UsersRelation::select()->where('user_from', $idUser)->get();
    $users = [];
    foreach($userList as $userItem) {
      $users[] = $userItem['user_to'];
    }
    // Me adicioanando na lista de pessoas que eu sigo
    $users[] = $idUser;

    print_r($users);

    // 2. pegar os posts dessa galera ordenado pela data.
    // 3. transformar os resultados em objetos dos models.
    // 4. preencher as informações adicionais no post.
    // 5. retornar o resultado.


  }

}