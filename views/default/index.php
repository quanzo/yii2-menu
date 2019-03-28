<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel x51\yii2\modules\menu\models\MenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$formId = 'group-op';
$allowAutocomplete = class_exists('\yii\jui\AutoComplete');

$this->title = 'Menus';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-index">

    <h1><?=Html::encode($this->title)?></h1>
    <?php Pjax::begin(['id' => 'menu-index']);?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?=Html::a('Create Menu', ['create'], ['class' => 'btn btn-success'])?>
    </p>

    <?php $form = ActiveForm::begin([
        'options' => [
            'data-pjax' => true,
            'id' => $formId,
        ],
    ]);
        echo Html::hiddenInput('operation', '');
    ?>


    <?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        [
            'class' => '\yii\grid\CheckboxColumn',
            'name' => 'sel[]',
            'checkboxOptions' => function ($model, $key, $index, $column) {
                $opt = [
                    'value' => $key,
                ];
                return $opt;
            },
        ],
        'id',
        'parent_id',
        'menu',
        'sort',
        'name',
        'url_path:url',
        //'url_params:url',
        //'active',
        //'permission',
        //'user_id',

        ['class' => 'yii\grid\ActionColumn'],
    ],
]);?>
    <?php $form::end();?>
    <?php Pjax::end();?>
</div>
