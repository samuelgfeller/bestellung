<?php

require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../Populate.php';

/**
 * Class Client
 */
class Client {
    /**
     * @var int client id
     */
    private $id;
    private $name;
    private $vorname;
    private $adresse;
    private $tel;
    private $natel;
    private $email;
    private $personen;
    private $siedfleisch;
    private $ort_id;
    private $besonderes;
    private $deleted_at;


    /**
     * @param $id id des Kunden
     * @return Client Kunde-Objekt
     */
    public static function find($id) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM kunde WHERE deleted_at is null and id ='.$id);
        $clientArr = $result->fetch_assoc();
        $client = populate::populateClient($clientArr);
        return $client;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getVorname() {
        return $this->vorname;
    }

    /**
     * @param mixed $vorname
     */
    public function setVorname($vorname) {
        $this->vorname = $vorname;
    }

    /**
     * @return mixed
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel) {
        $this->tel = $tel;
    }

    /**
     * @return mixed
     */
    public function getNatel() {
        return $this->natel;
    }

    /**
     * @param mixed $natel
     */
    public function setNatel($natel) {
        $this->natel = $natel;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPersonen() {
        return $this->personen;
    }

    /**
     * @param mixed $personen
     */
    public function setPersonen($personen) {
        $this->personen = $personen;
    }

    /**
     * @return mixed
     */
    public function getSiedfleisch() {
        return $this->siedfleisch;
    }

    /**
     * @param mixed $siedfleisch
     */
    public function setSiedfleisch($siedfleisch) {
        $this->siedfleisch = $siedfleisch;
    }

    /**
     * @return mixed
     */
    public function getOrtId() {
        return $this->ort_id;
    }

    /**
     * @param mixed $ort_id
     */
    public function setOrtId($ort_id) {
        $this->ort_id = $ort_id;
    }

    /**
     * @return mixed
     */
    public function getBesonderes() {
        return $this->besonderes;
    }

    /**
     * @param mixed $besonderes
     */
    public function setBesonderes($besonderes) {
        $this->besonderes = $besonderes;
    }

    /**
     * @return mixed
     */
    public function getDeletedAt() {
        return $this->deleted_at;
    }

    /**
     * @param mixed $deleted_at
     */
    public function setDeletedAt($deleted_at) {
        $this->deleted_at = $deleted_at;
    }


}
