<?php
session_start();

require 'controllers/abstract/Controller.php';
require 'controllers/AuthorController.php';
require 'controllers/AuthController.php';
require 'controllers/BookController.php';
require 'controllers/BookConditionController.php';
require 'controllers/CategoryController.php';
require 'controllers/UserController.php';
require 'controllers/FineController.php';
require 'controllers/LoanController.php';
require 'controllers/PermissionController.php';
require 'controllers/PublisherController.php';
require 'controllers/ReservationController.php';
require 'controllers/RoleController.php';


$controller = new Controller();

$model = isset($_GET['model']) ? $_GET['model'] : 'index';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';
$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!isset($_SESSION['user_id']) && !in_array($action, ['login', 'register','register_success'])) {
    header('Location: index.php?model=auth&action=login');
    exit();
}

switch ($model) {
    case 'role':
        $controller = new RoleController();
        break;
    case 'permission':
        $controller = new PermissionController();
        break;
    case 'user':
        $controller = new UserController();
        break;
    case 'book':
        $controller = new BookController();
        break;   
    case 'bookcondition':
        $controller = new BookConditionController();
        break;
    case 'fine':
        $controller = new FineController();
        break;
    case 'reservation':
        $controller = new ReservationController();
        break;
    case 'auth':
        $controller = new AuthController();
        break;
    case 'author':
        $controller = new AuthorController();
        break;
    case 'loan':
        $controller = new LoanController();
        break;
    case 'publisher':
        $controller = new PublisherController();
        break;   
    case 'category':
        $controller = new CategoryController();
        break;       
    default:
        $controller = new Controller();
        break;
}
switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
    case 'show':
        $controller->show($id);
        break;
    case 'login':
        $controller->login();
        break;
    case 'register':
        $controller->register();
        break;
    case 'logout':
        $controller->logout();
        break;
    case 'register_success':
        $controller->register_success();
        break;
    case 'update_status':
        $controller->update_status($status, $returnDate = null);
            break;
    case 'loadAuthors':
        $controller->loadAuthors();
        break;    
    default:
        $controller->index();
        break;
}
?>