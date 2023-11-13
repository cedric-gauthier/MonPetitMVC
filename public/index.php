<?php

use App\Exceptions\AppException;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

define('DS', DIRECTORY_SEPARATOR);
define('RACINE', new DirectoryIterator(dirname(__FILE__)) . DS . ".." . DS);
include_once(RACINE . DS . 'config/conf.php');
include_once(PATH_VENDOR . "autoload.php");
include_once(RACINE . DS . 'includes/params.php');

$loader = new FilesystemLoader(PATH_VIEW);
$twig = new Environment($loader);

try {
    if ((!array_key_exists('c', $_GET)) || (!array_key_exists('a', $_GET))) {
        throw new Exception("Erreur, cette page n'existe pas");
    }
    $BaseController = filter_input(INPUT_GET, 'c', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $action = filter_input(INPUT_GET, 'a', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $controller = "App\\Controller\\" . $BaseController . "Controller";
    if (class_exists($controller, true)) {
        $c = new $controller();
        $params = array(array_slice($_REQUEST, 2));
        call_user_func_array(array($c, $action), $params);
    } else {
        throw new Error("Le contrôleur demandé n'existe pas");
    }
} catch (Error $ex) {
    $errorTemplate = $twig->load('errors/error.html.twig');
    echo $errorTemplate->render(['error_message' => $ex->getMessage()]);
} catch (AppException $ex) {
    $errorTemplate = $twig->load('errors/error.html.twig');
    echo $errorTemplate->render(['error_message' => $ex->getMessage()]);
} catch (Exception $ex) {
    $errorTemplate = $twig->load('errors/error.html.twig');
    echo $errorTemplate->render(['error_message' => $ex->getMessage()]);
}

    

