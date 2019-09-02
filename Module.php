<?php
namespace x51\yii2\modules\menu;
use \Yii;

/**
 * posts module definition class
 */
class Module extends \yii\base\Module
{
    public $name;
    public $description;

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = '\x51\yii2\modules\menu\controllers';

    /**
     * {@inheritdoc}
     */
    //public $defaultController = 'menu';
    

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if (!isset($this->module->i18n->translations['module/menu'])) {
            $this->module->i18n->translations['module/menu'] = [
                'class' => '\yii\i18n\PhpMessageSource',
                'basePath' => __DIR__ . '/messages',
                'sourceLanguage' => 'en-US',
                'fileMap' => [
                    'module/posts' => 'messages.php',
                ],
            ];
        }
        if (!$this->name) {
            $this->name = Yii::t('module/menu', 'Menu');
        }
        if (!$this->description) {
            $this->description = Yii::t('module/menu', 'Manage site menu');
        }

    } // end init()

    // реализация ISupportAdminPanel
    public function apModuleName() {
        return $this->name;
    }

    public function apModuleDesc() {
        return $this->description;
    }

    public function apAdminMenu() {
        return [
            ['label' => Yii::t('module/menu', 'List'), 'url' => ['/'.$this->id.'/default/index']],
            ['label' => Yii::t('module/menu', 'Create menu element'), 'url' => ['/'.$this->id.'/default/create']],
        ];
    }

    


} // end class
