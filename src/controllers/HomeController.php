<?php
namespace src\controllers;

use \core\Controller;
use \src\handlers\UserHandler;
use \src\handlers\PostHandler;

class HomeController extends Controller {

    // variável que armezana o usuário logado
    private $loggedUser;

    public function __construct() {
        // checando se o usuário está logado
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false) {
            $this->redirect('/login');
        }

    }

    public function index() {

        $page = intval(filter_input(INPUT_GET, 'page'));

        // pegando o feed da home
        $feed = PostHandler::getHomeFeed(
            $this->loggedUser->id,
            $page
        );



        $this->render('home', [
            'loggedUser' => $this->loggedUser,
            'feed' => $feed
        ]);
    }


}