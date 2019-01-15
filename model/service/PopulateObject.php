<?php

require_once __DIR__ . '/../entity/Artikel.php';
require_once __DIR__ . '/../entity/Bestellposition.php';
require_once __DIR__ . '/../entity/Bestellartikel.php';
require_once __DIR__ . '/../entity/Bestellung.php';
require_once __DIR__ . '/../entity/Client.php';
require_once __DIR__ . '/../entity/Termin.php';
require_once __DIR__ . '/../entity/Feedback.php';
require_once __DIR__ . '/../service/Helper.php';

class PopulateObject {

    /**
     * @param $data
     * @return Client
     */
    public static function populateClient($data) {
        $client = new Client();
        $client->setVorname( Helper::ckVal($data['vorname'] ?? null));
        $client->setName(Helper::ckVal($data['name'] ?? null));
        $client->setAdresse(Helper::ckVal($data['adresse'] ?? null));
        $client->setOrtId(Helper::ckVal($data['ort_id'] ?? null));
        $client->setTel(Helper::ckVal($data['tel'] ?? null));
        $client->setNatel(Helper::ckVal($data['natel'] ?? null));
        $client->setEmail(Helper::ckVal($data['email'] ?? null));
        $client->setPersonen(Helper::ckVal($data['personen'] ?? null));
        $client->setSiedfleisch(Helper::ckVal($data['siedfleisch'] ?? null));
        $client->setBesonderes(Helper::ckVal($data['besonderes'] ?? null));
        $client->setId(Helper::ckVal($data['id'] ?? null));
        $client->setDeletedAt(Helper::ckVal($data['deleted_at'] ?? null));
        return $client;
    }


    /**
     * @param $data
     * @return Bestellartikel
     */
    public static function populateBestellArtikel($data){
        $artikel = new Bestellartikel();
        $artikel->setBestellArtikelId(Helper::ckVal($data['bestell_artikel_id'] ?? null));
        $artikel->setArtikelId(Helper::ckVal($data['artikel_id'] ?? null));
        $artikel->setNummer(Helper::ckVal($data['nummer'] ?? null));
        $artikel->setName(Helper::ckVal($data['name'] ?? null));
        $artikel->setKgPrice(Helper::ckVal($data['kg_price'] ?? null));
        $artikel->setGewicht(Helper::ckVal($data['gewicht'] ?? null));
        $artikel->setVerfuegbar(Helper::ckVal($data['verfuegbar'] ?? null));
        $artikel->setDatum(Helper::ckVal($data['datum'] ?? null));
        $artikel->setAvgWeight(Helper::ckVal($data['avgWeight'] ?? null));
        return $artikel;
    }

    /**
     * @param $data
     * @return Bestellposition
     */
    public static function populateBestellPosition($data) {
        $position = new Bestellposition();
        $position->setId(Helper::ckVal($data['id'] ?? null));
        $position->setBestellungId(Helper::ckVal($data['bestellung_id'] ?? null));
        $position->setBestellArtikelId(Helper::ckVal($data['bestell_artikel_id'] ?? null));
        $position->setAnzahlPaeckchen(Helper::ckVal($data['anzahl_paeckchen'] ?? null));
        $position->setGewicht(Helper::ckVal($data['gewicht'] ?? null));
        $position->setKommentar(Helper::ckVal($data['kommentar'] ?? null));
        return $position;
    }

    public static function populateBestellung($data) {
        $bestellung = new Bestellung();
        $bestellung->setId(Helper::ckVal($data['id'] ?? null));
        $bestellung->setKundeId(Helper::ckVal($data['datum'] ?? null));
        $bestellung->setDate(Helper::ckVal($data['kunde_id'] ?? null));
        return $bestellung;
    }

    public static function populateTermin($data) {
        $termin = new Termin();
        $termin->setId(Helper::ckVal($data['id'] ?? null));
        $termin->setDatum(Helper::ckVal($data['datum'] ?? null));
        return $termin;
    }

    /**
     * @param $data
     * @return Artikel
     */
    public static function populateArtikel($data) {
        $artikel = new Artikel();
        $artikel->setId(Helper::ckVal($data['id'] ?? null));
        $artikel->setNummer(Helper::ckVal($data['nummer'] ?? null));
        $artikel->setName(Helper::ckVal($data['name'] ?? null));
        $artikel->setKgPrice(Helper::ckVal($data['kg_price'] ?? null));
        $artikel->setStueckGewicht(Helper::ckVal($data['stueck_gewicht'] ?? null));
        $artikel->setGewicht1(Helper::ckVal($data['gewicht_1'] ?? null));
        $artikel->setGewicht2(Helper::ckVal($data['gewicht_2'] ?? null));
        $artikel->setGewicht3(Helper::ckVal($data['gewicht_3'] ?? null));
        $artikel->setGewicht4(Helper::ckVal($data['gewicht_4'] ?? null));
        $artikel->setStueckzahl1(Helper::ckVal($data['stueckzahl_1'] ?? null));
        $artikel->setStueckzahl2(Helper::ckVal($data['stueckzahl_2'] ?? null));
        $artikel->setStueckzahl3(Helper::ckVal($data['stueckzahl_3'] ?? null));
        $artikel->setStueckzahl4(Helper::ckVal($data['stueckzahl_4'] ?? null));
        return $artikel;
    }
}