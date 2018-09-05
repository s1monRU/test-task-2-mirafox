<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 9:09 PM
 */

/**
 * Class EmulationModel Модель эмуляции тестирования
 */
class EmulationModel
{
    /**
     * @var int Минимальное значение сложности
     */
    public $difficultyFrom;

    /**
     * @var int Максимальное значение сложности
     */
    public $difficultyTo;

    /**
     * @var int Интеллект тестируемого
     */
    public $mind;

    /**
     * EmulationModel конструктор.
     * @param int $difficultyFrom
     * @param int $difficultyTo
     * @param int $mind
     * @throws HttpException
     */
    public function __construct(int $difficultyFrom, int $difficultyTo, int $mind)
    {
        $this->difficultyFrom = $difficultyFrom;
        $this->difficultyTo = $difficultyTo;
        $this->mind = $mind;

        if(!$this->validate()) {
            throw new HttpException(400, 'Bad Values');
        }
    }

    /**
     * Эмулирование теста
     * @param $mind
     * @param QuestionModel $questionModel
     * @return array
     * @throws HttpException
     */
    public function emulate($mind, QuestionModel $questionModel)
    {
        $questions = $questionModel->getQuestions();

        $answeredQuestionsIds = [];
        $correctAnswers = 0;

        array_walk($questions, function(&$question) use ($mind, &$answeredQuestionsIds, &$correctAnswers) {
            $question['answer'] = $this->checkAnswer($mind, $question['difficulty']);

            if($question['answer']) {
                $correctAnswers++;
            }

            $answeredQuestionsIds[] = $question['id'];
        });

        $questionModel->updateUsedCount($answeredQuestionsIds);

        return ['results' => $questions, 'correct' => $correctAnswers, 'total' => $questionModel->questionsPerTest];
    }

    /**
     * Проверка ответа на корректность
     * @param $mind int интеллект
     * @param $difficulty int сложность вопроса
     * @return bool Результат ответа
     */
    public function checkAnswer($mind, $difficulty)
    {
        if ($difficulty === 100) {
            return false;
        }
        $weight = $mind + $difficulty;
        return $mind >= rand(1, $weight);
    }

    /**
     * Валидация введенных данных
     * @return bool Результат валидации
     */
    protected function validate()
    {
        if($this->difficultyFrom < 0 || $this->difficultyFrom > 100) {
            return false;
        }

        if($this->difficultyTo < 0 || $this->difficultyTo > 100) {
            return false;
        }

        if($this->mind < 0 || $this->mind > 100) {
            return false;
        }

        if($this->difficultyTo < $this->difficultyFrom){
            return false;
        }

        return true;
    }
}