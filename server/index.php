<?
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}


include("inc/conect.php");

if(isset($_POST['submit'])){
    $query = mysqli_query($link,"SELECT user_id, user_password FROM users WHERE user_login='".mysqli_real_escape_string($link,$_POST['login'])."' LIMIT 1");
    $data = mysqli_fetch_assoc($query);
	
    if($data['user_password'] === md5(md5($_POST['password']))){
        $hash = md5(generateCode(10));
        if(!empty($_POST['not_attach_ip'])){
            $insip = ", user_ip=INET_ATON('".$_SERVER['REMOTE_ADDR']."')";
        }
        mysqli_query($link, "UPDATE users SET user_hash='".$hash."' ".$insip." WHERE user_id='".$data['user_id']."'");
        //setcookie("id", $data['user_id']);
        //setcookie("hash", $hash, time()+3600); // httponly !!!
		setcookie("id", $data['user_id'], time()+60*60*24*30, "/");
        setcookie("hash", $hash, time()+60*60*24*30, "/");
        header("Location: check.php"); exit();
    }else{
        print "<center>Ви ввели невірний логін/пароль</center>";
    }
}
?>

<html lang="en">
    <head>
        <title>Test</title>
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-touch-fullscreen" content="yes">
<style type="text/css">
* {box-sizing: border-box;}

body {
  background: #BFC7D8;

}
.ui-form {
  max-width: 350px;
  padding: 80px 30px 30px;
  margin: 50px auto 30px;
}
.ui-form h3 {
  position: relative;
  z-index: 5;
  margin: 0 0 60px;
  text-align: center;
  color: #4a90e2;
  font-size: 30px;
  font-weight: normal;
}
.ui-form h3:before {
  content: "";
  position: absolute;
  z-index: -1;
  left: 60px;
  top: -30px;
  width: 100px;
  height: 100px;
  border-radius: 50%;
  background: #fee8e4;
}
.ui-form h3:after {
  content: "";
  position: absolute;
  z-index: -1;
  right: 50px;
  top: -40px;
  width: 0;
  height: 0;
  border-left: 55px solid transparent;
  border-right: 55px solid transparent;
  border-bottom: 90px solid #ffe3b5;
}
.form-row {
  position: relative;
  margin-bottom: 40px;
}
.form-row input {
  display: block;
  width: 100%;
  padding: 0 10px;
  line-height: 40px;
  font-family: 'Roboto', sans-serif;
  background: none;
  border-width: 0;
  border-bottom: 2px solid #4a90e2;
  transition: all 0.2s ease;
}
.form-row label {
  position: absolute;
  left: 13px;
  color: #9d959d;
  font-size: 20px;
  font-weight: 300;
  transform: translateY(-35px);
  transition: all 0.2s ease;
}
.form-row input:focus {
  outline: 0;
  border-color: #F77A52;
}
.form-row input:focus + label, 
.form-row input:valid + label {
  transform: translateY(-60px);
  margin-left: -14px;
  font-size: 14px;
  font-weight: 400;
  outline: 0;
  border-color: #F77A52;
  color: #F77A52;
}
.ui-form input[type="submit"] {
  width: 100%;
  padding: 0;
  line-height: 42px;
  background: #4a90e2;
  border-width: 0;
  color: white;
  font-size: 20px;
}
.ui-form p {
  margin: 0;
  padding-top: 10px;
}
  </style>
</head> 


<form method="POST" class="ui-form">
  <h3>Вхід</h3>
   <div class="form-row">
    <input name="login" type="text" id="email" required autocomplete="off"><label for="email">Логін</label>
  </div>
  <div class="form-row">
    <input name="password" type="password" id="password" required autocomplete="off"><label for="password">Пароль</label>
  </div>
  <p><input name="submit" type="submit" value="Увійти"></p>
</form>
<center><a href="face.php">Авторизуватись через обличчя</a></center>
</html>