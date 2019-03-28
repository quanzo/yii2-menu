<?php

namespace x51\yii2\modules\menu\controllers;
use yii\web\Controller;

class AutocompleteController extends Controller
{
    public function behaviors()
    {
        return [
            'bootstrap' => [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }

    public function actionIndex() {}

    public function actionMenu($term)
    {
        $arGroup = \x51\yii2\modules\menu\models\Menu::find()
            ->select(['menu'])
            ->distinct()
            ->where(['like', 'menu', $term])
            ->limit(50)
            ->cache(30)
            ->all();
        $result = [];
        foreach ($arGroup as $i => $e) {
            $result[] = ['label'=>$e->menu];
        }
        return $result;
    }

    public function actionParent($term)
    {
        $arParent = \x51\yii2\modules\menu\models\Menu::find()->select(['id', 'name', 'url_path'])
            ->distinct()
            ->where(['like', 'name', $term])
            ->orWhere(['like', 'url_path', $term])
            ->orWhere(['like', 'id', $term])
            ->limit(50)
            ->cache(30)
            ->all();
        $result = [];
        foreach ($arParent as $i => $e) {
            $result[] = [
                'label'=>$e->name.'[#'.$e->id.']',
                'id' => $e->id,
                'title' => $e->name,
                'name' => $e->name,
                'url_path' => $e->url_path,
            ];
        }
        return $result;
    }
} // end class
