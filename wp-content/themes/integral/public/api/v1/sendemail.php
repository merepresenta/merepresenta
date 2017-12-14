<?php
  require_once realpath(dirname(__FILE__)."/../../../ambiente.php");
 error_log(json_encode($_POST));
  $contribuicao = '';
  if (isset($_POST['contribuicao_Convidar_canidatas'])) {
    $contribuicao = $contribuicao . "\n\tConvidar_canidatas: sim";
  }
  if (isset($_POST["contribuicao_Pesquisar_projetos"])) {
    $contribuicao = $contribuicao . "\n\tPesquisar_projetos: sim";
  }
  if (isset($_POST["contribuicao_Eescolha_pautas"])) {
    $contribuicao = $contribuicao . "\n\tEscolha_pautas: sim";
  }
  if (isset($_POST["contribuicao_Organizacao_parceira"])) {
    $contribuicao = $contribuicao . "\n\tOrganizacao_parceira: sim";
  }
  if (isset($_POST["contribuicao_Divulgar_coletivos"])) {
    $contribuicao = $contribuicao . "\n\tDivulgar_coletivos: sim";
  }
  if (isset($_POST["contribuicao_Organizar_reunioes"])) {
    $contribuicao = $contribuicao . "\n\tOrganizar_reunioes: sim";
  }
  if (isset($_POST["contribuicao_Outra"])) {
    $contribuicao = $contribuicao . "\n\tOutra: " . $_POST["contribuicao_Outra"];
  }


  $pautas = '';
  if (isset($_POST["pauta_Mulheres"])) {
    $pautas = $pautas . "\n\tMulheres: SIM";
  }
  if (isset($_POST["pauta_Pessoas_negras"])) {
    $pautas = $pautas . "\n\tPessoas negras: SIM";
  }
  if (isset($_POST["pauta_LGBTs"])) {
    $pautas = $pautas . "\n\tLGBTs: SIM";
  }
  if (isset($_POST["pauta_Indigenas"])) {
    $pautas = $pautas . "\n\tIndígenas: SIM";
  }
  if (isset($_POST["pauta_Meio_ambiente"])) {
    $pautas = $pautas . "\n\tMeio ambiente: SIM";
  }
  if (isset($_POST["pauta_Causa_animal"])) {
    $pautas = $pautas . "\n\tCausa animal: SIM";
  }
  if (isset($_POST["pauta_Pessoas_com_deficiencia"])) {
    $pautas = $pautas . "\n\tPessoas com deficiência: SIM";
  }
  if (isset($_POST["pauta_Infancia"])) {
    $pautas = $pautas . "\n\tInfância: SIM";
  }
  if (isset($_POST["pauta_Seguranca_Publica"])) {
    $pautas = $pautas . "\n\tSegurança Pública: SIM";
  }
  if (isset($_POST["pauta_Moradia"])) {
    $pautas = $pautas . "\n\tMoradia: SIM";
  }
  if (isset($_POST["pauta_Terra"])) {
    $pautas = $pautas . "\n\tTerra: SIM";
  }
  if (isset($_POST["pauta_Transparencia"])) {
    $pautas = $pautas . "\n\tTransparência: SIM";
  }
  if (isset($_POST["pauta_Estado_laico_liberdade_religiosa"])) {
    $pautas = $pautas . "\n\tEstado laico e liberdade religiosa: SIM";
  }
  if (isset($_POST["pauta_Migrantes"])) {
    $pautas = $pautas . "\n\tMigrantes: SIM";
  }
  if (isset($_POST["pauta_Encarceramento"])) {
    $pautas = $pautas . "\n\tEncarceramento: SIM";
  }
  if (isset($_POST["pauta_Comunicacao"])) {
    $pautas = $pautas . "\n\tComunicação: SIM";
  }
  if (isset($_POST["pauta_Drogas"])) {
    $pautas = $pautas . "\n\tDrogas: SIM";
  }
  if (isset($_POST["pauta_Educacao"])) {
    $pautas = $pautas . "\n\tEducação: SIM";
  }
  if (isset($_POST["pauta_Saude"])) {
    $pautas = $pautas . "\n\tSaúde: SIM";
  }
  if (isset($_POST["outra_pauta_desc"])) {
    $pautas = $pautas . "\n\tOutra: " . $_POST["outra_pauta_desc"];
  }






  $mensagem = "Olá, estou entrando em contato pelo formulário do site. As informações que estou enviando: \n" .
    'Meu nome: ' . $_POST['your-name'] . "\n" .
    'Sobrenome: ' . $_POST['your-surname'] . "\n" .
    'Email: ' . $_POST['your-email'] . "\n" .
    'WhatsApp / Telegram: ' . $_POST['your-cellphone'] . "\n" .
    'Cidade: ' . $_POST['your-city'] . "\n" .
    'Estado: ' . $_POST['your-state'] . "\n" .
    'Gênero: ' . $_POST['your-gender'] . "\n" .
    'Idade: ' . $_POST['your-age'] . "\n\n" .
    'Pautas de maior preocupação: ' . $pautas . "\n\n\n" .
    'Organizações que participa:' . $_POST['your-organizations'] . "\n\n" .
    'Contribuições que posso dar: ' . $contribuicao . "\n\n\n" .
    'Mensagem' . $_POST['your-message'] . "\n\n\n";



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