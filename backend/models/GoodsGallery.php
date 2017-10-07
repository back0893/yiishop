<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "goods_gallery".
 *
 * @property integer $id
 * @property integer $goods_id
 * @property string $path
 */
class GoodsGallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'goods_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['goods_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'goods_id' => '商品名id',
            'path' => '图片保存地址',
        ];
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->UnlinkGoods();
    }
    public function UnlinkGoods(){
        $format='@frontend/web/html/%s.html';
        $filePath=Yii::getAlias(sprintf($format,$this->goods_id));
        unlink($filePath);
    }
    public function afterDelete()
    {
        parent::afterDelete(); // TODO: Change the autogenerated stub
        try{
            $this->UnlinkGoods();
        }
        catch (\Exception $e){}
    }
}
