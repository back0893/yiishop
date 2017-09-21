<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170915_083327_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('菜单名称'),
            'route'=>$this->string(100)->comment('地址/路由'),
            'sort'=>$this->integer()->unsigned()->defaultValue(1)->comment('排序'),
            'pId'=>$this->integer()->unique()->comment('父id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
