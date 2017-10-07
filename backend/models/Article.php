<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "article".
 *
 * @property integer $id
 * @property string $name
 * @property string $intro
 * @property integer $article_category_id
 * @property integer $sort
 * @property integer $create_time
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['intro','article_category_id','sort','name'],'required'],
            [['intro'], 'string'],
            ['name','unique'],
            [['article_category_id', 'sort', 'create_time'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['status'],'in','range'=>[-1,0,1]]
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
            'article_category_id' => '文章分类',
            'sort' => '排序',
            'status'=>'状态',
            'create_time' => '创建时间',
        ];
    }
    public function getCategory(){
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id'])->one();
    }
    public function behaviors()
    {
        return [
            'create_time'=>[
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'create_time',
                'updatedAtAttribute' => false,
            ]
        ];
    }
}
