<?php


defined('BASEPATH') or exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;

require_once APPPATH . 'libraries/PHPMailer/src/Exception.php';
require_once APPPATH . 'libraries/PHPMailer/src/SMTP.php';
require_once APPPATH . 'libraries/PHPMailer/src/PHPMailer.php';

class PHPMailer_lib
{
    protected $ci;
    protected $mail;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->mail = new PHPMailer;
    }

    public function load()
    {
        return $this->mail;
    }
}
