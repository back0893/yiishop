<?php

use yii\db\Migration;

/**
 * Handles the creation of table `member`.
 */
class m170918_072704_create_member_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('member', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->comment('用户名'),
            'auth_key'=>$this->string(50)->comment('自动登录证书'),
            'password_hash'=>$this->string(100)->comment('密码'),
            'email'=>$this->string(100)->comment('邮箱'),
            'tel'=>$this->string(20)->comment('电话'),
            'last_login_time'=>$this->integer()->comment('最后登录时间'),
            'last_login_ip'=>$this->string(50)->comment('最后登录ip'),
            'status'=>$this->integer(2)->comment('状态(1正常,0删除)'),
            'created_at'=>$this->integer()->comment('创建于'),
            'updated_at'=>$this->integer()->comment('最后一次修改时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('member');
    }
}
