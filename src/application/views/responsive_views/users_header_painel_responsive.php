<a href="<?php echo base_url().'index.php/welcome/'?>">
        <img id="logo" src="<?php echo base_url().'assets/img/dumbu_logo_png.png'?>"/>
</a>

<ul class="topnav" id="myTopnav">
    <li><a class="active" href="#home">Home</a></li>
    <li><a href="#news">News</a></li>
    <li><a href="#contact">Contact</a></li>
    <li><a href="#about">About</a></li>
    <li class="icon">
        <a href="javascript:void(0);" style="font-size:15px;" onclick="myFunction()">☰</a>
    </li>
</ul>

<div style="padding-right:16px">
    <h2>Responsive Topnav Example</h2>
    <p>Resize the browser window to see how it works.</p>
</div>

<script>
    function myFunction() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav"){
            x.className += " responsive";
        } else {
            x.className = "topnav";
        }
    }
</script>


<!--<ul class="topnav" id="myTopnav">
    <li><a href="#lnk_how_function">COMO FUNCIONA</a></li>
    <li><a  href="#lnk_sign_in_now">ASSINAR AGORA</a></li>
    <li><a id="login" href="#">ENTRAR</a></li>
    <li class="icon">
        <a href="javascript:void(0);" style="font-size:15px;" onclick="myFunction()">☰</a>
    </li>
</ul>-->

    


<!--<div id="how_function" style="position: absolute; top:2%; left: 75%;">
    <a href="#lnk_how_function">COMO FUNCIONA</a>
</div>

<div id="sing_in" style="position: absolute; top:2%; left: 84%;">
    <a  href="#lnk_sign_in_now">ASSINAR AGORA</a>
</div> 

<div style="position: absolute; top:2%; left: 93%;">
    <a id="login" href="#">ENTRAR</a>
</div>-->










<center>
    <div id = "usersLoginForm" >
        <form method = "post" action = "">
            <img type = "image"       id = "userCloseLogin"   src = "<?php echo base_url().'assets/img/close.png'?>">
            <input type = "text"      id = "userLogin"         placeholder = "Usuário">
            <input type = "password"  id = "userPassword"      placeholder = "***"  >
            <input type = "button"    id = "btn_dumbu_login"       value = "Entrar" style="cursor:pointer;">
        </form>
        <img id="waiting" src="<?php echo base_url().'assets/img/waiting.gif'?>"/>
    </div>
</center>


