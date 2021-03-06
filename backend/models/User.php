<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $last_login_time
 * @property string $last_login_ip
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    //登录时的明文密码
    public $login;
    //明文密码
    public $pwd;
    //修改密码时的旧密码
    public $oldPwd;
    //重复密码
    public $rpassword;
    //用来接受角色,是由角色名组成的数组
    public $roles;

    const SCENARIO_CHANGE_PASSWORD='change_password';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function setScenario($value)
    {
        $p=parent::setScenario($value);
        $p['logo']=['login','pwd','remember'];
        $p['add']=['username','rpassword', 'password_hash', 'email','status'];
        $p['edit']=['username','pwd','auth_key', 'email','status'];
        $p[self::SCENARIO_CHANGE_PASSWORD]=['pwd','rpassword','oldPwd'];
        return $p;
    }

    public function rules()
    {
        return [
            //修改规则
            [['username','email','status'],'required','on'=>'edit'],
            [['username','email'],'unique','on'=>'edit','filter'=>['!=','id',$this->id]],
            //添加规则
            [['username', 'password_hash','email'], 'required','on'=>'add'],
            //登录规则
            [['login','pwd'],'required','on'=>'login'],
            //修改自我密码规则
            [['pwd','oldPwd'],'required','on'=>self::SCENARIO_CHANGE_PASSWORD],
            [['pwd'],'match','pattern'=>'/^(?=.*?[a-z])(?=.*?[0-9])[a-z0-9]+$/','message'=>'请最少包含一个字符和数组','on'=>self::SCENARIO_CHANGE_PASSWORD],
            ['rpassword','compare','compareAttribute'=>'pwd','message'=>'2次密码不相同','on'=>self::SCENARIO_CHANGE_PASSWORD],
            ['oldPwd','validateOldPwd','on'=>self::SCENARIO_CHANGE_PASSWORD],
            //通用规则,当没有值会跳过验证规则
            [['status'], 'integer'],
            [['username', 'password_hash', 'email','pwd'], 'string', 'max' => 255,'min'=>4,'tooShort'=>'位数少于4位'],
            ['email','email'],
            [['username','email'], 'unique'],
            ['rpassword','compare','compareAttribute'=>'password_hash','message'=>'2次密码不相同','on'=>'edit'],
            ['roles','safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {

        return [
            'roles'=>'角色权限',
            'username' => '用户名',
            'password_hash' => '密码',
            'email' => '邮箱',
            'status' => '状态',
            'rpassword'=>'重复密码',
            'pwd'=>'登录密码',
            'login'=>'登录帐号',
            'oldPwd'=>'旧密码'
        ];
    }
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'created_at',
                'updatedAtAttribute'=>'updated_at'
            ],
        ];
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        // TODO: Implement findIdentity() method.
        return self::findOne(['id'=>$id,'status'=>[0,1,2]]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     *
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey()===$authKey;
    }
    //@var $insert 表示本次保存是是添加还是保存
    public function beforeSave($insert)
    {
        $security=\Yii::$app->getSecurity();
        if($insert){
            $this->password_hash=$security->generatePasswordHash($this->password_hash);
            //生成一个用于自动登录的令牌
            $this->auth_key=$security->generateRandomString();
        }else{
            //只在修改密码时启动
            if($this->pwd){
                $this->password_hash=$security->generatePasswordHash($this->pwd);
                $this->auth_key=$security->generateRandomString();
            }
        }
        return parent::beforeSave($insert);
    }
    public function afterSave($insert, $changedAttributes)
    {
        $userId=$this->id?$this->id:$changedAttributes['id'];
        $this->assign($userId);
        parent::afterSave($insert, $changedAttributes);
    }

    public function validateLogin($loginUser){
        $security=\Yii::$app->getSecurity();
        if($security->validatePassword($loginUser->pwd,$this->password_hash)){
            $this->touch('last_login_time');
            $this->last_login_ip=\Yii::$app->request->getUserIP();
            return $this->save();
        }
        $this->addError('pwd','密码错误');
        return false;
    }
    //自定义验证规则至考虑验证不通过的情况,验证不通过需要添加错误
    public function validateOldPwd(){
        $security=\Yii::$app->getSecurity();
        if(!$security->validatePassword($this->oldPwd,$this->password_hash)){
            $this->addError('oldPwd','密码错误');
        }
    }
    //需要在角色保存后调用,因为此时$this->id上才会有值
    public function assign($userId){
        $auth=\Yii::$app->authManager;
        $tr1=\Yii::$app->db->beginTransaction();
        try {
            //先解除user绑定的说有角色
            $auth->revokeAll($userId);
            //解除后在绑定
            foreach ($this->roles as $role) {
                var_dump($auth->assign($auth->getRole($role), $userId));
            }
            $tr1->commit();
        }catch (\Exception $e){
            $tr1->rollBack();
        }
    }
}
