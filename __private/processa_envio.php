<?php

    require "./Bibliotecas/PHPMailer/Exception.php";
    require "./Bibliotecas/PHPMailer/OAuth.php";
    require "./Bibliotecas/PHPMailer/PHPMailer.php";
    require "./Bibliotecas/PHPMailer/POP3.php";
    require "./Bibliotecas/PHPMailer/SMTP.php";

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;


    // print_r($_POST);

    class Mensagem {
        private $para = null;
        private $assunto = null;
        private $mensagem = null;
        public $status = array('codigo_status' => null, 'descricao_status' => '');

        public function __construct($para, $assunto, $mensagem)
        {
            $this->para = $para;
            $this->assunto = $assunto;
            $this->mensagem = $mensagem;
        }

        public function __get($attr) {
            return $this->$attr;
        }

        public function __set($attr, $value) {
            $this->$attr = $value;
        }

        public function mensagemValida() {
            if(empty($this->para) || empty($this->assunto) || empty($this->mensagem)) {
                return false;
            }

            return true;
        }
    }

    $mensagem = new Mensagem($_POST['para'], $_POST['assunto'], $_POST['mensagem']);

    if(!$mensagem->mensagemValida()) {
        echo 'Mensagem não é válida';
        header('location: index.php');
    }

    $mail = new PHPMailer(true);

    try {
        //Server settings
        // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->SMTPDebug = false;
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'webcompleto2@gmail.com';                     //SMTP username
        $mail->Password   = '!@#$4321';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('webcompleto2@gmail.com', 'Mailer');
        $mail->addAddress( $mensagem->__get('para'), 'Joe User');     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $mensagem->__get('assunto'); //'Here is the subject';
        $mail->Body    = $mensagem->__get('mensagem'); //'This is the HTML message body <b>in bold!</b>';
        $mail->AltBody = 'É necessário utilizar um client que suporte HTML para ter acesso ao conteúdo completo do email';

        $mail->send();

        $mensagem->status['codigo_status'] = 1;
        $mensagem->status['descricao_status'] = 'E-mail enviado com sucesso';
    } catch (Exception $e) {
        $mensagem->status['codigo_status'] = 2;
        $mensagem->status['descricao_status'] = "Não foi possível enviar este email. Mailer Error: {$mail->ErrorInfo}";
    }

?>

<html>
    <head>
        <meta charset="utf-8" />
    	<title>App Mail Send</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <img class="d-block mx-auto mb-2" src="logo.png" alt="" width="72" height="72">
			<h2>Send Mail</h2>
			<p class="lead">Seu app de envio de e-mails particular!</p>
        </div>

        <div class="row">
            <div class="col-md-12">
                <?php
                
                    if($mensagem->status['codigo_status'] == 1) { ?>

                        <div class="container">
                            <h1 class="display-4 text-success">Sucesso</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?>

                
                <?php
                
                    if($mensagem->status['codigo_status'] == 2) { ?>

                        <div class="container">
                            <h1 class="display-4 text-danger">Ops!</h1>
                            <p><?= $mensagem->status['descricao_status'] ?></p>
                            <a href="index.php" class="btn btn-success btn-lg mt-5 text-white">Voltar</a>
                        </div>

                    <?php } ?>
                
                
            </div>
        </div>
    </body>
</html>