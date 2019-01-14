<?php

require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../service/PopulateObject.php';
require_once __DIR__ . '/../service/DataManagement.php';

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
        $client = PopulateObject::populateClient($clientArr);
        return $client;
    }


    /**
     * @return array alle Kunden
     */
    public static function all($order='ASC') {
        $clients = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM kunde where deleted_at is null ORDER BY id '.$order);
        $total_records = mysqli_num_rows($result);
        while($client = $result->fetch_object()) {
            $params = [
                "vorname" => $client->vorname,
                "name" =>$client->name,
                "adresse" =>$client->adresse,
                "ort_id" =>$client->ort_id,
                "email" =>$client->email,
                "tel" =>$client->tel,
                "natel" =>$client->natel,
                "personen" =>$client->personen,
                "siedfleisch" =>$client->siedfleisch,
                "besonderes" =>$client->besonderes,
                "id" =>$client->id,
//                "nummer" =>$client->nummer
            ];
            $clients[] = PopulateObject::populateClient($params);
        }
        return  [
            'clients' => $clients,
            'total_records' => $total_records
        ];
    }

    /**
     * @param $start_from
     * @param $record_per_page
     * @param $order
     * @return array
     */
    public static function allPagination($start_from,$record_per_page,$order='ASC') {
        $clients = [];
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM kunde where deleted_at is null ORDER BY id '.$order.' LIMIT '.$start_from.', '.$record_per_page);
        while($client = $result->fetch_object()) {
            $params = [
                "vorname" => $client->vorname,
                "name" =>$client->name,
                "adresse" =>$client->adresse,
                "ort_id" =>$client->ort_id,
                "email" =>$client->email,
                "tel" =>$client->tel,
                "natel" =>$client->natel,
                "personen" =>$client->personen,
                "siedfleisch" =>$client->siedfleisch,
                "besonderes" =>$client->besonderes,
                "id" =>$client->id,
//                "nummer" =>$client->nummer
            ];
            $clients[] = PopulateObject::populateClient($params);
        }
        return  $clients;
    }


    /**
     * @param $id
     * @return array
     */
    public static function findDeleted($id): array {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM kunde WHERE id ='.$id);
        $clientArr = $result->fetch_assoc();
        $client=PopulateObject::populateClient($clientArr);
        //If client is deleted
        if($client->getDeletedAt()){
            return ['client' => $client, 'deleted_at' => true];
        }
        return ['client' => $client, 'deleted_at' => false];
    }

    /**
     * Updates one Client
     * @param $client
     * @return int client id (last insert id)
     */
    public static function upd($client){
        $db = Db::instantiate();
        $values = Client::getQueryData($client);
        $query = 'UPDATE kunde SET ' . implode(",", $values). ' WHERE id='.$client->getId();
        $result = $db->query($query);
        Db::checkConnection($result,$query);
        return true;
    }

    /**
     * Adds a client to database
     * @param $client
     * @return int last insert id
     */
    public static function add($client){
        $db = Db::instantiate();
        $values = Client::getQueryData($client);
        $query = 'INSERT INTO kunde SET ' . implode(",", $values);
        $result = $db->query();
        Db::checkConnection($result,$query);
        $last_id = $db->insert_id;
        return $last_id;
    }

    /**
     * @param $inputVal
     * @return array|bool|null
     */
    public static function getSearchResult($inputVal) {
        $db = Db::instantiate();
        $result = $db->query("SELECT * FROM kunde WHERE deleted_at is null and (name LIKE '%".$inputVal."%' OR vorname LIKE '%".$inputVal."%')");
        /*OR nummer LIKE '%".$inputVal."%'*/
        $clients=null;
        while ($clientArr = $result->fetch_assoc()) {
            $client = PopulateObject::populateClient($clientArr);
            $clients[] = $client;
        }
        if (!empty($clients)){
            return $clients;
        }
        return false;
    }

    /**
     * @param $id
     */
    public static function del($id) {
        $db = Db::instantiate();
        $query = 'UPDATE kunde SET deleted_at=now() WHERE id='.$id;
        $sql=$db->query($query);
        Db::checkConnection($sql,$query);
    }

    public static function findByName($vorname,$name) {
        $db = Db::instantiate();
        $result = $db->query('SELECT * FROM kunde WHERE deleted_at is null and vorname="'.$vorname.'" AND name="'.$name.'";');
        if ($result->num_rows>0){
            $clientArr = $result->fetch_assoc();
            $client=PopulateObject::populateClient($clientArr);
            return $client;
        }else{
            return false;
        }
    }

    /**
     * @param $client
     * @return array all client data as array
     */
    public static function getDataAsArray(Client $client) {
        $data=[
            'vorname' => $client->getVorname(),
            'name' => $client->getName(),
            'adresse' => $client->getAdresse(),
            'ort_id' => $client->getOrtId(),
            'email' => $client->getEmail(),
            'tel' => $client->getTel(),
            'natel' =>$client->getNatel(),
            'personen' =>  $client->getPersonen(),
            'siedfleisch' =>$client->getSiedfleisch(),
            'besonderes' => $client->getBesonderes(),
            'id' => $client->getId(),
//            'nummer' => $client->getNummer(),
            ];
        return $data;
    }

    /**
     * Creates sql query to edit or insert data inside database.
     * Like foo="test"
     * @param $client
     * @return array all entries for the sql syntax
     */
    public static function getQueryData($client) {
        $data=Client::getDataAsArray($client);
        $values=[];
        if( !empty($data['vorname'])){
            $values[]= "vorname='".$data['vorname']."'";
        }else{
            $values[]= "vorname=NULL";
        }
        if( !empty($data['name'])){
            $values[]= "name='".$data['name']."'";
        }else{
            $values[]= "name=NULL";
        }
        if( !empty($data['adresse'])){
            $values[]= "adresse='".$data['adresse']."'";
        }else{
            $values[]= "adresse=NULL";
        }
        if( !empty($data['ort_id'])){
            $values[]= "ort_id=".$data['ort_id'];
        }else{
            $values[]= "ort_id=NULL";
        }
        if( !empty($data['tel'])){
            $values[]= "tel='".$data['tel']."'";
        }else{
            $values[]= "tel=NULL";
        }
        if( !empty($data['natel'])){
            $values[]= "natel='".$data['natel']."'";
        }else{
            $values[]= "natel=NULL";
        }
        if( !empty($data['email'])){
            $values[]= "email='".$data['email']."'";
        }else{
            $values[]= "email=NULL";
        }
        if( !empty($data['personen'])){
            $values[]= "personen='".$data['personen']."'";
        }else{
            $values[]= "personen=NULL";
        }
        if( !empty($data['siedfleisch'])){
            $values[]= "siedfleisch='".$data['siedfleisch']."'";
        }else{
            $values[]= "siedfleisch=NULL";
        }
        if( !empty($data['besonderes'])){
            $values[]= "besonderes='".$data['besonderes']."'";
        }else{
            $values[]= "besonderes=NULL";
        }
/*        if( !empty($data['nummer'])){
            $values[]= "nummer='".$data['nummer']."'";
        }else{
            $values[]= "nummer=NULL";
        }*/
        return $values;
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
