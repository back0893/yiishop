<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m170910_152453_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id' => $this->primaryKey(),
            'intro'=>$this->text()->comment('商品详细')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
