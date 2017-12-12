<?php
$path = realpath(dirname(__FILE__));
$pos = strpos($path, 'wp-content/themes');
$fpath = substr($path, 0, $pos) . 'wp-includes/';
require($fpath . 'class-phpmailer.php');
require($fpath . 'class-smtp.php');

class EmailSender {
  private $from;
  private $message;
  private $subject;
  private $to;

  function setFrom($_from) {
    $this->from = $_from;
  }

  function setMessage($_message) {
    $this->message = $_message;
  }

  function setSubject($_subject) {
    $this->subject = $_subject;
  }
  
  function setTo($_to) {
    $this->to = $_to;
  }

  function send() {
    date_default_timezone_set('America/Sao_Paulo');//corrige hora local
    $mail = new PHPMailer();
    
    $mail->IsSMTP();
    $mail->CharSet='UTF-8';
 
    $mail->SMTPDebug = intval(getenv('SMTP_DEBUG'));   // Debugar: 1 = erros e mensagens, 2 = mensagens apenas

    
    $mail->SMTPSecure = boolval(getenv('SMTP_SECURE'));
    $mail->SMTPAuth   = getenv('SMTP_AUTH');  // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
    $mail->Port       = intval(getenv('SMTP_PORT')); //  Usar 587 porta SMTP
    
    $mail->Host = getenv('SMTP_HOST'); // Endereço do servidor SMTP (Autenticação, utilize o host smtp.seudomínio.com.br)
    $mail->Username = getenv('SMTP_USER'); // Usuário do servidor SMTP (endereço de email)
    $mail->Password = getenv('SMTP_PASSWORD'); // Senha do servidor SMTP (senha do email usado)

    error_log("from: " . $this->from);
    error_log("to: " . $this->to);
    error_log("subject: " . $this->subject);
    error_log("message: " . $this->message);

    // $mail->From = get_option('smtp_user');
    // $mail->FromName = get_option('blogname');
    // $mail->Subject = '['. get_option('blogname') .'] ' . $this->subject;
    // $mail->AddReplyTo = $_POST['contact_email'];
    // $mail->Sender = get_option('smtp_user');
    // //SMTP Config

    // $mail->AddAddress( get_option('mail_from') );
    $mail->setFrom($this->from, $this->from); //Seu e-mail
    $mail->Subject = $this->subject;//Assunto do e-mail


    //Define os destinatário(s)
    //=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->AddAddress($this->to, $this->to);

    
    #mail->  
    $mail->Body = $this->message;
 
    // Send Email.
    error_log("Enviando email !!!");
    $retorno = $mail->send();

    $mail->ClearAllRecipients();
    return $retorno;
  }

}
  