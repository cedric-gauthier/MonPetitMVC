<?php
declare (strict_types=1);
namespace Tools;

use PDO;
use App\Entity\Commande;
use App\Entity\Client;
use Exceptions;
use App\Exceptions\AppException;

abstract class Repository{
    private string $classNameLong;
    private string $classNamespace;
    private string $table;
    private PDO $connexion;
    
    private function __construct(string $entity) {
        $tablo = explode("\\", $entity);
        $this->table = array_pop($tablo);
        $this->classNamespace = implode("\\", $tablo);
        $this->classNameLong = $entity;
        $this->connexion = Connexion::getConnexion();
    }
    
    public static function getRepository(string $entity) : Repository {
        $repositoryName = str_replace('Entity', 'Repository', $entity) . 'Repository';
        $repository = new $repositoryName($entity);
        return $repository;
    }
    
    public function findAll() : array {
        $sql = "select * from " . $this->table;
        $lignes = $this->connexion->query($sql);
        $lignes->setFetchMode(PDO::FETCH_CLASS, $this->classNameLong, null);
        return $lignes->fetchAll();
    }
    
    public function findIds() : array {
        try {
            $unObjetPdo = Connexion::getConnexion();
            $sql = "select id from " . $this->table;
            $lignes = $unObjetPdo->query($sql);
            if ($lignes->rowCount() > 0) {
                $t = $lignes->fetchAll(PDO::FETCH_ASSOC);
                return $t;
            } else {
                throw new AppException('Aucun client trouvé');
            }
        } catch (PDOException) {
            throw new AppException("Erreur technique inattendue");
        }
    }
    
    public function find(int $id): ?object {
        try {
            $unObjetPdo = Connexion::getConnexion();
            $sql = "select * from " . $this->table . " where id=:id";
            $ligne = $unObjetPdo->prepare($sql);
            $ligne->bindValue(':id', $id, PDO::PARAM_INT);
            $ligne->execute();
            return $ligne->fetchObject($this->classNameLong);
        } catch (Exception $ex) {
            throw new AppException("Erreur technique inattendue");
        }
    }
    
    public function findClientCommande(int $id): Client {
        try {
            $unObjetPdo = Connexion::getConnexion();
            $sql = "select client.id as idClient "
                    . "commande.dateCde as dateCde "
                    . "commande.noFacture as noFacture "
                    . "client.nom as nomCli "
                    . ""
                    . "from client "
                    . "inner join commande "
                    . "on client.id = commande.idclient "
                    . "where commande.id=:id";
            $ligne = $unObjetPdo->prepare($sql);
            $ligne->bindValue(':id', $id, PDO::PARAM_INT);
            $ligne->execute();
            return $ligne->fetchObject(Client::class);
        } catch (Exception $ex) {
            throw new AppException("Erreur technique inattendue");
        }
    }
    
    public function __call(string $methode, array $params) :array{
        if (preg_match("#^findBy#", $methode)){
            return $this->traiteFindBy($methode, array_values($params[0]));
        }
    }
    
    private function traiteFindBy($methode, $params){
        $criteres = str_replace("findBy", "", $methode);
        $criteres = explode("_and_", $criteres);
        if (count($criteres) > 0 ){
            $sql = 'select * from ' . $this->table . " where ";
            $pasPremier = false;
            foreach ($criteres as $critere){
                if ($pasPremier){
                    $sql .= ' and ';
                }
                $sql .= $critere . " = ? ";
                $pasPremier = true;
            }
            $lignes = $this->connexion->prepare($sql);
            $lignes->execute($params);
            $lignes->setFetchMode(PDO::FETCH_CLASS, $this->classNameLong, null);
            return $lignes->fetchAll();
        }
    }
    
    public function findColumnDistinctValues(string $colonne) :array {
        $sql = "select distinct " . $colonne . " libelle from " . $this->table . " order by 1";
        $tab = $this->connexion->query($sql)->fetchAll(PDO::FETCH_COLUMN);
        return $tab;
    }
}


