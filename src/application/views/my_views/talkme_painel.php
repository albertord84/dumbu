
<script type="text/javascript" src="<?php echo base_url().'assets/js/talkme_painel.js';?>"></script>

<div style="position:absolute; background-color:#F4F4F4; width:100%; height:100%;">
        <div style="position:absolute; top:8%; left:20%; width:60%; height:7%; color:black; text-align:center;">
            <b style="font-family:sans-serif;font-size:24px">FALE CONOSCO</b>
        </div>
        <div style="position:absolute; top:16%; left:20%; width:60%; height:80%;">            
            <form  id="talkme_frm" style="position:absolute; top:5%; left:10%; width:80%; height:87%; font-family:helvetica; border:1px solid gray; font-size:14px;">                    
                    <input spellcheck="false" autocomplete="on" id="visitor_name" type="text" placeholder="Nome"        style="background-color:#F8FFFF; position:absolute; top:5%; left:3%;  height:8%; width:44%; border:1px solid gray; font-size:14px;"/>
                    <input autocomplete="on" id="visitor_company" type="text" placeholder="Empresa"  style="background-color:#F8FFFF; position:absolute; top:5%; left:52%; height:8%; width:44%; border:1px solid gray; font-size:14px;"/>
                    
                    <input autocomplete="on" id="visitor_email" type="text" placeholder="E-mail"     style="background-color:#F8FFFF; position:absolute; top:18%; left:3%;  height:8%; width:44%; border:1px solid gray; font-size:14px;"/>
                    <input autocomplete="off" id="visitor_phone" type="text" maxlength="32" placeholder="Telf. Ex: (21) 96111-5123"   style="background-color:#F8FFFF; position:absolute; top:18%; left:52%; height:8%; width:44%; border:1px solid gray; font-size:14px;"/>                    
                    
                    <textarea autocomplete="on" spellcheck="true"  id="visitor_message" type="textarea" placeholder="Mensagem"  style="background-color:#F8FFFF; position: absolute; top:31%; left:3%; height:50%; width:93%; border:1px solid gray; font-family:helvetica; font-size:14px;"></textarea>
                    <input id="btn_send_message" type="button" value="ENVIAR"      style="cursor:pointer; background-color:#3F3F3F; position:absolute; top:88%; left:40%; height:8%; width:20%; border:1px solid gray; font-size:14px; border-radius: 14px; font-family:sans-serif;font-size:19px; color:white; font-weight: bold;" />
                </form>
        </div>
</div>
