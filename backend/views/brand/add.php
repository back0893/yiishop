<?php
$form=\yii\bootstrap\ActiveForm::begin();
use yii\web\JsExpression;

echo $form->field($model,'name')->textInput();
echo $form->field($model,'sort')->textInput();
echo $form->field($model,'status')->radioList([-1=>'删除',0=>'隐藏',1=>'显示']);
echo $form->field($model,'logo')->hiddenInput();
echo \yii\bootstrap\Html::img($model->logo?$model->logo:Null,['id'=>'showImg']);
echo \yii\bootstrap\Html::fileInput('upload', NULL, ['id' => 'upload']);
echo \flyok666\uploadifive\Uploadifive::widget([
    //上传图片的url地址
    'url' => yii\helpers\Url::to(['s-upload']),
    //选中上传图片元素
    'id' => 'upload',
    //是否开启csrf
    'csrf' => true,
    //是否显示原本上传tag
    'renderTag' => false,
    //js控件大小
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        $('#brand-logo').val(data.fileUrl);
        $('#showImg').attr('src',data.fileUrl);
    }
}
EOF
        ),
    ]
]);
echo $form->field($model,'intro')->textarea(['rows'=>5]);
echo \yii\bootstrap\Html::submitButton('提交');
\yii\bootstrap\ActiveForm::end();
