<?php

namespace app\components\base;

/**
 * Базовий контроллер для API
 * Базові методи і атрибути для контроллерів API виносити в цей клас
 * Class BaseController
 * @package app\components\base
 */
abstract class APIController extends BaseController
{
    /**
     * @var - Зберігає головну модель з якою працює контроллер API
     */
    protected $selfModel;
    /**
     * @var - Модель із запитами
     */
    protected $requestData;
    /**
     * @var - Контейнер для повернення даних
     */
    protected $dataContainerResponse;

    /**
     * Ініціалізує контроллер
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        $this->requestData = new BaseRequestData();
        $this->dataContainerResponse = new DataContainerResponse();

        return parent::__construct($id, $module, $config = []);
    }
}