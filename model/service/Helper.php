<?php
/**
 * Created by PhpStorm.
 * User: samue
 * Date: 04.12.2018
 * Time: 10:52
 */

class Helper {

    /**
     * @param $data
     * @return array
     */
    public static function getImportColAndValues($data) {
        $cols = [];
        $values = [];
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                if (is_string($value)) {
                    $values[] = '"' . $value . '"';
                    $cols[] = $key;
                } else if (is_bool($value) || is_int($value)) {
                    $values[] = $value;
                    $cols[] = $key;
                }
            }
        }
        return ['cols' => $cols,
            'values' => $values];
    }

    public static function prepareHtmlMailBody($positionDaten) {
        $body = '';
    }
}

