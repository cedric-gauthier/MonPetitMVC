<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\GestionClientModel;
use ReflectionClass;
use App\Exceptions\AppException;
use App\Entity\Client;
use Tools\Repository;

class GestionClientController {

    public function chercheUn(array $params) {
        // appel de la méthode find($id) de la classe Model adequate
        $repository = Repository::getRepository("App\Entity\Client");
        // on récupère tous les id des clients
        $ids = $repository->findIds();
        // on place les id trouvés dans le tableau de paramètres à envoyer à la vue
        $params['lesId'] = $ids;
        // on test si l'id du client à chercher a été passé dans l'URL
        if (array_key_exists('id', $params)) {
            $id = filter_var(intval($params['id']), FILTER_VALIDATE_INT);
            $unClient = $repository->find($id);
            if ($unClient) {
                $params['unClient'] = $unClient;
            } else {
                $params['message'] = "Client " . $id . " inconnu";
            }
        }
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'View', $r->getShortName() . "/unClient.html.twig");
        \Tools\MyTwig::afficheVue($vue, $params);
    }

    public function chercheTous(): void {
        //$modele = new GestionClientModel();
        //$clients = $modele->findAll();
        // récupération d'un objet ClientRepository
        $repository = Repository::getRepository("App\Entity\Client");
        $clients = $repository->findAll();
        if ($clients) {
            $r = new ReflectionClass($this);
            $vue = str_replace('Controller', 'View', $r->getShortName() . "/tousClients.html.twig");
            \Tools\MyTwig::afficheVue($vue, array('clients' => $clients));
        } else {
            throw new AppException("Aucun client à afficher");
        }
    }

    public function creerClient(array $params) {
        $vue = "GestionClientView\\creerClient.html.twig";
        \Tools\MyTwig::afficheVue($vue, array());
    }

    public function enregistreClient($params) {
        try {
            // création de l'objet client à partir des données du formulaire
            $client = new Client($params);
            $modele = new GestionClientModel();
            $modele->enregistreClient($client);
            header('Location: index.php?c=GestionClient&a=chercheUn');
            exit;
        } catch (Exception) {
            throw new AppException("Erreur à l'enregistrement 'un nouveau client");
        }
    }
}
