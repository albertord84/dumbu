
<script type="text/javascript" src="<?php echo base_url().'assets/js/client1.js'?>" ></script>

<div id="personal_data" style="background-color: #D0D0D0; position: absolute; top: 10%; height: 82%;left: 30%;width: 45%;">        
    <h3>Datos del usuario</h3>
    Name:       <input type="text" id="client_name"><br>
    Login:      <input type="text" id="client_login"><br>
    Pass:       <input type="password" id="client_pass" ><br>
    Email:      <input type="text" id="client_email"><br>
    Telf:       <input type="text" id="client_telf"><br>
    Lang:       <input type="text" id="client_languaje"><br><br>
                <input type="submit" value="Seguinte" id="btn_personal_data">
</div> 
   
<div id="credit_card_data" style="background-color: #D0D0D0; position: absolute; top: 10%; height: 82%;left: 30%;width: 45%;">
    <h3>Datos Carton de Credito</h3>
    Num:       <input type="text" id="client_credit_card_number"><br>
    CVC:       <input type="text" id="client_credit_card_cvc"><br>
    Name:      <input type="text" id="client_credit_card_name"><br>
               <input type="submit" value="Seguinte" id="btn_credit_card_data">
</div>

<div id="sumarize_data" style="background-color: #D0D0D0; position: absolute; top: 10%; height: 82%;left: 30%;width: 45%;">
    <h4>Resumo dos dados para cadastro</h4>
    <table id="tbl_resume">
       
    </table>
        <p> <input type="submit" value="Finalizar" id="btn_register_end"> 
        <hr><hr><hr><hr> 
        <input type="submit" value="cancelar" id="btn_register_cancel"
    </p>
</div>

