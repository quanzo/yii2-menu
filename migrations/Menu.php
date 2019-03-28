<?php
namespace x51\yii2\modules\menu\migrations;

use yii\db\Migration;

class Menu extends Migration
{
    public $baseTableName = 'sitemenu';

    public function init()
    {
        parent::init();
    } // end init

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // создаем таблицы
        $tblPosts = [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer()->defaultValue(0),
            'menu' => $this->string(75)->notNull()->defaultValue('default'),
            'sort' => $this->integer()->defaultValue(100),
            'name' => $this->string(75)->notNull(),
            'url_path' => $this->string(150)->defaultValue(''),
            'url_params' => $this->string(150)->defaultValue(''),
            'active' => $this->tinyInteger()->defaultValue(1),
            'permission' => $this->string(150)->defaultValue(''),
            'user_id' => $this->integer()->defaultValue(0),
        ];
        $this->createTable('{{%' . $this->baseTableName . '}}', $tblPosts, $tableOptions);
        $this->createIndex('k_' . $this->baseTableName . '_menu', '{{%' . $this->baseTableName . '}}', 'menu');
        $this->createIndex('k_' . $this->baseTableName . '_user', '{{%' . $this->baseTableName . '}}', 'user_id');
        $this->createIndex('k_' . $this->baseTableName . '_sort', '{{%' . $this->baseTableName . '}}', 'sort');
        $this->createIndex('k_' . $this->baseTableName . '_active', '{{%' . $this->baseTableName . '}}', 'active');
        $this->createIndex('k_' . $this->baseTableName . '_parent', '{{%' . $this->baseTableName . '}}', 'parent_id');

        $this->insert('{{%' . $this->baseTableName . '}}', [
            'id' => 0,
            'parent_id' => 0,
            'menu' => '',
            'sort' => 0,
            'name' => 'root',
            'active' => 1,
        ]);
        $newId = $this->db->getLastInsertID();
        if ($newId != 0) {
            $this->update('{{%' . $this->baseTableName . '}}', ['id' => 0], ['id' => $newId]);
        }

        $this->addForeignKey('fk_' . $this->baseTableName . '_parent', '{{%' . $this->baseTableName . '}}', 'parent_id', '{{%' . $this->baseTableName . '}}', 'id', 'CASCADE', 'CASCADE');

    } // end safeUp

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_' . $this->baseTableName . '_parent', '{{%' . $this->baseTableName . '}}');
        $this->dropTable('{{%' . $this->baseTableName . '}}');
    }

    /*
// Use up()/down() to run migration code without a transaction.
public function up()
{

}

public function down()
{
echo "m180806_115337_article cannot be reverted.\n";

return false;
}
 */
} // end class
