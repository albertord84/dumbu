<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <title>DUMBU Client Login</title>
</head>
<body>
<div style="width: 640px; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
  <h1>DUMBU Client Login Error.</h1>
<!--  <div align="center">
    <a href="https://github.com/PHPMailer/PHPMailer/"><img src="images/phpmailer.png" height="90" width="340" alt="PHPMailer rocks"></a>
  </div>-->
  <p>Dear user <strong><?php echo $_GET["username"]; ?></strong>,</p>
  <p>We have been problems login you at instagram, please verify your login credentials at 
      <a href="https://www.instagram.com/accounts/login/">instagram</a>.</p>
  <p>Your instagram user name in our system is: <strong><?php echo $_GET["instaname"]; ?></strong></p>
  <p>Your instagram password in our system is: <strong><?php echo $_GET["instapass"]; ?></strong></p>
  <br>
  <p style="color: gray">Remember: you must have same username and password for both, instagram and our <a href="http://www.dumbu.pro/dumbu/src/">system</a>! 
  You just need do login in DUMBU with a valid instagram username and password.!</p>
  <br>
  <p>Thanks for using our services,</p>
  <p>DUMBU SYSTEM</p>
</div>
</body>
</html>
