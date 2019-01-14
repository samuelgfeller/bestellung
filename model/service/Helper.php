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

    /**
     * Transform an assoc array of data to a string in the SQL-Query (set key = ?) and return it and the values
     *
     * @param array $data
     * @return array
     */
    public static function getUpdateStringAndValues(Array $data): array {
        $formattedData = [];
        $values = [];
        foreach ($data as $col => $val) {
            $formattedData[] = $col . ' = ?';
            $values[] = $val;
        }
        return [
            'updString' => $formattedData,
            'values' => $values,
        ];
    }

    /**
     * Put % around string for pdo like
     *
     * @param string $string
     * @return mixed
     */
    public static function getLikeString(string $string) {
        $len = strlen($string);
        $first = substr_replace($string, '%', 0, 0);
        return substr_replace($first, '%', $len + 1, 0);
    }

    /**
     * If the value is empty return null otherwise the value
     *
     * @param $val
     * @return null | $val
     */
    public static function ckVal($val) {
        return !empty($val) ? $val : null;
    }
}

