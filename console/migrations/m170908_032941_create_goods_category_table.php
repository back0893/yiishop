<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_category`.
 */
class m170908_032941_create_goods_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    //        tree	int()	树id
//lft	int()	左值
//rgt	int()	右值
//depth	int()	层级
//name	varchar(50)	名称
//parent_id	int()	上级分类id
//intro	text()	简介
    public function up()
    {
        $this->createTable('goods_category', [
            'id' => $this->primaryKey(),
            'tree'=>$this->integer()->comment('树id'),
            'lft'=>$this->integer()->comment('左值'),
            'rgt'=>$this->integer()->comment('右值'),
            'depth'=>$this->string(50)->comment('名称'),
            'parent_id'=>$this->integer()->comment('上级分类id'),
            'intro'=>$this->text()->comment('简介')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_category');
    }
}
