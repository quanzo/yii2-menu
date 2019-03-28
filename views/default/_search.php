<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model x51\yii2\modules\menu\models\MenuSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'parent_id') ?>
    
    <?= $form->field($model, 'menu') ?>

    <?= $form->field($model, 'sort') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'url_path') ?>

    <?php // echo $form->field($model, 'url_params') ?>

    <?php // echo $form->field($model, 'active') ?>

    <?php // echo $form->field($model, 'permission') ?>

    <?php // echo $form->field($model, 'user_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
