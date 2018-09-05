<?php
/**
 * Created by PhpStorm.
 * User: Ivan Semenov
 * Date: 8/30/2018
 * Time: 8:52 PM
 */

/**
 * Class DefaultController - Базовый контроллер, единственный, так как мало функционала
 */
class DefaultController
{
    /**
     * DefaultController constructor.
     * @param null $dataFromClient Данные с клиентской части
     */
    public function __construct($dataFromClient = null)
    {
        if(is_array($dataFromClient)) {
            foreach ($dataFromClient as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Сеттер для данных
     * @param $name string Ключ массива
     * @param $value string Значение
     */
    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    /**
     * Заглушка на случай, если action не выбран
     */
    public function index()
    {
        return;
    }

    /**
     * Запуск эмулятора тестирования
     * @return string Результаты тестирования
     * @throws HttpException Ошибка обработки данных
     */
    public function emulate()
    {
        $emulator = new EmulationModel($this->difficultyFrom, $this->difficultyTo, $this->mind);
        $questionModel = new QuestionModel($this->difficultyFrom, $this->difficultyTo);
        $historyModel = new HistoryModel();

        $emulate = $emulator->emulate($this->mind, $questionModel);
        $historyModel->addTestResults($emulator, $emulate['correct']);

        return json_encode($emulate);
    }

    /**
     * Показывает данные из истории
     * @return string JSON данных из истории
     */
    public function showHistory()
    {
        $history = new HistoryModel();
        return json_encode($history->show());
    }
}