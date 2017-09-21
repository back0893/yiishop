<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170918_115803_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(30)->comment('收货人地址'),
            'province'=>$this->integer()->comment('省级分类'),
            'city'=>$this->integer()->comment('市级分类'),
            'town'=>$this->integer()->comment('区级分类'),
            'tel'=>$this->string(20)->comment('手机号'),
            'status'=>$this->integer(1)->comment('默认地址(1为默认地址)'),
            'address'=>$this->string()->comment('详细地址'),
            'user_id'=>$this->integer()->notNull()->comment('用户id')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
