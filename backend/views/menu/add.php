<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/**
 * @var $form ActiveForm
 * @var $topMenus array
 * @var $routes array
 */
?>
<div class="menu-add">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'pId')->dropDownList($topMenus)?>
    <?= $form->field($model, 'route')->dropDownList($routes) ?>
    <?= $form->field($model, 'sort') ?>
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- menu-add -->
