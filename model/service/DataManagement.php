<?php

require_once __DIR__ . '/../../PdoConnection.php';

class DataManagement {

    /**
     * Insert data in database
     *
     * @param string $table
     * @param array $data assoc array. Key has to be the table name and value its value
     * @return bool|string
     */
    public static function insert(string $table, array $data) {
        if ($data) {
            if ($conn = PdoConnection::instance()) {
                $query = 'INSERT INTO ' . $table . ' (' . implode(', ', array_keys($data)) . ') 
            VALUES (:' . implode(', :', array_keys($data)) . ');';
                $stmt = $conn->prepare($query);
                $stmt->execute($data);
                return $conn->lastInsertId();
            }
            return false;
        }
        return false;
    }

    /**
     * Run a query for example update or delete
     *
     * @param string $query
     * @param array $args
     * @return PDO|bool
     */
    public static function run(string $query, array $args = []) {
        if ($conn = PdoConnection::instance()) {
            $stmt = $conn->prepare($query);
            $stmt->execute($args);
            return $conn;
        }
        return false;
    }

    /**
     * Select multiple data from database and return them as an array of associative arrays
     *
     * @param string $query
     * @param array $args arguments (? in query)
     * @return array|bool
     */
    public static function selectAndFetchAssocMultipleData(string $query, array $args = []) {
        if ($conn = PdoConnection::instance()) {
            $stmt = $conn->prepare($query);
            $stmt->execute($args);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    /**
     * Select single data from database and return it as an assoc array which is the value
     *
     * @param string $query
     * @param array $args
     * @return bool|mixed
     */
    public static function selectAndFetchSingleData(string $query, array $args = []) {
        if ($conn = PdoConnection::instance()) {
            $stmt = $conn->prepare($query);
            $stmt->execute($args);
            return $stmt->fetch();
        }
        return false;
    }

    public static function update(String $query, array $args = []) {
        if ($conn = PdoConnection::instance()) {
            $stmt = $conn->prepare($query);
            $stmt->execute($args);
            return $stmt;
        }
        return false;
    }

//if(!empty($stmt->errorInfo())){echo $stmt->errorInfo();}


}