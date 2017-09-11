<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'intro')->widget('kucha\ueditor\UEditor');
echo \yii\bootstrap\Html::submitButton();
\yii\bootstrap\ActiveForm::end();