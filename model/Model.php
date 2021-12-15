<?php

require_once 'lib/DbSimple/Generic.php';

abstract class Model
{
    private $db;
    private $id;
    private $data = [];
    private $data_changed = false;

    /**
     * @param int|null $id
     */
    public function __construct(?int $id)
    {
        $db_class = $_ENV['DB_CLASS'];

        if (file_exists("lib/{$db_class}.php")) {
            require_once "lib/{$db_class}.php";
        }

        try {
            $this->db = $db_class::getInstance();

        } catch (Exception $e) {
            trigger_error("Class {$db_class} doesn't exist!");
        }

        if ($id) {
            $this->id = $id;
            $this->fetchData();
        }
    }

    protected function db()
    {
        return $this->db;
    }

    /**
     * @return string
     */
    abstract static function getIdField(): string;

    /**
     * @return string
     */
    abstract static function getTable(): string;

    /**
     * @return void
     */
    private function fetchData()
    {
        $this->data = $this->db->selectRow('SELECT * FROM ?# WHERE ?# = ?d',
            static::getTable(), static::getIdField(), $this->id);
    }


    /**
     * @return bool
     */
    public function isExists()
    {
        $id = $this->db->selectCell('SELECT ?# FROM ?# where ?# = ?d',
            static::getIdField(), static::getTable(), static::getIdField(), $this->id);

        return !is_null($id);
    }


    /**
     * @param $key
     * @return mixed|null
     */
    public function __get($key)
    {
        if ($key === static::getIdField()) { // cast id to int
            return $this->data[$key] ? intval($this->data[$key]) : null;
        } else {
            return $this->data[$key] ?? null;
        }
    }

    /**
     * @param $key
     * @param $value
     * @return void
     */
    public function __set($key, $value)
    {
        if ($key === static::getIdField()) { // id is immutable
            return;
        }

        if (!isset($this->data[$key]) || ($this->data[$key] !== $value)) {
            $this->data[$key] = $value;
            $this->data_changed = true;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @return void
     */
    public function delete()
    {
        if ($this->isExists()) {
            $this->db->query('DELETE FROM ?# WHERE ?# = ?d', static::getTable(), static::getIdField(), $this->id);
        }
    }

    /**
     * @return void
     */
    public function save()
    {
        if ($this->isExists()) {
            if ($this->data_changed) {
                $this->db->query("UPDATE ?# SET ?a WHERE ?# = ?d",
                    static::getTable(), $this->data, static::getIdField(), $this->id);
            }
        } else {
            $result = $this->db->query("INSERT INTO ?# (?#) VALUES (?a)",
                static::getTable(), array_keys($this->data), array_values($this->data));
        }

    }
}