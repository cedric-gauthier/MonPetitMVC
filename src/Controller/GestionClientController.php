<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\GestionClientModel;
use ReflectionClass;
use App\Exceptions\AppException;
use App\Entity\Client;
use Tools\Repository;
use Tools\MyTwig;

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
        MyTwig::afficheVue($vue, $params);
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
            MyTwig::afficheVue($vue, array('clients' => $clients));
        } else {
            throw new AppException("Aucun client à afficher");
        }
    }

    public function creerClient(array $params) {
        $vue = "GestionClientView\\creerClient.html.twig";
        MyTwig::afficheVue($vue, array());
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

    public function testFindBy(array $params): void {
        $repository = Repository::getRepository("App\Entity\Client");
        $parametres = array("cpCli" => "14000", "villeCli" => "Toulon");
        $clients = $repository->findBycpCli_and_villeCli($parametres);
        $r = new ReflectionClass($this);
        $vue = str_replace('Controller', 'view', $r->getShortName()) . "/tousClients.html.twig";
        MyTwig::afficheVue($vue, array('lesClients' => $clients));
    }

    public function rechercheClients(array $params): void {
        $repository = Repository::getRepository("App\Entity\Client");
        $titres = $repository->findColumnDistinctValues('titreCli');
        $cps = $repository->findColumnDistinctValues('cpCli');
        $villes = $repository->findColumnDistinctValues('villeCli');
        $paramsVue['titres'] = $titres;
        $paramsVue['cps'] = $cps;
        $paramsVue['villes'] = $villes;
        $criteresPrepares = $this->verifieEtPrepareCriteres($params);
        if (count($criteresPrepares) > 0) {
            $clients = $repository->findBy($params);
            $paramsVue['lesClients'] = $clients;
            foreach ($criteresPrepares as $valeur) {
                ($valeur != "Choisir...") ? ($criteres[] = $valeur) : (null);
            }
            $paramsVue['criteres'] = $criteres;
            $vue = "GestionClientView\\tousClients.html.twig";
            MyTwig::afficheVue($vue, $paramsVue);
        } else {
            $vue = "GestionClientView\\filtreClients.html.twig";
            MyTwig::afficheVue($vue, $paramsVue);
        }
    }

    private function verifieEtPrepareCriteres(array $params): array {
        $args = array(
        'titreCli' => array(
        'filter' => FILTER_VALIDATE_REGEXP | FILTER_SANITIZE_SPECIAL_CHARS,
        'flags' => FILTER_NULL_ON_FAILURE,
        'options' => array('regexp' => "/^(Monsieur|Madame|Mademoiselle)$/")
        ),
        'cpCli' => array(
        'filter' => FILTER_VALIDATE_REGEXP | FILTER_SANITIZE_SPECIAL_CHARS,
        'flags' => FILTER_NULL_ON_FAILURE,
        'options' => array('regexp' => "/[0-9]{5}/")
        ),
        'villeCli' => FILTER_SANITIZE_SPECIAL_CHARS,
        );
        $retour = filter_var_array($params, $args, false);
        if (isset($retour['titreCli'])|| isset($retour['cpCli']) || isset($retour['villeCli'])){
            $element = "Choisir...";
            while (in_array($element, $retour)){
                unset($retour[array_search($element, $retour)]);
            }
        }
        return $retour;
    }
}
