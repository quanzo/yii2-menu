<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model x51\yii2\modules\menu\models\Menu */
/* @var $form yii\widgets\ActiveForm */

$allowAutocomplete = class_exists('\yii\jui\AutoComplete');
$module = $this->context->module;



?>

<div class="menu-form">
    <?php $form = ActiveForm::begin(); ?>
<?php
if ($allowAutocomplete) {
    // расшифровка некоторых полей модели
    if ($pp = $model->parent) {
        $parentTitle = $pp->name;
    } else {
        $parentTitle = '';
    }

    $afPostParent = $form->field($model, 'parent_id')->widget('\yii\jui\AutoComplete', [
        'model' => $model,
        'attribute' => 'parent_id',
        'clientOptions' => [
            'source' => yii\helpers\Url::to(['/' . $module->id . '/autocomplete/parent']),
            'minLength' => 1,
            'select' => new \yii\web\JsExpression('function (event, ui) {
            this.value = ui.item.id;
            $("#parent_title").html(ui.item.title);
            //console.log(ui, this);
            return false;
        }'),
            'create' => new \yii\web\JsExpression('function (event, ui) {
            $("#post_parent_title").html("' . $parentTitle . '");
        }'),
        ],
    ])->hint('Начните вводить название или код. В списке появятся возможные варианты.');
    $afPostParent->parts['{input}'] .= '<label id="parent_title" class="detail-label"></label>';
    echo $afPostParent;
} else {
    /*$posts = $this->context->module->module->getList(['onlyName' => true, 'includeRoot' => true]);
    // исключить текущую
    if (isset($posts[$model->id])) {
        unset($posts[$model->id]);
    }

    echo $form
        ->field($model, 'post_parent')
        ->listBox($posts, ['multiple' => false, 'size' => 10]);*/
    
    echo $form->field($model, 'parent_id')->textInput();

}
?>
<?
if ($allowAutocomplete) {
    echo $form->field($model, 'menu')->widget('\yii\jui\AutoComplete', [
        'model' => $model,
        'attribute' => 'menu',
        'clientOptions' => [
            'source' => yii\helpers\Url::to(['/' . $module->id . '/autocomplete/menu']),
            'minLength' => 1,
        ],
    ])->hint('Введите название меню. В списке появятся коды, использованные раннее.');
} else {
    echo $form->field($model, 'menu')->textInput(['maxlength' => true]);
}   
?>

    <?= $form->field($model, 'sort')->input('number') ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_params')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'active')->dropDownList([
		0 => Yii::t('module/menu', 'no'),
        1 => Yii::t('module/menu', 'yes')], []);
    ?>

    <?= $form->field($model, 'permission')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'route')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
