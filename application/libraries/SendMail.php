<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SendMail
{
    protected $ci;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function sendEmail($subject, $message, $to, $cc, $subjectName, $attach = null)
    {
        $config = array(
            'protocol' => EMAIL_PROTOCOL,
            'smtp_host' => SMTP_HOST,
            'smtp_port' => SMTP_PORT,
            'smtp_user' => SYSTEM_MAIL,
            'smtp_pass' => SYSTEM_MAILPASS,
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'wordwrap' => true,
        );
        $this->ci->load->library("email", $config);
        $this->ci->email->set_mailtype("html");
        $this->ci->email->set_newline("\r\n");
        $this->ci->email->from(SYSTEM_MAIL_ALIAS, $subjectName);
        $this->ci->email->to($to);
        $this->ci->email->cc($cc);
        $this->ci->email->subject($subject);
        $this->ci->email->message($message);
        if ($attach) {
            $this->ci->email->attach($attach);
        }
        return $this->ci->email->send();
    }

}
