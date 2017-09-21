<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "address".
 *
 * @property integer $id
 * @property string $name
 * @property integer $province
 * @property integer $city
 * @property integer $town
 * @property string $tel
 * @property integer $status
 */
class Address extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'address';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province', 'city', 'town','status'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['tel'], 'string', 'max' => 20],
            ['address','string'],
            [['province', 'city', 'town'],'compare','compareValue'=>0,'operator'=>'>']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => '收货人地址',
            'tel' => '手机号',
            'status' => '默认地址',
            'address'=>'详细地址'
        ];
    }
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)){
            return false;
        }
        $this->user_id=Yii::$app->user->identity->id;
        if($this->status){
            self::updateAll(['status'=>0],['user_id'=>$this->user_id]);
        }
        return true;
    }
    public function getAddress($name){
        return $this->hasOne(Locations::className(),['id'=>$name])->one();
    }
}
