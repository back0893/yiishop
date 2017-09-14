<?php
/**
 * @var $this \yii\web\View
 * @var $model \backend\models\Permission
 */
$readonly=$model->name?['readonly'=>true]:[];
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput($readonly);
echo $form->field($model,'desc');
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();