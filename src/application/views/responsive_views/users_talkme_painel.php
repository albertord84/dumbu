
<script type="text/javascript" src="<?php echo base_url().'assets/js/talkme_painel.js';?>"></script>

<br><br><p class="section-titles">FALE CONOSCO</p><br>

<div class="center">
        <form id="talkme_frm"   style="box-shadow:0px 0px 5px 0px #424242; width: 50%; margin-left:25%;  border-radius: 5px; border:1px solid silver; padding: 2%;" class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
            <div class="form-group" style="width:100%">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 filter-buttons">
                        <input class="form-control" spellcheck="false" autocomplete="on" id="visitor_name" type="text" placeholder="Nome" required>
                    </div>

                    <div class="col-xs-6 col-sm-6 filter-buttons">
                        <input class="form-control" autocomplete="on" id="visitor_company" type="text" placeholder="Empresa"   required>
                    </div>
                </div>
            </div>
            <div class="form-group" style="width:100%">
                <div class="row">
                    <div class="col-xs-6 col-sm-6 filter-buttons">
                        <input class="form-control" autocomplete="on" id="visitor_email" type="text" placeholder="E-mail" required>
                    </div>

                    <div class="col-xs-6 col-sm-6 filter-buttons">
                        <input class="form-control" autocomplete="off" id="visitor_phone" type="text" maxlength="32" placeholder="Telefone" required>
                    </div>
                </div>
            </div>
            <div class="form-group" style="width:94%">
                <div class="row">
                    <textarea class="form-control"  rows="5" style="margin-left: 3%" autocomplete="on" spellcheck="true"  id="visitor_message" type="textarea" placeholder="Mensagem"></textarea>
                </div>
            </div>
            <div class="form-group" style="width:94%">
                <div class="row center">
                    <button type="button" style="margin-left:4%; width: 40%" class="btn btn-success " >Enviar</button>
                </div>
            </div>
        </form>
   
    
</div>

<br><br>














<!--
<script type="text/javascript" src="<?php //echo base_url().'assets/js/talkme_painel.js';?>"></script>

<div style="position:absolute; background-color:#F4F4F4; width:100%; height:100%;">
        <div style="position:absolute; top:8%; left:20%; width:60%; height:7%; color:black; text-align:center;">
            <b style="font-family:sans-serif;font-size:18px">FALE CONOSCO</b>
        </div>
    <img id="waiting1" height="80px" width="80px" style="z-index:10; visibility:hidden; display:none; position:absolute;top:48%;left:48%; " src="<?php echo base_url().'assets/img/waiting.gif'?>"/>
        <div style=" position:absolute; top:16%; left:20%; width:60%; height:80%;">            
            <form  id="talkme_frm" style="position:absolute; top:5%; left:10%; width:80%; height:87%; font-family:helvetica; border:1px solid gray; font-size:14px; box-shadow:0px 0px 5px 0px #424242; border-radius:5px;">
                    <input spellcheck="false" autocomplete="on" id="visitor_name" type="text" placeholder="Nome"        style="background-color:#F8FFFF; position:absolute; top:5%; left:3%;  height:8%; width:44%; border:1px solid gray; font-size:14px;"/>
                    <input autocomplete="on" id="visitor_company" type="text" placeholder="Empresa"  style="background-color:#F8FFFF; position:absolute; top:5%; left:52%; height:8%; width:44%; border:1px solid gray; font-size:14px;"/>
                    
                    <input autocomplete="on" id="visitor_email" type="text" placeholder="E-mail"     style="background-color:#F8FFFF; position:absolute; top:18%; left:3%;  height:8%; width:44%; border:1px solid gray; font-size:14px;"/>
                    <input autocomplete="off" id="visitor_phone" type="text" maxlength="32" placeholder="Telf. Ex: (21) 96111-5123"   style="background-color:#F8FFFF; position:absolute; top:18%; left:52%; height:8%; width:44%; border:1px solid gray; font-size:14px;"/>                    
                    
                    <textarea autocomplete="on" spellcheck="true"  id="visitor_message" type="textarea" placeholder="Mensagem"  style="background-color:#F8FFFF; position: absolute; top:31%; left:3%; height:50%; width:93%; border:1px solid gray; font-family:helvetica; font-size:14px;"></textarea>
                    <input id="btn_send_message" type="button" value="ENVIAR"      style="cursor:pointer; background-color:#3F3F3F; position:absolute; top:88%; left:40%; height:8%; width:20%; border:1px solid gray; font-size:14px; border-radius: 14px; font-family:sans-serif;font-size:19px; color:white; font-weight: bold;" />
                </form>
        </div>
    
</div>
-->