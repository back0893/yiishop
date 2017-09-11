<?php

namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "brand".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property string $logo
 * @property integer $sort
 * @property integer $status
 * @property UploadedFile $file
 */
class Brand extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $file;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'brand';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','intro','sort','status'],'required'],
            [['intro'], 'string'],
            [['sort', 'status'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['logo'], 'string', 'max' => 255],
            ['status','in','range'=>[-1,0,1]],
            ['name','unique','message'=>'已经被占用'],
            ['file','file','extensions'=>['jpg','png','git']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'intro' => '简介',
            'logo' => 'logo图像',
            'sort' => '排序',
            'status' => '状态',
        ];
    }
    public function saveImg(){
        $root=\Yii::getAlias('@webroot').'/';
        $path='logo/'.md5($this->name).'.'.$this->file->extension;
        $this->logo=$path;
        $this->file->saveAs($root.$path);
    }
    public function saveAll(){
        if($this->file){
            $this->saveImg();
        }
        return $this->save(false);
    }
    public static function getDropList(){
        $dropList=self::find()->select(['id','name'])->orderBy('id DESC')->asArray()->all();
        return ArrayHelper::map($dropList,'id','name');
    }
}
