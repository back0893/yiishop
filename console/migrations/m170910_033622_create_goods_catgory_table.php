<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_catgory`.
 */
class m170910_033622_create_goods_catgory_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree'=>$this->integer()->comment('树id'),
            'lft'=>$this->integer(),
            'rgt'=>$this->integer(),
            'depth'=>$this->integer(),
            'name'=>$this->string(50)->comment('分类名称'),
            'parent_id'=>$this->integer()->comment('父分类id'),
            'intro'=>$this->text()->comment('简介')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_catgory');
    }
}
