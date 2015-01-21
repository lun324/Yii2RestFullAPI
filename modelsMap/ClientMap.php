<?php

namespace app\modelsMap;

use app\components\base\BaseModelMap;
use app\components\base\DataContainer;
use app\components\base\interfaсes\RestFullAPIModelMapMethods;
use app\models\Client;
use app\models\Phone;
use Yii;

/**
 * Модель Client
 * Дану сутність виніс в окрему модель не AR, в більшості випадків операції CRUD сутності не зв'язані
 * з однією таблицею. Легше розширювати, підається швидкому рефакторінгу та оптимізації.
 * Class MessageMap
 * @package app\modelsMap
 */
class ClientMap extends BaseModelMap implements RestFullAPIModelMapMethods
{
    /**
     * @var - Ідентифікатор запису
     */
    public $id;
    /**
     * @var - Ім'я клієнта
     */
    public $firstName;
    /**
     * @var - Прізвище клієнта
     */
    public $lastName;
    /**
     * Передавати масивом
     * @var - Телефони клієнтів
     */
    public $phones = [];

    /**
     * Атрибути з кодом для перекладу
     * Зберігається коди атрибутів
     * @return array - коди атрибутів
     */
    public function attributeLabels()
    {
        return [
            'id' => 'MODEL.CLIENT.ID',
            'firstName' => 'MODEL.CLIENT.FIRST_NAME',
            'lastName' => 'MODEL.CLIENT.LAST_NAME',
            'phones' => 'MODEL.CLIENT.PHONES',
        ];
    }

    /**
     * Карта валідації
     * Відповідний валідатор запускаєть при певному сценарії
     * @return array - валідатори
     */
    public function rules()
    {
        return [
            ['id', 'required', 'on' => ['read', 'update', 'delete']],
            [['firstName', 'lastName'], 'required', 'on' => ['create', 'update'],
                'message' => 'MODEL.CLIENT.ERROR.IS_EMPTY'],
            [['firstName', 'lastName'], 'string', 'max' => 60, 'on' => ['create', 'update'],
                'message' => 'MODEL.CLIENT.ERROR.NOT_STRING',
                'tooLong' => 'MODEL.CLIENT.ERROR.MAX_LENGTH_{max}'],
            ['phones', 'validatePhones', 'on' => ['create', 'update']],
            [['id', 'firstName', 'lastName', 'phones'], 'safe'],
        ];
    }

    /**
     * Створити сутність клієнт
     * @return $this|mixed
     * @throws \yii\base\InvalidParamException
     * @throws \yii\db\Exception
     */
    public function create()
    {
        if($this->validate()){
            $client = new Client();
            $client->firstName = $this->firstName;
            $client->lastName = $this->lastName;
            $client->save();

            $this->id = $client->id;

            $phoneRows = [];
            foreach($this->phones as $phone){
                $phoneRows[] = [
                    $client->id,
                    $phone,
                ];
            }

            Yii::$app->db
                ->createCommand()
                ->batchInsert(Phone::tableName(), ['clientId', 'phone'], $phoneRows)
                ->execute();
        }

        return $this;
    }

    /**
     * Повернути одного клієнта
     * @return $this|array|mixed|null|\yii\db\ActiveRecord
     * @throws \yii\base\InvalidParamException
     */
    public function read()
    {
        if($this->validate()){
            $client = Client::find()
                ->where('id = :id', [':id' => $this->id])
                ->asArray()
                ->with('phones')
                ->one();

            if(!$client){
                $this->addError('id', 'MODEL.CLIENT.ERROR.NO_CLIENT');

                return $this;
            }

            return $client;
        }

        return $this;
    }

    /**
     * Обновити дані клієнта
     * @return $this|mixed
     * @throws \yii\base\InvalidParamException
     * @throws \yii\db\Exception
     */
    public function update()
    {
        if($this->validate()){
            $client = Client::find()
                ->where('id = :id', [':id' => $this->id])
                ->with('phones')
                ->one();

            if(!$client){
                $this->addError('id', 'MODEL.CLIENT.ERROR.NO_CLIENT');

                return $this;
            }

            $client->firstName = $this->firstName;
            $client->lastName = $this->lastName;

            $client->save();

            $arrayPhones = [];
            foreach($client->phones as $phone){
                if(!in_array($phone->phone, $this->phones)){
                    $phone->delete();
                } else {
                    $arrayPhones[$phone->phone] = 1;
                }
            }

            $phoneRows = [];
            foreach($this->phones as $phone){
                if(empty($arrayPhones[$phone])){
                    $phoneRows[] = [
                        $client->id,
                        $phone,
                    ];
                }
            }

            if(!empty($phoneRows)){
                Yii::$app->db
                    ->createCommand()
                    ->batchInsert(Phone::tableName(), ['clientId', 'phone'], $phoneRows)
                    ->execute();
            }
        }

        return $this;
    }

    /**
     * Видалити клієнта
     * @return $this|mixed
     * @throws \yii\base\InvalidParamException
     */
    public function delete()
    {
        if($this->validate()){
            Phone::deleteAll('clientId = :clientId', [':clientId' => $this->id]);
            Client::deleteAll('id = :id', [':id' => $this->id]);
        }

        return $this;
    }

    /**
     * Видалити всіх клієнтів
     * @return array|mixed
     */
    public function delete_all()
    {
        Phone::deleteAll();
        Client::deleteAll();

        return ['message' => 'MODEL.CLIENT.MESSAGE.CLEAR_CLIENT'];
    }

    /**
     * Повертає всіх клієнтів
     * @param DataContainer $dataContainer - додаткові параметри (наприклад параметри фільтрування)
     * @return array|\yii\db\ActiveRecord[]
     * @throws \yii\base\InvalidParamException
     */
    public function getList(DataContainer $dataContainer = null)
    {
        if($this->validate()){
            $clients = Client::find()
                ->with('phones')
                ->asArray()
                ->all();

            return $clients;
        }

        return $this;
    }

    /**
     * Валідація номерів телефонів
     * @param $attribute
     * @param $params
     */
    public function validatePhones($attribute, $params)
    {
        foreach($this->{$attribute} as $phone){
            if(!preg_match("/^\+?\d{9,12}$/", $phone)){
                $this->addError($attribute, 'MODEL.CLIENT.ERROR.BAD_FORMAT_PHONE');
            }
        }
    }
}