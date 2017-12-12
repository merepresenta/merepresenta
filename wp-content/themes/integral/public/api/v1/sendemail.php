<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");

  $mensagem = "Olá, estou entrando em contato pelo formulário do site. As informações que estou enviando: \n" .
    'Meu nome: ' . $_POST['your-name'] . "\n" .
    'Sobrenome: ' . $_POST['your-surname'] . "\n" .
    'Email: ' . $_POST['your-email'] . "\n" .
    'WhatsApp / Telegram: ' . $_POST['your-cellphone'] . "\n" .
    'Cidade: ' . $_POST['your-city'] . "\n" .
    'Estado: ' . $_POST['your-state'] . "\n" .
    'Gênero: ' . $_POST['your-gender'] . "\n" .
    'Idade: ' . $_POST['your-age'] . "\n" .
    'Pauta de maior preocupação: ' . $_POST['your-demand'] . "\n\n\n" .
    'Organizações que participa:' . $_POST['your-organizations'] . "\n" .
    'Mensagem' . $_POST['your-message'] . "\n";


  $ambiente = new Ambiente();
  $ambiente->loadLib("email_sender.php");

  $emailSender = $ambiente->emailSender();

  $emailSender->setTo( getenv("SEND_EMAIL_TO") );
  
  $emailSender->setSubject("Contato - Construa com a gente");

  $emailSender->setFrom("merepresenta@merepresenta.org.br");
  $emailSender->setMessage($mensagem);
  
  if ($emailSender->send()) {
    $redirect_addr = @$_POST['REDIRECT_SUCCESS'] ?: getenv('SEND_EMAIL_REDIRECT_SUCCESS');
  }
  else {
    $redirect_addr = @$_POST['REDIRECT_NO_SUCCESS'] ?: getenv('SEND_EMAIL_REDIRECT_NO_SUCCESS');
  }
  header('Location: ' . $redirect_addr);
?>