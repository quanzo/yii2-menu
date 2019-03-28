<?php
namespace x51\yii2\modules\menu;

/**
 * posts module definition class
 */
class Module extends \yii\base\Module
{
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
    } // end init()

    


} // end class
