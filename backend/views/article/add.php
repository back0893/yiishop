<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($article,'name')->textInput();
echo $form->field($article,'article_category_id')->dropDownList($cats);
echo $form->field($article,'status')->radioList(['隐藏','显示']);
echo $form->field($article,'sort')->textInput();
echo $form->field($article,'intro')->textarea(['rows'=>5]);
echo $form->field($articleDetail,'content')->textarea(['rows'=>10]);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();