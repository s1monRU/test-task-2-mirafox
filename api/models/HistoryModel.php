<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 9:03 PM
 */

/**
 * Class HistoryModel Модель истории
 */
class HistoryModel extends Model
{
    /**
     * @var int Лимит выводимых данных
     */
    public $limit = 1000;

    /**
     * Показывает крайние записи из истории
     * @return array Данные из истории
     */
    public function show()
    {
        $result = $this->query('
            SELECT 
                *
            FROM
              `history`
            ORDER BY 
              `id` DESC 
            LIMIT '. $this->limit
        );

        $tests = [];
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            $tests[$i]['id'] = $row['id'];
            $tests[$i]['mind'] = $row['mind'];
            $tests[$i]['minDifficulty'] = $row['min_difficulty'];
            $tests[$i]['maxDifficulty'] = $row['max_difficulty'];
            $tests[$i]['result'] = $row['result'];
            $i++;
        }

        return $tests;
    }

    /**
     * Запись данных в историю о прошедшем тесте
     * @param EmulationModel $emulator
     * @param $result
     * @throws HttpException
     */
    public function addTestResults(EmulationModel $emulator, $result)
    {
        $insert = $this->query('
            INSERT INTO `history`
                        (`mind`, 
                         `min_difficulty`, 
                         `max_difficulty`, 
                         `result`)
            VALUES      ('. $this->real_escape_string($emulator->mind) .',
                         '. $this->real_escape_string($emulator->difficultyFrom) .',
                         '. $this->real_escape_string($emulator->difficultyTo) .',
                         '. $this->real_escape_string($result) .')
        ');

        if(!$insert) {
            throw new HttpException(500, 'Cannot change the way of history');
        }
    }
}