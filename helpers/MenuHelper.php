<?php
namespace x51\yii2\modules\menu\helpers;

class MenuHelper
{
    public static $modelName = '\x51\yii2\modules\menu\models\Menu';
    public static $sort = SORT_ASC;

    public static function getMenu($menu)
    {
        $m = static::$modelName;
        $query = $m::find()->where(
            [
                '>', 'active', 0                
            ]
        )->andWhere(['menu' => $menu])
        ->orderBy([
            'sort' => static::$sort
        ])->indexBy($m::primaryKey());

        if ($arResult = $query->all()) {
            //print_r($arResult);
            $arMenu = [];
            $arInMenu = [];
            // первый уровень меню - элементы у которых parent_id указывает на элемент вне списка
            foreach ($arResult as $id => $item) {
                if (!isset($arResult[$item['parent_id']]) && $item->canView) {
                    $arMenu[$id] = static::formMenuArray($item);
                    $arInMenu[$id] = true;
                }
            }
            // заполняем все остальные уровни меню. начинаем перебор с первого уровня. setItems рекурсивно соберет всю ветку
            foreach ($arMenu as $id => &$menu) {
                static::setItems($menu, $arResult, $arInMenu);
            }
            return $arMenu;
        }
        return [];
    }

    protected static function setItems(&$arMenuElement, &$arResult, &$arInMenu) {
        foreach ($arResult as $id => &$item) {
            if (
                $item['parent_id'] == $arMenuElement['id'] &&
                !isset($arInArray[$item['id']]) &&
                $item->canView
            ) {
                if (empty($arMenuElement['items'])) {
                    $arMenuElement['items'] = [];
                    $i = 0;
                } else {
                    $i = sizeof($arMenuElement['items']);
                }
                
                $arMenuElement['items'][$i] = static::formMenuArray($item);
                $arInMenu[$item['id']] = true;
                static::setItems($arMenuElement['items'][$i], $arResult, $arInMenu);
            }
        }
    }

    protected static function formMenuArray($dbRow) {
        $arResult = [
            'label' => $dbRow['name'],            
            'visible' => true,
            'id' => $dbRow['id'],
            'url' => [$dbRow['url_path']],
        ];
        if (!empty($dbRow['url_params'])) {
            $params = [];
            parse_str($dbRow['url_params'], $params);
            if ($params) {
                $arResult['url'] = array_merge($arResult['url'], $params);
            }
        }
        return $arResult;
    }
} // end class
