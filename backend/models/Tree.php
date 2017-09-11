<?php
namespace backend\models;
use yii\base\Model;

class Tree extends Model{
    public $intro;
    public function rules()
    {
        return [
            ['intro','required']
        ];
    }
}