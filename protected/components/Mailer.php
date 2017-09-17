<?php

class Mailer
{
    protected $mailer = null;
    protected $from = array();
    protected $message = null;

    public function __construct()
    {
        $file = Yii::app()->basePath . DS . 'vendor' . DS . 'swiftmailer' . DS . 'swift_required.php';
        require_once($file);
        $this->from = array(
            Configs::getByKey('mail_account') => Configs::getByKey('seo_title')
        );

        $host = Configs::getByKey('mail_host');
        $port = Configs::getByKey('mail_port');
        $user = Configs::getByKey('mail_account');
        $pass = Configs::getByKey('mail_pass');
        $encrypt = intval(Configs::getByKey('mail_encrypt')) ? 'ssl' : 'tls';

        spl_autoload_unregister(array('YiiBase', 'autoload'));
        $transport = Swift_SmtpTransport::newInstance()
            ->setHost($host)
            ->setPort($port)
            ->setEncryption($encrypt)
            ->setUsername($user)
            ->setPassword($pass);

        $this->mailer = Swift_Mailer::newInstance($transport);
        $this->message = Swift_Message::newInstance();
        spl_autoload_register(array('YiiBase', 'autoload'));
    }

    public function send($subject, $to, $body)
    {
        $this->message->setSubject($subject)
            ->setFrom($this->from)
            ->setTo($to)
            ->setBody($body, 'text/html');

        return $this->mailer->send($this->message);
    }
}