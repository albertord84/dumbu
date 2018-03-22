<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>DUMBU Continuar com o cadastro!</title>
    </head>
    <body>
        <div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
            <h1>DUMBU Continuar com o cadastro!</h1>
            <!--  <div align="center">
                <a href="https://github.com/PHPMailer/PHPMailer/"><img src="images/phpmailer.png" height="90" width="340" alt="PHPMailer rocks"></a>
              </div>-->
            <p>Olá <strong><?php echo $_GET["username"]; ?></strong>,</p>
            <p>Você acaba de fazer o primeiro passo da compra em <a href="https://www.dumbu.pro/dumbu/src/">Dumbu</a>, parabéns! :D</p>
            <p>Seu nome de usuário em nosso sistema é: <strong><?php echo $_GET["instaname"]; ?></strong> 
               Clique no seguinte link para accesar ao segundo passo de seu cadastro:
               <a  href="<?php echo $_GET["second_step_link"]; ?>" > <?php echo $_GET["second_step_link"]; ?></a>
            </p>
            
            <p>Se tiver qualquer dúvida é só nos escrever!</p>
            <p>Obrigado por usar nossos servicios,</p>
            <p>DUMBU SYSTEM</p>
        </div>
    </body>
</html>
