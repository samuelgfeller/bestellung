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
        $client->setVorname( Helper::ckVal($data['vorname']));
        $client->setName(Helper::ckVal($data['name']));
        $client->setAdresse(Helper::ckVal($data['adresse']));
        $client->setOrtId(Helper::ckVal($data['ort_id']));
        $client->setTel(Helper::ckVal($data['tel']));
        $client->setNatel(Helper::ckVal($data['natel']));
        $client->setEmail(Helper::ckVal($data['email']));
        $client->setPersonen(Helper::ckVal($data['personen']));
        $client->setSiedfleisch(Helper::ckVal($data['siedfleisch']));
        $client->setBesonderes(Helper::ckVal($data['besonderes']));
        $client->setId(Helper::ckVal($data['id']));
        $client->setDeletedAt(Helper::ckVal($data['deleted_at']));
        return $client;
    }


    /**
     * @param $data
     * @return Bestellartikel
     */
    public static function populateBestellArtikel($data){
        $artikel = new Bestellartikel();
        $artikel->setBestellArtikelId(Helper::ckVal($data['bestell_artikel_id']));
        $artikel->setArtikelId(Helper::ckVal($data['artikel_id']));
        $artikel->setNummer(Helper::ckVal($data['nummer']));
        $artikel->setName(Helper::ckVal($data['name']));
        $artikel->setKgPrice(Helper::ckVal($data['kg_price']));
        $artikel->setGewicht(Helper::ckVal($data['gewicht']));
        $artikel->setVerfuegbar(Helper::ckVal($data['verfuegbar']));
        $artikel->setDatum(Helper::ckVal($data['datum']));
        $artikel->setAvgWeight(Helper::ckVal($data['avgWeight']));
        return $artikel;
    }

    /**
     * @param $data
     * @return Bestellposition
     */
    public static function populateBestellPosition($data) {
        $position = new Bestellposition();
        $position->setId(Helper::ckVal($data['id']));
        $position->setBestellungId(Helper::ckVal($data['bestellung_id']));
        $position->setBestellArtikelId(Helper::ckVal($data['bestell_artikel_id']));
        $position->setAnzahlPaeckchen(Helper::ckVal($data['anzahl_paeckchen']));
        $position->setGewicht(Helper::ckVal($data['gewicht']));
        $position->setKommentar(Helper::ckVal($data['kommentar']));
        return $position;
    }

    public static function populateBestellung($data) {
        $bestellung = new Bestellung();
        $bestellung->setId(Helper::ckVal($data['id']));
        $bestellung->setKundeId(Helper::ckVal($data['datum']));
        $bestellung->setDate(Helper::ckVal($data['kunde_id']));
        return $bestellung;
    }

    public static function populateTermin($data) {
        $termin = new Termin();
        $termin->setId(Helper::ckVal($data['id']));
        $termin->setDatum(Helper::ckVal($data['datum']));
        return $termin;
    }

    /**
     * @param $data
     * @return Artikel
     */
    public static function populateArtikel($data) {
        $artikel = new Artikel();
        $artikel->setId(Helper::ckVal($data['id']));
        $artikel->setNummer(Helper::ckVal($data['nummer']));
        $artikel->setName(Helper::ckVal($data['name']));
        $artikel->setKgPrice(Helper::ckVal($data['kg_price']));
        $artikel->setStueckGewicht(Helper::ckVal($data['stueck_gewicht']));
        $artikel->setGewicht1(Helper::ckVal($data['gewicht_1']));
        $artikel->setGewicht2(Helper::ckVal($data['gewicht_2']));
        $artikel->setGewicht3(Helper::ckVal($data['gewicht_3']));
        $artikel->setStueckzahl1(Helper::ckVal($data['stueckzahl_1']));
        $artikel->setStueckzahl2(Helper::ckVal($data['stueckzahl_2']));
        $artikel->setStueckzahl3(Helper::ckVal($data['stueckzahl_3']));
        return $artikel;
    }
}