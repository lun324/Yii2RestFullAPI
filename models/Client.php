<?php

namespace app\models;

use app\components\base\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "Client".
 *
 * @property integer $id
 * @property string $firstName
 * @property string $lastName
 *
 * @property Phone[] $phones
 */
class Client extends BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Client';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'string', 'max' => 60]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstName' => 'First Name',
            'lastName' => 'Last Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPhones()
    {
        return $this->hasMany(Phone::className(), ['clientId' => 'id']);
    }
}
