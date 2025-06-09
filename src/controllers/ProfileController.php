<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\LoginHandler;
use \src\handlers\PostHandler;

class ProfileController extends Controller {

    // variável que armezana o usuário logado
    private $loggedUser;

    public function __construct() {
        // checando se o usuário está logado
        $this->loggedUser = LoginHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }

    }

    public function index($atts = []) {
      $this->render('profile', [
        'loggedUser' => $this->loggedUser
      ]);

    }
}