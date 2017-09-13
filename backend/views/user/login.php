<?php
/**
 * @var $this \yii\web\View
 * @var $user \backend\models\User
 */
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($user,'login')->textInput(['placeholder'=>'用来登录的用户名或者邮箱']);
echo $form->field($user,'pwd')->passwordInput(['placeholder'=>'登录密码']);
echo '<div>'.\yii\bootstrap\Html::label('自动登录','remember',['class'=>'control-label'])
      .\yii\bootstrap\Html::checkbox('remember','true',['id'=>'remember']).'</div>';
echo \yii\bootstrap\Html::submitButton('登录');
\yii\bootstrap\ActiveForm::end();
