<script type="text/javascript"> user_active=true; </script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/update_client.js'?>" ></script>

<div id="personal_data" style="background-color: #D0D0D0; position: absolute; top: 10%; height: 82%;left: 30%;width: 45%;">        
    <h3>Datos del usuario</h3>
    Name:       <input type="text" value="<?php echo $user_data['personal_datas']['name']; ?>" id="client_name"><br>  
    Login:      <input type="text" value="<?php echo $user_data['personal_datas']['login']; ?>" id="client_login"><br>
    Pass:       <input type="password" value="<?php echo $user_data['personal_datas']['pass']; ?>" id="client_pass" ><br>
    Email:      <input type="text" value="<?php echo $user_data['personal_datas']['email']; ?>" id="client_email"><br>
    Telf:       <input type="text" value="<?php echo $user_data['personal_datas']['telf']; ?>" id="client_telf"><br>
    Lang:       <input type="text" value="<?php echo $user_data['personal_datas']['languaje']; ?>" id="client_languaje"><br><br>
                <input type="submit" value="Seguinte" id="btn_personal_data_update">
</div> 
   
<div id="credit_card_data" style="background-color: #D0D0D0; position: absolute; top: 10%; height: 82%;left: 30%;width: 45%;">
    <h3>Datos Carton de Credito</h3>
    Num:       <input type="text" value="<?php echo $user_data['bank_datas']['credit_card_number']; ?>" id="client_credit_card_number"><br>
    CVC:       <input type="text" value="<?php echo $user_data['bank_datas']['credit_card_cvc']; ?>" id="client_credit_card_cvc"><br>
    Name:      <input type="text" value="<?php echo $user_data['bank_datas']['credit_card_name']; ?>" id="client_credit_card_name"><br>
               <input type="submit" value="Seguinte" id="btn_credit_card_data_update">
</div>

<div id="sumarize_data" style="background-color: #D0D0D0; position: absolute; top: 10%; height: 82%;left: 30%;width: 45%;">
    <h4>Resumo dos dados para cadastro</h4>
    <table id="tbl_resume">
       
    </table>
        <p> <input type="submit" value="Finalizar" id="btn_register_end_update"> 
        <hr><hr><hr><hr> 
        <input type="submit" value="cancelar" id="btn_register_cancel_update"
    </p>
</div>
