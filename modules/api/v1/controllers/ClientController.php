<?php

namespace app\modules\api\v1\controllers;

use app\components\base\APIController;
use app\components\base\interfaсes\RestFullAPIControllerMethods;

/**
 * Контролер сутності Клієнт
 * Class ClientController
 * @package app\modules\api\v1\controllers
 */
class ClientController extends APIController implements RestFullAPIControllerMethods
{
    /**
     * Ініціалізує контроллер
     * @param string $id
     * @param \yii\base\Module $module
     * @param array $config
     */
    public function __construct($id, $module, $config = [])
    {
        $this->selfModel = new \app\modelsMap\ClientMap();

        return parent::__construct($id, $module, $config = []);
    }

    /**
     * Повертає список клієнтів
     * @return mixed
     */
    public function actionList()
    {
        $list = $this->selfModel->getList();

        $this->dataContainerResponse->setData($list);

        return $this->dataContainerResponse->getData();
    }

    /**
     * Створити клієнта
     * @return mixed|string
     */
    public function actionCreate()
    {
        $this->selfModel->setScenario('create');
        $this->selfModel->setAttributes($this->requestData->getAttributes()->getData());
        $this->selfModel->create();

        $this->dataContainerResponse->setData($this->selfModel);

        return $this->dataContainerResponse->getData();
    }

    /**
     * Повернути клієнта по ідентифікатору
     * @param null $id - ідентифікатор клієнта
     * @return mixed|string
     */
    public function actionRead($id = null)
    {
        $this->selfModel->setScenario('read');

        $this->selfModel->id = $id;

        $result = $this->selfModel->read();

        $this->dataContainerResponse->setData($result);

        return $this->dataContainerResponse->getData();
    }

    /**
     * Оновити дані клієнта
     * @param null $id - ідентифікатор клієнта
     * @return mixed|string
     */
    public function actionUpdate($id = null)
    {
        $this->selfModel->setScenario('update');
        $this->selfModel->id = $id;
        $this->selfModel->setAttributes($this->requestData->getAttributes()->getData());
        $this->selfModel->update();

        $this->dataContainerResponse->setData($this->selfModel);

        return $this->dataContainerResponse->getData();
    }

    /**
     * Видалити клієнта по ідентифікатору
     * @param null $id -  - ідентифікатор клієнта
     * @return mixed|string
     */
    public function actionDelete($id = null)
    {
        $this->selfModel->setScenario('delete');

        $this->selfModel->id = $id;

        $this->selfModel->delete();

        $this->dataContainerResponse->setData($this->selfModel);

        return $this->dataContainerResponse->getData();
    }

    /**
     * Видалити всіх клієнтів
     * @return mixed|string
     */
    public function actionDelete_all()
    {
        $message = $this->selfModel->delete_all();

        $this->dataContainerResponse->setData($message);

        return $this->dataContainerResponse->getData();
    }
}
