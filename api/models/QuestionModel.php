<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 9:03 PM
 */

/**
 * Class QuestionModel Вопрос
 */
class QuestionModel extends Model
{
    /**
     * @var int Количество вопросов в тесте
     */
    public $questionsPerTest = 40;

    /**
     * @var int Минимальная сложность вопроса
     */
    public $difficultyFrom;

    /**
     * @var int Максимальная сложность вопроса
     */
    public $difficultyTo;

    /**
     * QuestionModel Конструктор.
     * @param mixed ...$difficulties Полученные минимальная и максимальная сложности вопроса
     */
    public function __construct(...$difficulties)
    {
        $this->difficultyFrom = $difficulties[0];
        $this->difficultyTo = $difficulties[1];

        parent::__construct();
    }

    /**
     * Назначает сложность каждого вопроса в тесте
     * @return array Сложность каждого вопроса
     */
    public function getQuestionDifficulties()
    {
        $questionDifficulties = [];

        for ($i = 1; $i <= $this->questionsPerTest; $i++) {
            $questionDifficulties[] = rand($this->difficultyFrom, $this->difficultyTo);
        }

        return $questionDifficulties;
    }

    /**
     * Получает массив вопросов для теста
     * @return array Вопросы для теста
     */
    public function getQuestions()
    {
        $difficulties = $this->getQuestionDifficulties();
        $result = $this->query('
            SELECT 
                *
            FROM
              `questions`
            ORDER BY 
              `used` ASC 
            LIMIT '. $this->questionsPerTest
        );

        $i = 0;
        $questions = [];
        while ($row = $result->fetch_assoc()) {
            $questions[$i]['id'] = $row['id'];
            $questions[$i]['used'] = $row['used'];
            $questions[$i]['difficulty'] = $difficulties[$i];
            $i++;
        }

        return $questions;

    }

    /**
     * Обновляет данные о количестве использования вопроса
     * @param $ids
     * @return bool
     * @throws HttpException
     */
    public function updateUsedCount($ids)
    {
        $ids = implode(',', $ids);

        $update = $this->query('
            UPDATE 
                `questions`
            SET 
                `used`=`used`+1
            WHERE
              `id` IN ('. $ids .')
        ');

        if(!$update) {
            throw new HttpException(500, 'Cannot update used count');
        }
        return true;
    }
}