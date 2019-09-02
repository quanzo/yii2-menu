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
<p>С отмеченными:
<div><?= Html::submitButton(
        Yii::t('module/menu', 'Delete'),
        [
            'data' => [
                'confirm' => Yii::t('module/menu', 'Are you sure?')
            ],
            'class' => 'btn btn-danger',
            'id'=>'btn-delete'
        ]);?>
</div>
<div>
<?php
echo \yii\helpers\Html::input('text', 'new-menu');
echo Html::submitButton(
    Yii::t('module/menu', 'Change menu'),
    [
        'data' => [
            'confirm' => Yii::t('module/menu', 'Are you sure?'),
        ],
        'class' => 'btn btn-danger',
        'id' => 'btn-set-menu',
    ]);
?>
</div>
    <?php $form::end();?>
    <?php $this->registerJs('
    function checked() {
        var checkers = document.querySelectorAll("#' . $formId . ' input[type=checkbox][name=\'sel[]\']");
		var checked = false;
		for (var i=0; i<checkers.length; i++) {
			if (checkers[i].checked) {
				checked = true;
				break;
			}
		}
        return checked;
    }
    document.getElementById("btn-delete").onclick = function (){
		checked = checked();
		if (checked) {
			document.querySelector("#' . $formId . ' input[name=operation]").value="delete";
			return true;
		} else {
			alert("' . Yii::t('module/menu', 'Nothing is selected.') . '");
		}
		return false;
    };
    document.getElementById("btn-set-menu").onclick = function (){
		checked = checked();
		if (checked) {
			document.querySelector("#' . $formId . ' input[name=operation]").value="set-menu";
			return true;
		} else {
			alert("' . Yii::t('module/menu', 'Nothing is selected.') . '");
		}
		return false;
    };
');?>
    <?php Pjax::end();?>
</div>
