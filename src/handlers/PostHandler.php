<?php

namespace src\handlers;

use \src\models\Post;
use \src\models\User;
use \src\models\UsersRelation;

class PostHandler {

  public static function addPost($idUser, $type, $body) {

    $body = trim($body);

    // Verificação para saber se idUser está preenchido
    if (!empty($idUser) && !empty($body)) {

      Post::insert([
        'id_user' => $idUser,
        'type' => $type,
        'created_at' => date('Y-m-d H:i:s'),
        'body' => $body
      ])->execute();
    }
  }

  public static function getHomeFeed($idUser) {

    // 1. pegar lista de usuários que EU sigo.
    $userList = UsersRelation::select()->where('user_from', $idUser)->get();  // pegando todos os registros que em 'user_from' sou eu
    $users = [];
    foreach ($userList as $userItem) {
      $users[] = $userItem['user_to'];
    }
    // Me adicioanando na lista de pessoas que eu sigo
    $users[] = $idUser;


    // 2. pegar os posts dessa galera ordenado pela data.
    $postList = Post::select()
      ->where('id_user', 'in', $users)
      ->orderBy('created_at', 'desc')
      ->get();  // pegando os posts, em que o id_user está presente na lista $users = pessoas que eu sigo


    // 3. transformar os resultados em objetos dos models.
    $posts = [];
    foreach ($postList as $postItem) {
      $newPost = new Post();
      $newPost->id = $postItem['id'];
      $newPost->type = $postItem['type'];
      $newPost->created_at = $postItem['created_at'];
      $newPost->body = $postItem['body'];

      // 4. preencher as informações adicionais no post.
      $newUser = User::select()->where('id', $postItem['id_user'])->one(); //pegando informações do usuário
      $newPost->user = new User();
      $newPost->user->id = $newUser['id'];
      $newPost->user->name = $newUser['name'];
      $newPost->user->avatar = $newUser['avatar'];

      // TODO 4.1 preencher informações de LIKE
      // TODO 4.2 preencher informações de COMMENTS

      $posts[] = $newPost;
    }


    // 5. retornar o resultado.

    return $posts;
  }
}
