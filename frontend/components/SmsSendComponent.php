<?php
namespace frontend\components;

use yii\base\Object;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

Config::load();

class SmsSendComponent extends Object{
    protected $acsClient;
    public function __construct($accessKey,$accessKeySecret,array $config = [])
    {
        $product = "Dysmsapi";
        $domain = "dysmsapi.aliyuncs.com";
        $region = "cn-hangzhou";
        $endPointName = "cn-hangzhou";
        $profile = DefaultProfile::getProfile($region, $accessKey, $accessKeySecret);
        DefaultProfile::addEndpoint($endPointName,$region,$product,$domain);
        $this->acsClient=new DefaultAcsClient($profile);
        parent::__construct($config);
    }
    public function sendSms($signName,$templateCode,$phoneNumbers,$templateParams=null,$outId=null){
        $request=new SendSmsRequest();
        $request->setPhoneNumbers($phoneNumbers);
        $request->setSignName($signName);
        $request->setTemplateCode($templateCode);
        if($templateParams){
            $request->setTemplateParam(json_encode($templateParams));
        }
        if($outId){
            $request->setOutId($outId);
        }
        $response=$this->acsClient->getAcsResponse($request);
        return $response;
    }
    public function queryDetails($phoneNumbers,$sendDate,$pageSize=10,$currentPage=1,$bizId=Null){
        $request=new QuerySendDetailsRequest();
        $request->setPhoneNumber($phoneNumbers);
        $request->setSendDate($sendDate);
        $request->setPageSize($pageSize);
        $request->setCurrentPage($currentPage);
        $response=$this->acsClient->getAcsResponse($request);
        return $response;
    }
}
