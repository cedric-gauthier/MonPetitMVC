<?php

declare(strict_types=1);

namespace App\Controller;

use Tools\Repository;
use App\Model\GestionCommandeModel;
use ReflectionClass;
use App\Exceptions\AppException;
use App\Entity\Commande;
use App\Entity\Client;

class GestionCommandeController {

    public function chercheUne(array $params) {
        // appel de la méthode find($id) de la classe Model adequate
        $repository = Repository::getRepository("App\Entity\Commande");
        $repositoryClient = Repository::getRepository("App\Entity\Client");
        $ids = $repository->findIds();
        $params['lesId'] = $ids;
        if (array_key_exists('id', $params)) {
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $uneCommande = $repository->find($id);
            if ($uneCommande) {
                $unClient = $repositoryClient->findClientCommande($id);
                $params['unClient'] = $unClient;
                $params['uneCommande'] = $uneCommande;
            } else {
                $params['message'] = "Commande " . $id . " inconnu";
            }
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName() . "/uneCommande.html.twig");
        \Tools\MyTwig::afficheVue($vue, $params);
    }

    public function chercheToutes() {
        $modele = new GestionCommandeModel();
        $commandes = $modele->findAll();
        if ($commandes) {
            $r = new \ReflectionClass($this);
            include_once PATH_VIEW . str_replace('Controller', 'View', $r->getShortName() . "/plusieursCommandes.php");
        } else {
            throw new AppException("Aucune comman à afficher");
        }
    }
}
