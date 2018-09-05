<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 1:34 AM
 */

/**
 * Class Model Нет времени использовать ORM. Только SQL-запросы прямо в коде, только хардкор!
 */
abstract class Model extends MySQLi
{
    use ConfigurationTrait;

    /**
     * Model Конструктор.
     */
    public function __construct()
    {
        $host = $this->config['database']['host'];
        $username = $this->config['database']['username'];
        $passwd = $this->config['database']['passwd'];
        $dbname = $this->config['database']['dbname'];
        parent::__construct($host, $username, $passwd, $dbname);
    }
}