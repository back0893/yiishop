<?php

namespace frontend\models;

use yii\db\Query;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $email
 * @property string $tel
 * @property integer $last_login_time
 * @property string $last_login_ip
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;
    public $repassword;
    //这个是图形验证码
    public $checkcode;
    public $remember;
    //这个是短信验证码
    public $code;
    const SCENARIO_LOGIN='login';
    const SCENARIO_REGISTER='register';
    const SCENARIO_EDIT='edit';
    public function setScenario($value)
    {
        $p=parent::setScenario($value);
        $p[self::SCENARIO_REGISTER]=['username','email','password','tel','checkcode','code'];
        $p[self::SCENARIO_EDIT]=['username','email','tel'];
        $p[self::SCENARIO_LOGIN]=['username','password','checkcode','remember'];
        return $p;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //注册
            [['username','email','password','tel'],'required','on'=>self::SCENARIO_REGISTER],
            //修改
            [['username','email','tel'],'required','on'=>self::SCENARIO_EDIT],
            //登录
            [['username','password','checkcode'],'required','on'=>self::SCENARIO_LOGIN],
            ['username','validateUsername','on'=>self::SCENARIO_LOGIN],
            //验证重复,除了已经有的,注册
            ['username', 'unique','on'=>[self::SCENARIO_REGISTER]],
            ['email', 'unique','on'=>[self::SCENARIO_REGISTER]],
            ['tel', 'unique','on'=>[self::SCENARIO_REGISTER]],


            //验证重复,除了已经有的,修改
            ['username', 'unique','filter'=>['!=','username',$this->username],'on'=>[self::SCENARIO_EDIT]],
            ['email', 'unique','filter'=>['!=','email',$this->email],'on'=>[self::SCENARIO_EDIT]],
            ['tel', 'unique','filter'=>['!=','tel',$this->tel],'on'=>[self::SCENARIO_EDIT]],

            //通用
            ['email','email'],
            [['password','email'], 'string', 'max' => 100],
            [['tel'], 'string', 'max' => 11,'min'=>11,'message'=>'非法的手机号'],
            ['!repassword','compare','compareAttribute'=>'password'],
            ['checkcode','captcha'],
            ['remember','safe'],
            ['code','validateSms']
        ];
    }
    public function validateUsername($attr,$params){
        if(!$this::findOne(['username'=>$this->username])){
            $this->addError('username','用户帐号错误');
        }
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'email' => 'Email',
            'tel' => 'Tel',
            'last_login_time' => 'Last Login Time',
            'last_login_ip' => 'Last Login Ip',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)){
            return false;
        }
        $security=\Yii::$app->security;
        if($insert){
            $this->auth_key=$security->generateRandomString();
            $this->password_hash=$security->generatePasswordHash($this->password);
            $this->status=1;
            $this->created_at=time();
            $this->updated_at=$this->created_at;
        }else{
            if($this->password){
                $this->auth_key=$security->generateRandomString();
                $this->password_hash=$security->generatePasswordHash($this->password);
            }
            $this->updated_at=$this->created_at;
        }
        return true;
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
        return self::findOne(['id'=>$id]);
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
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        // TODO: Implement getId() method.
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
        // TODO: Implement getAuthKey() method.
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
        return $this->getAuthKey()==$authKey;
    }
    public function validateSms($attr,$params=[]){
        $tel=$this->$attr;
        $redis=new \Redis();
        $redis->connect('127.0.0.1');
        $code=$redis->get($tel);
        if($code!=$this->code){
            $this->addError('code','短信验证码错误');
        }else{
            //一样注册成功,验证码就失效
            $redis->del($tel);
        }
    }
}
