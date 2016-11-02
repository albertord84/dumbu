    
<script type="text/javascript" src="<?php echo base_url().'assets/js/client.js'?>" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/sign_in_style.css'?>">

<!--<style>
    form.frm_card_datas input {
        background-color: red;
    }
</style>-->


<div id="title_panel" style="text-align:center; color:black; background-color:white; position:absolute; top:0%; height:10%;left:0%;width:100%;">        
    <p><b style="font-family:sans-serif;font-size:24px">ASSINAR</b></p>
</div> 

   
<div id="plane_promotianal_panel" style="text-align:center; background-color:white; position:absolute; top:10%; height:15%;left:0%;width:100%;">   
    <div id="promotion" style="background-color:white;position:absolute; top:0%; height:100%; left:20%; width:27%;">      
        <img src="<?php echo base_url().'assets/img/7-dias-grátis-2.png'?>" style="border-radius:4px; position:absolute; top:0%; height:100%; left:0%; width:100%;">
    </div>
   <div id="ou" style="background-color:white;position:absolute; top:5%; height:100%; left:47%; width:5%;">
       <p><b style="font-family:sans-serif;font-size:20px">OU</b></p>
   </div>
    <div id="plane" style="background-color:white;position:absolute; top:0%; height:100%; left:52%; width:27%;">        
        <img src="<?php echo base_url().'assets/img/plano mensal-3.png'?>" style="border-radius:4px;position:absolute; top:0%; height:100%; left:0%; width:100%;">
    </div>    
</div>


<div id="pather_sing_in_panel" style="background-color:white; position:absolute; top:25%; height:75%;left:0%;width:100%;">
    <!--PASSO 1--> 
    <div id="login_panel" style="text-align:center; position:absolute; top:5%; height:100%; left:10%; width:15%;">      
        <p><b style="font-family:sans-serif;font-size:20px">PASSO 1</b></p>
        <center>
            <input type="button" id="show_login" value="" style="background-image:url('<?php echo base_url()."assets/img/login-instagram-2.png"; ?>'); background-repeat:no-repeat; background-size:100%;border-radius:8px; position:absolute; top:25%; left:10%; width:80%; height:14%; ">             
            <div id = "loginform">                
                <form method = "post" action = "">
                    <img type = "image"       id = "close_login"   src = "<?php echo base_url().'assets/img/close.png'?>">
                    <input type = "text"      id = "clientLogin"         placeholder = "Usuário">
                    <input type = "password"  id = "clientPassword"      placeholder = "***"  >
                    <input type = "button"    id = "btn_insta_login"       value = "Login">
                </form>
                <img id="waiting_sign_in" src="<?php echo base_url().'assets/img/waiting.gif'?>"/>
            </div>
        </center>
    </div>

    <!--PASSO 2--> 
    <div id="data_panel" style="text-align:center; position:absolute; top:5%; height:100%; left:30%; width:40%;">
       <p><b style="font-family:sans-serif;font-size:20px">PASSO 2</b></p>
       <form  style=" position:absolute; top:15%; left:10%; width:80%;height:70%; font-family:helvetica;">
           <p style="font-family:sans-serif; font-size:20px">INFORMAÇÕES DE PAGAMENTO</p>
           <input id="client_credit_card_name" onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" placeholder="Nome no cartão" style="text-transform:uppercase;position:absolute; top:20%; left:0%; height:10%; width:99%; border:1px solid gray;">
           <input id="client_email" type="text" placeholder="E-mail" style="position:absolute; top:35%; left:0%; height:10%; width:99%; border:1px solid gray;">
           <input id="client_credit_card_number" type="text" placeholder="Número do cartão" data-mask="0000 0000 0000 0000" maxlength="16" style="position:absolute; top:50%; left:0%; height:10%; width:74%; border:1px solid gray;">
           <input id="client_credit_card_cvv" type="text" placeholder="CVV" maxlength="3"  style="position:absolute; top:50%; left:80%; height:10%; width:19%; border:1px solid gray;">           
           <div style="position:absolute; top:65%; left:15%; height:10%; width:20%; font-family:sans-serif;font-size:16px">Validade</div>           
           <input id="client_credit_card_validate_month" placeholder="MM"   maxlength="2" type="text" style="position:absolute; top:65%; left:40%; height:10%; width:20%; border:1px solid gray;"> 
           <input  id="client_credit_card_validate_year" placeholder="YYYY" maxlength="4" type="text" style="position:absolute; top:65%; left:65%; height:10%; width:34%; border:1px solid gray;">
           <!--<input  id="client_credit_card_validate_year" name="color"   style="position:absolute; top:65%; left:65%; height:10%; width:34%; border:1px solid gray;">                  
           <datalist id="listas">
                <option value="azul">azul</option>
                <option value="rojo">rojo</option>
                <option value="amarillo">amarillo</option>
                <option value="negro">negro</option>
                <option value="verde">verde</option>
            </datalist>-->           
       </form>
       <div id="promotional_text" style="position:absolute; top:74%; left:5%; width:90%; height:10%">
           <p style="font-family:sans-serif; font-size:20px">Caso não haja o cancelamento em até 7 dias começará o plano mensal automáticamente!</p>
       </div>       
    </div>
    
    <!--PASSO 3--> 
    <div id="sing_in_panel" style="text-align:center; position:absolute; top:5%; height:100%; left:75%; width:15%;">        
        <p><b style="font-family:sans-serif;font-size:20px">PASSO 3</b></p>
        <input id="btn_sing_in" type="button"  value="" style="position:absolute; top:25%; left:0%; width:80%; height:14%;border-radius:8px;  background-image:url('<?php echo base_url()."assets/img/assinar2.png"; ?>'); background-repeat:no-repeat; background-size:100%;">        
        <div id="user_term" style="position:absolute; top:45%; left:0%; ">
            <input id="check_declaration" type="checkbox" name="declaration">
            <a style="color:black" href="">Declaro que li e aceito os termos de uso</a>
        </div>
    </div>
    
</div>

