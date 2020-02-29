<?php
namespace controller\admin;
use includes\Email;
use lib\speed\Speed;
use model\Config;

class MailController extends BaseController{
    private  $data=array('MailSmtp',"MailPort","MailSend","MailPass","MailRec","MailNoticeMe","MailNoticeYou" );
    public function actionGet(){
        $conf=new Config();
        foreach ($this->data as $v){
            $arg[$v]=$conf->getData($v);
        }
        echo json_encode(array("state"=>true,'data'=>$arg));
    }
    public function actionSet(){
        foreach ($this->data as $v){
            if(Speed::arg($v)===null) exit(json_encode(array("state"=>false,"msg"=>"参数错误！".$v)));
        }
        $conf=new Config();
        //更新选项
        foreach ($this->data as $v){
            $conf->setData($v,Speed::arg($v));
        }
        echo json_encode(array("state"=>true));
    }
    public function actionTest(){
        //发送测试邮件
        $email=new Email();
        $this->title="这是一封测试邮件";
        $this->content="测试";
        $content=$this->display("mail/test",true);
        echo "正在尝试发送邮件！";
        $c=new Config();
        $re=$email->send($c->getData('MailRec'),$this->title,$content,"Vpay",2);
        if($re)echo "测试成功！请到邮箱\"".$c->getData('MailRec')."\"查询！";
        else echo "测试失败！具体错误原因请查看上边的日志记录！";
    }
}