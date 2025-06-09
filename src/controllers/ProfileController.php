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
      
      $user = UserHandler::getUser($id, true);

      if(!$user) {
        $this->redirect('/');
      }

      // Calculo que faz a diferença entre a Data de Nascimento do usuário com a Data atual (hoje)
      $dateFrom = new \DateTime($user->birthdate);
      $dateTo = new \DateTime('today');
      $user->ageYears = $dateFrom->diff($dateTo)->y;

      $this->render('profile', [
        'loggedUser' => $this->loggedUser,
        'user' => $user
      ]);

    }
}