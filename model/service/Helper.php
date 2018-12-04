<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 04.12.2018
 * Time: 10:52
 */

class Helper {
    /**
     * If a word is in the
     * @param array $myArray
     * @param $word
     * @return bool
     */
    public static function checkIfArticleCorresponds(array $orderedArr, $ba_id) {
        foreach ($orderedArr as $position) {
            if ($position->getBestellArtikelId() == $ba_id) {
                return true;
            }
        }
        return false;
    }
}