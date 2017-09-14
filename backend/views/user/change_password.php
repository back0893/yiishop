<?php
/**
 * @var $this \yii\web\View
 * @var $identity \backend\models\User
 */
echo "<h1>用户:{$identity->username}正在就该密码</h1>";
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($identity,'oldPwd');
echo $form->field($identity,'pwd');
echo $form->field($identity,'rpassword');
echo \yii\bootstrap\Html::submitButton('修改');
\yii\bootstrap\ActiveForm::end();