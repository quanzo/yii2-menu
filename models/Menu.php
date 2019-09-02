<?php

namespace x51\yii2\modules\menu\models;

use Yii;
use \x51\functions\funcString;
use \x51\yii2\modules\auth\classes\RoleRule;



/**
 * This is the model class for table "{{%sitemenu}}".
 *
 * @property int $id
 * @property string $menu
 * @property int $sort
 * @property string $name
 * @property string $url_path
 * @property string $url_params
 * @property int $active
 * @property string $permission
 * @property int $user_id
 */
class Menu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sitemenu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['menu', 'name'], 'required'],
            [['sort', 'active', 'user_id', 'id', 'parent_id'], 'integer'],
            [['menu', 'name'], 'string', 'max' => 75],
            [['url_path', 'url_params', 'permission', 'route'], 'string', 'max' => 150],
            //['parent_id', 'exist', 'targetAttribute'=>'id']
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('module/menu', 'ID'),
            'parent_id' => Yii::t('module/menu', 'Parent id'),
            'menu' => Yii::t('module/menu', 'Menu'),
            'sort' => Yii::t('module/menu', 'Sort'),
            'name' => Yii::t('module/menu', 'Name'),
            'url_path' => Yii::t('module/menu', 'Url Path'),
            'url_params' => Yii::t('module/menu', 'Url Params'),
            'active' => Yii::t('module/menu', 'Active'),
            'permission' => Yii::t('module/menu', 'Only for permissions'),
            'route' => Yii::t('module/menu', 'Only for routes'),
            'user_id' => Yii::t('module/menu', 'User ID'),
            /*'id' => 'ID',
            'menu' => 'Menu',
            'sort' => 'Sort',
            'name' => 'Name',
            'url_path' => 'Url Path',
            'url_params' => 'Url Params',
            'active' => 'Active',
            'permission' => 'Permission',
            'user_id' => 'User ID',*/
        ];
    }

    /**
     * {@inheritdoc}
     * @return MenuQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MenuQuery(get_called_class());
    }

    public function getParent()
    {
        return $this->hasOne(Menu::className(), ['id' => 'parent_id']);
    }

    public function getCanView()
    {
        $result = true;
        if (!empty($this->permission)) {
            //$arPerm = explode(',', $this->permission);
			$arPerm = funcString::explode([',', ' '], $this->permission, true);
            array_walk($arPerm, function (&$val, $key) {
                $val = trim($val);
            });
            if (empty(RoleRule::choosePermissions($arPerm)) && empty(RoleRule::chooseRoles($arPerm))) {
                $result = false;
            }
        }

        if ($result && !empty($this->route)) {
            $currRoute = \Yii::$app->controller->route;
            $arRoutesView = funcString::explode([',', ' '], $this->route, true);
            if ($arRoutesView) {
                $match = false;
                foreach ($arRoutesView as $path) {
                    $match = fnmatch($path, $currRoute);
                    if ($match) {
                        break;
                    }
                }
                $result = $match;
            }
        }
        return $result;
    } // end getCanView
}
