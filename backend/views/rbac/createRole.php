<?php
/**
 * @var $this \yii\web\View
 * @var $model \backend\models\Role
 * @var $permissions array 权限的名称组成的数组
 */


use yii\helpers\Url;
use yii\bootstrap\Html;
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'desc');
echo \yii\helpers\Html::activeCheckboxList($model,'permissions',$permissions);
//echo $form->field($model,'permissions')->checkboxList($permissions);
echo Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
