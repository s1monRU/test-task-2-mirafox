<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 1:36 AM
 */

/**
 * Trait ConfigurationTrait Содержит конфигурационные данные
 */
trait ConfigurationTrait
{
    public $config = [
        'database' => [
            'host' => 'localhost',
            'username' => 'root',
            'passwd' => '',
            'dbname' => 'mirafox-additional'
        ]
    ];
}