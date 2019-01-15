<?php

class PopulateObject {

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
    public static function populateBestellArtikel($data){
        $artikel = new Bestellartikel();
        $artikel->setBestellArtikelId($data['bestell_artikel_id'] ?? null);
        $artikel->setArtikelId($data['artikel_id'] ?? null);
        $artikel->setNummer($data['nummer'] ?? null);
        $artikel->setName($data['name'] ?? null);
        $artikel->setKgPrice($data['kg_price'] ?? null);
        $artikel->setGewicht($data['gewicht'] ?? null);
        $artikel->setVerfuegbar($data['verfuegbar'] ?? null);
        $artikel->setDatum($data['datum'] ?? null);
        $artikel->setAvgWeight($data['avgWeight'] ?? null);
        return $artikel;
    }

    /**
     * @param $data
     * @return Bestellposition
     */
    public static function populateBestellPosition($data) {
        $position = new Bestellposition();
        $position->setId($data['id'] ?? null);
        $position->setBestellungId($data['bId'] ?? null);
        $position->setBestellArtikelId($data['ba_id'] ?? null);
        $position->setAnzahlPaeckchen($data['pAmount'] ?? null);
        $position->setGewicht($data['singleWeight'] ?? null);
        $position->setKommentar($data['kommentar'] ?? null);
        return $position;
    }

    public static function populateBestellung($data) {
        $bestellung = new Bestellung();
        $bestellung->setId($data['id'] ?? null);
        $bestellung->setKundeId($data['datum'] ?? null);
        $bestellung->setDate($data['kunde_id'] ?? null);
        return $bestellung;
    }

    public static function populateTermin($data) {
        $termin = new Termin();
        $termin->setId($data['id'] ?? null);
        $termin->setDatum($data['datum'] ?? null);
        return $termin;
    }

    /**
     * @param $data
     * @return Artikel
     */
    public static function populateArtikel($data) {
        $artikel = new Artikel();
        $artikel->setId($data['id'] ?? null);
        $artikel->setNummer($data['nummer'] ?? null);
        $artikel->setName($data['name'] ?? null);
        $artikel->setKgPrice($data['kg_price'] ?? null);
        $artikel->setStueckGewicht($data['stueck_gewicht'] ?? null);
        $artikel->setGewicht1($data['gewicht_1'] ?? null);
        $artikel->setGewicht2($data['gewicht_2'] ?? null);
        $artikel->setGewicht3($data['gewicht_3'] ?? null);
        $artikel->setStueckzahl1($data['stueckzahl_1'] ?? null);
        $artikel->setStueckzahl2($data['stueckzahl_2'] ?? null);
        $artikel->setStueckzahl3($data['stueckzahl_3'] ?? null);
        return $artikel;
    }
}