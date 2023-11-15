<?php

declare(strict_types=1);

namespace App\Model;

use PDO;
use App\Entity\Client;
use Tools\Connexion;
use Exception;
use App\Exceptions\AppException;

class GestionClientModel {

    

    public function findAll(): array {
        $unObjetPdo = Connexion::getConnexion();
        $sql = "select * from CLIENT";
        $lignes = $unObjetPdo->query($sql);
        return $lignes->fetchAll(PDO::FETCH_CLASS, Client::class);
    }

    

    public function enregistreClient(Client $client) {
        try {
            $unObjetPdo = Connexion::getConnexion();
            $sql = "insert into client(titreCli, nomCli, prenomCli, adresseRue1Cli, adresseRue2Cli, cpCli, villeCli, telCli) "
                    . "values (:titreCli, :nomCli, :prenomCli, :adresseRue1Cli, :adresseRue2Cli, :cpCli, :villeCli, :telCli)";
            $s = $unObjetPdo->prepare($sql);
            $s->bindValue(':titreCli', $client->getTitreCli(), PDO::PARAM_STR);
            $s->bindValue(':nomCli', $client->getNomCli(), PDO::PARAM_STR);
            $s->bindValue(':prenomCli', $client->getPrenomCli(), PDO::PARAM_STR);
            $s->bindValue(':adresseRue1Cli', $client->getAdresseRue1Cli(), PDO::PARAM_STR);
            $s->bindValue(':adresseRue2Cli', ($client->getAdresseRue2Cli() == "") ? (null) : ($client->getAdresseRue2Cli()), PDO::PARAM_STR);
            $s->bindValue(':cpCli', $client->getCpCli(), PDO::PARAM_STR);
            $s->bindValue(':villeCli', $client->getVilleCli(), PDO::PARAM_STR);
            $s->bindValue(':telCli', $client->getTelCli(), PDO::PARAM_STR);
            $s->execute();
        } catch (PDOException) {
            throw new AppException("Erreur technique inattendue");
        }
    }
}
