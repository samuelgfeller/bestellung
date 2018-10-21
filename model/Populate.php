<?php

class Populate {
    /**
     * @param $data
     * @return Ort
     */
    public static function populateOrt($data) {
        $ort = new Ort();
        $ort->setOrt($data['ort'] ?? null);
        $ort->setPLZ($data['PLZ'] ?? null);
        $ort->setId($data['id'] ?? null);
        return $ort;
    }

    /**
     * @param $data
     * @return Client
     */
    public static function populateClient($data) {
        $client = new Client();
        $client->setVorname($data['vorname'] ?? null);
        $client->setName($data['name'] ?? null);
        $client->setAdresse($data['adresse'] ?? null);
        $client->setOrtId($data['ort_id'] ?? null);
        $client->setTel($data['tel'] ?? null);
        $client->setNatel($data['natel'] ?? null);
        $client->setEmail($data['email'] ?? null);
        $client->setPersonen($data['personen'] ?? null);
        $client->setSiedfleisch($data['siedfleisch'] ?? null);
        $client->setBesonderes($data['besonderes'] ?? null);
        $client->setId($data['id'] ?? null);
        $client->setDeletedAt($data['deleted_at'] ?? null);
//        $client->setNummer($data['nummer']?? null);
        return $client;
    }


    /**
     * @param $data
     * @return Bestellartikel
     */
    public static function populateArtikel($data) {
        $artikel = new Bestellartikel();
        $artikel->setBestellArtikelId($data['ba_id'] ?? null);
        $artikel->setArtikelId($data['artikel_id'] ?? null);
        $artikel->setNummer($data['nummer'] ?? null);
        $artikel->setName($data['name'] ?? null);
        $artikel->setKgPrice($data['kg_price'] ?? null);
        $artikel->setGewicht($data['gewicht'] ?? null);
        $artikel->setVerfuegbar($data['verfuegbar'] ?? null);
        return $artikel;
    }

    /**
     * @param $data
     * @return Bestellosition
     */
    public static function populatePosition($data) {
        $position = new Bestellosition();
        $position->setId($data['id'] ?? null);
        $position->setWeight($data['weight'] ?? null);
        $position->setPrice($data['price'] ?? null);
        $position->setRechnungId($data['rechnung_id'] ?? null);
        $position->setArtikelId($data['artikel_id'] ?? null);
        return $position;
    }

    public static function populateRechnung($data) {
        $rechnung = new Bestellung();
        $rechnung->setId($data['id'] ?? null);
        $rechnung->setDatum($data['datum'] ?? null);
        $rechnung->setClientId($data['kunde_id'] ?? null);
        $rechnung->setBezahlt($data['bezahlt'] ?? null);
        $rechnung->setKommentar($data['kommentar'] ?? null);
        return $rechnung;
    }
}