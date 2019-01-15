<?php


require_once __DIR__ . '/../entity/Artikel.php';
require_once __DIR__ . '/../entity/Bestellartikel.php';
require_once __DIR__ . '/../entity/Bestellposition.php';
require_once __DIR__ . '/../entity/Bestellung.php';
require_once __DIR__ . '/../entity/Client.php';
require_once __DIR__ . '/../entity/Feedback.php';
require_once __DIR__ . '/../entity/Termin.php';


class PopulateArray
{
	
	/**
	 * @param Artikel $artikel
	 * @return array
	 */
	public static function populateArtikelArray(Artikel $artikel): array {
		return ['nummer' => $artikel->getNummer(),
			'name' => $artikel->getName(),
			'kg_price' => $artikel->getKgPrice(),
			'stueck_gewicht' => $artikel->getStueckGewicht(),
			'gewicht_1' => $artikel->getGewicht1(),
			'gewicht_2' => $artikel->getGewicht2(),
			'gewicht_3' => $artikel->getGewicht3(),
			'gewicht_4' => $artikel->getGewicht4(),
			'stueckzahl_1' => $artikel->getStueckzahl1(),
			'stueckzahl_2' => $artikel->getStueckzahl2(),
			'stueckzahl_3' => $artikel->getStueckzahl3(),
			'stueckzahl_4' => $artikel->getStueckzahl4(),];
	}
	
	public static function populateClientArray(Client $client): array {
		return ['vorname' => $client->getVorname(),
			'name' => $client->getName(),
			'adresse' => $client->getAdresse(),
			'ort_id' => $client->getOrtId(),
			'tel' => $client->getTel(),
			'natel' => $client->getNatel(),
			'email' => $client->getEmail(),
			'personen' => $client->getPersonen(),
			'siedfleisch' => $client->getSiedfleisch(),
			'besonderes' => $client->getBesonderes(),
			'id' => $client->getId(),
			'deleted_at' => $client->getDeletedAt(),];
	}
	
	public static function populateTerminArray(Termin $termin): array {
		return [
			'id' => $termin->getId(),
			'datum' => $termin->getDatum(),
		];
	}
	
	public static function populateBestellungArray(Bestellung $bestellung): array {
		return [
			'id' => $bestellung->getId(),
			'kunde_id' => $bestellung->getKundeId(),
			'datum' => $bestellung->getDate(),
		];
	}
	
	public static function populateBestellpositionArray(Bestellposition $position): array {
		return [
			'id' => $position->getId(),
			'bestellung_id' => $position->getBestellungId(),
			'bestell_artikel_id' => $position->getBestellArtikelId(),
			'anzahl_paeckchen' => $position->getAnzahlPaeckchen(),
			'gewicht' => $position->getGewicht(),
			'kommentar' => $position->getKommentar(),
		];
	}
	
	
}