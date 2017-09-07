<?php
$form=\yii\bootstrap\ActiveForm::begin();

echo $form->field($model,'name')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->radioList([-1=>'删除',0=>'隐藏',1=>'显示']);
echo $form->field($model,'intro')->textarea(['rows'=>5]);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
