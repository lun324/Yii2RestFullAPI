<?php

namespace app\models;

use app\components\base\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "Phone".
 *
 * @property integer $id
 * @property integer $clientId
 * @property string $phone
 *
 * @property Client $client
 */
class Phone extends BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Phone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['clientId'], 'integer'],
            [['phone'], 'string', 'max' => 13]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'clientId' => 'Client ID',
            'phone' => 'Phone',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'clientId']);
    }
}
