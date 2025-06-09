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

  public static function getHomeFeed($idUser, $page) {
    $perPage = 2;

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
      ->page($page, $perPage)
      ->get();  // pegando os posts, em que o id_user está presente na lista $users = pessoas que eu sigo

    //pegando o total de posts para paginação
    $total = Post::select()
      ->where('id_user', 'in', $users)
      ->count();
    $pageCount = ceil($total / $perPage); // jogando a divisão de total por perPage e jogando em total, o 'CEIL' arredonda pra cima



    // 3. transformar os resultados em objetos dos models.
    $posts = [];
    foreach ($postList as $postItem) {
      $newPost = new Post();
      $newPost->id = $postItem['id'];
      $newPost->type = $postItem['type'];
      $newPost->created_at = $postItem['created_at'];
      $newPost->body = $postItem['body'];
      $newPost->mine = false;

      if ($postItem['id_user'] == $idUser) { // verificando se o id do post é mesmo valor do id do usuário logado, para uma exclusão de post por exemplo
        $newPost->mine = true;
      }


      // 4. preencher as informações adicionais no post.
      $newUser = User::select()->where('id', $postItem['id_user'])->one(); //pegando informações do usuário
      $newPost->user = new User();
      $newPost->user->id = $newUser['id'];
      $newPost->user->name = $newUser['name'];
      $newPost->user->avatar = $newUser['avatar'];

      // TODO 4.1 preencher informações de LIKE
      $newPost->likeCount = 0;
      $newPost->liked = false;


      // TODO 4.2 preencher informações de COMMENTS
      $newPost->comments = [];

      $posts[] = $newPost;
    }


    // 5. retornar o resultado.

    return [
      'posts' => $posts,
      'pageCount' => $pageCount,
      'currentPage' => $page
    ];
  }

  public static function getPhotosFrom($idUser) {
    $photosData =  Post::select()
      ->where('id_user', $idUser)
      ->where('type', 'photo')
    ->get();

    $photos = [];

    foreach($photosData as $photo) {
      $newPost = new Post();
      $newPost->id = $photo['id'];
      $newPost->type = $photo['photo'];
      $newPost->created_at = $photo['created_at'];
      $newPost->body = $photo['body'];

      $photos[] = $newPost;
    }

    return $photos;
  }
}
