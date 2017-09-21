<?php
/**
 * @var $this \yii\web\View
 * @var $model \backend\models\Permission
 * $this->context 是控制器对象
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput();
echo $form->field($model,'desc');
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();