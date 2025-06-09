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
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }

    }

    public function index($atts = []) {
      $id = $this->loggedUser->id;

      if(!empty($atts['id'])) {
        $id = $atts['id'];
      } 
      
      $user = UserHandler::getUser($id);

      if(!$user) {
        $this->redirect('/');
      }

      $this->render('profile', [
        'loggedUser' => $this->loggedUser,
        'user' => $user
      ]);

    }
}