<?php
/**
 * @var $this \yii\web\View
 * @var $user \backend\models\User
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'username');
if(\Yii::$app->controller->action->id=='add'){
    echo $form->field($user,'password_hash')->passwordInput();
    echo $form->field($user,'rpassword')->passwordInput();
}else{
    echo $form->field($user,'pwd')->label('新密码')->passwordInput();
}
echo $form->field($user,'email');
echo $form->field($user,'status')->radioList([2=>'超级管理员',1=>'管理员',0=>'员工']);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();