<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {

  // variável que armezana o usuário logado
  private $loggedUser;

  public function __construct() {
    // checando se o usuário está logado
    $this->loggedUser = UserHandler::checkLogin();
    if ($this->loggedUser === false) {
      $this->redirect('/login');
    }
  }

  public function index($atts = []) {
    $page = intval(filter_input(INPUT_GET, 'page'));

    // Detectando o usuário logado, se é o meu usuário ou se é de outra pessoa
    $id = $this->loggedUser->id;
    if (!empty($atts['id'])) {
      $id = $atts['id'];
    }

    // Pegando informações do usuário
    $user = UserHandler::getUser($id, true);

    if (!$user) {
      $this->redirect('/');
    }

    // Calculo que faz a diferença entre a Data de Nascimento do usuário com a Data atual (hoje)
    $dateFrom = new \DateTime($user->birthdate);
    $dateTo = new \DateTime('today');
    $user->ageYears = $dateFrom->diff($dateTo)->y;

    // Pegando o feed do usuário
    $feed = PostHandler::getUserFeed(
      $id,
      $page,
      $this->loggedUser->id
    );

    // Verificar se EU sigo o usuário
    $isFollowing = false;
    if ($user->id != $this->loggedUser->id) {
      $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
    }


    $this->render('profile', [
      'loggedUser' => $this->loggedUser,
      'user' => $user,
      'feed' => $feed,
      'isFollowing' => $isFollowing
    ]);
  }

  public function follow($atts) {
    $to = intval($atts['id']);


    if (UserHandler::idExists($to)) {
      if (UserHandler::isFollowing($this->loggedUser->id, $to)) {

        //desseguir 
        UserHandler::unfollow($this->loggedUser->id, $to);
      } else {
        //seguir
        UserHandler::follow($this->loggedUser->id, $to);
      }
    }

    $this->redirect('/perfil/'. $to);
  }

  public function friends($atts = []) {
    // Detectando o usuário logado, se é o meu usuário ou se é de outra pessoa
    $id = $this->loggedUser->id;
    if (!empty($atts['id'])) {
      $id = $atts['id'];
    }

    // Pegando informações do usuário
    $user = UserHandler::getUser($id, true);

    if (!$user) {
      $this->redirect('/');
    }

    // Calculo que faz a diferença entre a Data de Nascimento do usuário com a Data atual (hoje)
    $dateFrom = new \DateTime($user->birthdate);
    $dateTo = new \DateTime('today');
    $user->ageYears = $dateFrom->diff($dateTo)->y;

    // Verificar se EU sigo o usuário
    $isFollowing = false;
    if ($user->id != $this->loggedUser->id) {
      $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
    }

    $this->render('profile_friends', [
      'loggedUser' => $this->loggedUser,
      'user' => $user,
      'isFollowing' => $isFollowing
    ]);

  }

  public function photos($atts = []) {
    // Detectando o usuário logado, se é o meu usuário ou se é de outra pessoa
    $id = $this->loggedUser->id;
    if (!empty($atts['id'])) {
      $id = $atts['id'];
    }

    // Pegando informações do usuário
    $user = UserHandler::getUser($id, true);

    if (!$user) {
      $this->redirect('/');
    }

    // Calculo que faz a diferença entre a Data de Nascimento do usuário com a Data atual (hoje)
    $dateFrom = new \DateTime($user->birthdate);
    $dateTo = new \DateTime('today');
    $user->ageYears = $dateFrom->diff($dateTo)->y;

    // Verificar se EU sigo o usuário
    $isFollowing = false;
    if ($user->id != $this->loggedUser->id) {
      $isFollowing = UserHandler::isFollowing($this->loggedUser->id, $user->id);
    }

    $this->render('profile_photos', [
      'loggedUser' => $this->loggedUser,
      'user' => $user,
      'isFollowing' => $isFollowing
    ]);

  }
}
