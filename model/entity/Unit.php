<?php

class Unit implements JsonSerializable {
    private $id;
    private $name;
    private $short_name;
    private $equal_1000_gram;
    private $is_default;

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void {
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
    public function setName($name): void {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getShortName() {
        return $this->short_name;
    }

    /**
     * @param mixed $short_name
     */
    public function setShortName($short_name): void {
        $this->short_name = $short_name;
    }

    /**
     * @return mixed
     */
    public function getEqual1000Gram() {
        return $this->equal_1000_gram;
    }

    /**
     * @param mixed $equal_1000_gram
     */
    public function setEqual1000Gram($equal_1000_gram): void {
        $this->equal_1000_gram = $equal_1000_gram;
    }

    /**
     * @return mixed
     */
    public function getisDefault() {
        return $this->is_default;
    }

    /**
     * @param mixed $is_default
     */
    public function setIsDefault($is_default): void {
        $this->is_default = $is_default;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return array data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}