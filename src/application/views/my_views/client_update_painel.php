
<script type="text/javascript">var upgradable_datas=<?php echo json_encode($upgradable_datas);?></script>
<script type="text/javascript" src="<?php echo base_url().'assets/js/update_client_painel.js'?>"></script>

<div style="position:absolute; background-color:#F4F4F4; width:100%; height:100%;">
        <div style="position:absolute; top:8%; left:20%; width:60%; height:7%; color:black; text-align:center;">
            <b style="font-family:sans-serif;font-size:24px">ATUALIZAR DADOS</b>
        </div>
        <div style="position:absolute; top:16%; left:30%; width:40%; height:70%; border:1px solid gray;">            
            <form  style="position:absolute; top:3%; left:2%; width:96%;height:95%; font-family:helvetica; font-size: 16px">
                <p style="font-family:sans-serif; font-size:20px">INFORMAÇÕES PESSOAIS</p>
                <input id="client_credit_card_name" onkeyup="javascript:this.value=this.value.toUpperCase();" type="text" placeholder="Nome no cartão" style="text-transform:uppercase;position:absolute; top:20%; left:0%; height:10%; width:99%; border:1px solid gray;">
                <input id="client_email" type="text" placeholder="E-mail" style="position:absolute; top:35%; left:0%; height:10%; width:99%; border:1px solid gray;">
                <input id="client_credit_card_number" type="text" placeholder="Número do cartão" data-mask="0000 0000 0000 0000" maxlength="16" style="position:absolute; top:50%; left:0%; height:10%; width:74%; border:1px solid gray;">
                <input id="client_credit_card_cvv" type="text" placeholder="CVV" maxlength="3"  style="position:absolute; top:50%; left:80%; height:10%; width:19%; border:1px solid gray;">           
                <div style="position:absolute; top:67%; left:18%; height:10%; width:20%; font-family:sans-serif;font-size:18px">Validade</div>           
                <input id="client_credit_card_validate_month" placeholder="MM"   maxlength="2" type="text" style="position:absolute; top:65%; left:40%; height:10%; width:20%; border:1px solid gray;"> 
                <input  id="client_credit_card_validate_year" placeholder="YYYY" maxlength="4" type="text" style="position:absolute; top:65%; left:65%; height:10%; width:34%; border:1px solid gray;">                     
                
                <input id="btn_send_update_datas" type="button" value="ENVIAR" style="cursor:pointer; background-color:#3F3F3F; position:absolute; top:88%; left:25%; height:8%; width:24%; border:1px solid gray; font-size:14px; border-radius: 14px; font-family:sans-serif;font-size:19px; color:white; font-weight: bold;" />
                <input id="btn_cancel_update_datas" type="button" value="CANCELAR" style="cursor:pointer; background-color:#3F3F3F; position:absolute; top:88%; left:55%; height:8%; width:24%; border:1px solid gray; font-size:14px; border-radius: 14px; font-family:sans-serif;font-size:19px; color:white; font-weight: bold;" />
            </form>
        </div>
</div>








