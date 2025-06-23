<?php

namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use src\handlers\PostHandler;

class AjaxController extends Controller {

  // variável que armezana o usuário logado
  private $loggedUser;

  public function __construct() {
    //checando se o usuário está logado
    $this->loggedUser = UserHandler::checkLogin();
    if ($this->loggedUser === false) {
      // Caso o usuário não esteja logado
      header("Content-Type: application/json");
      echo json_encode(['error' => 'Usuário não logado']);
    }
  }

  public function like($atts) {
  
    $id = $atts['id'];

    if (PostHandler::isLiked($id, $this->loggedUser->id)) {
      PostHandler::deleteLike($id, $this->loggedUser->id);
    } else {
      PostHandler::addLike($id, $this->loggedUser->id);
    }
  }
}
