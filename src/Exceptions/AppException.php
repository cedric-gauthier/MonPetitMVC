<?php
namespace App\Exceptions;

use Exception;

class AppException extends Exception {
    
    const NOMUSERCONNECTE = APP_USER;
    const NOMAPPLICATION = APP_NAME; 
    
    public function __construct(string $message){
        parent::__construct("Erreur d'application " . self::NOMAPPLICATION . "<br> user : " . 
                self::NOMUSERCONNECTE . "<br> message :" . $message);
    }
}    