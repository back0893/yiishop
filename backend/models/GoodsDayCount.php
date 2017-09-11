<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_day_count".
 *
 * @property integer $id
 * @property string $day
 * @property integer $count
 */
class GoodsDayCount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_day_count';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['day'], 'required'],
            [['day'], 'safe'],
            [['count'], 'integer'],
        ];
    }
    public static function getCount(){
        $day=date('Y-m-d');
        $count=self::findOne(['day'=>$day]);
        if($count){
            $count->count++;
        }
        else{
            $count=new self();
            $count->day=$day;
            $count->count=1;
        }
        $count->save();
        return $count->count;
    }

}
