
<script type="text/javascript">
    var profiles=<?php echo json_encode($profiles);?>;
    var MAX_NUM_PROFILES=<?php echo $MAX_NUM_PROFILES; ?>;
</script>


<div class="row">
    <br><br><p class="section-titles center">PERFIS DE REFERÊNCIA</p><br>
</div>
<div class="row">
    <div class="col-md-3">
        <br>        
        <div style="text-align:center;">
            <img id="my_img" class="my_img_profile" src="<?php echo $my_img_profile;?>"/>
            <br><b><p id="my_name" style="font-size:1.2em; font-family:sans-serif;"><?php echo $my_login_profile;?></p></b>
        </div>        
        <br><br>
    </div> 
    <div class="col-md-7">
        <br>
        <div class="container-profiles">
            <!--<b id="missing_referrence_profiles" style="visibility:hidden; display:none;" class="container-missing-referrence-profiles">
               Adicione seus perfis de referência aqui ...
            </b>-->
            
            <div class="row" style="padding:1%;">
                <div class="col-xs-1"></div>
                <div class="col-xs-2" >
                    <div id="reference_profile0" class="container-reference-profile">
                        <img id="img_ref_prof0" class="img-circle image-reference-profile" src="">
                        <br><b id="name_ref_prof0" style="font-family:sans-serif; font-size:1em;"></b>
                    </div>
                </div>
                <div class="col-xs-2" >
                    <div id="reference_profile1" class="container-reference-profile">
                        <img id="img_ref_prof1" class="img-circle image-reference-profile" src="">
                        <br><b id="name_ref_prof1" style="font-family:sans-serif; font-size:1em;"></b>
                    </div>
                </div>
                <div class="col-xs-2" >
                    <div id="reference_profile2" class="container-reference-profile">
                        <img id="img_ref_prof2" class="img-circle image-reference-profile" src="">
                        <br><b id="name_ref_prof2" style="font-family:sans-serif; font-size:1em;"></b>
                    </div>
                </div>
                <div class="col-xs-2" >
                    <div id="reference_profile3" class="container-reference-profile">
                        <img id="img_ref_prof3" class="img-circle image-reference-profile" src="">
                        <br><b id="name_ref_prof3" style="font-family:sans-serif; font-size:1em;"></b>
                    </div>
                </div>
                <div class="col-xs-1"></div>
                <div class="col-xs-2">
                    <div id="reference_profile3">
                        <button id="adding_profile" type="button" class="btn btn-info btn-social-icon" ><b style="font-size:1.5em ">+</b></button>
                    </div>                    
                </div>
            </div> 
        </div>
        <br><br>        
    </div> 
    
    <div class="col-md-2"> 
        <div id="insert_profile_form" class="form-insert-profile">
            <form action="#" method="#"   class="form" accept-charset="UTF-8" >
                <div class="form-group">                   
                    <input id = "login_profile"  type="text" class="form-control"  placeholder="Perfil" required>
                </div>                              
                <div class="form-group">
                    <button id="btn_insert_profile"   type="submit" class="btn btn-success btn-block" >Adicionar</button>
                </div>
             </form>
        </div>
    </div>     
</div>

<div class="row">
    <div class="row">
        <br><br><p class="section-titles center">AVISOS IMPORTANTES</p><br>
    </div>    
    <div class="row">
        <div id="important_warning"  style="margin-left: 10%; margin-right:10%; padding:3%; padding-bottom:0%; padding-top: 0%; border:1px solid silver; ">
            <div class="row">
                <div class="col-md-12" style="text-align: center; margin: 2%; margin-left: 0">
                    <b style="font-size:1.2em; font-family:sans-serif">STATUS:</b>
                    <?php
                        if($status['status_id']==1)
                            echo '<b id="status_text" style="color:green; font-size:1.2em; font-family:sans-serif">'.$status["status_name"].'</b>';
                        else
                        if($status['status_id']==2)
                            echo '<b id="status_text" style="color:red; font-size:1.2em; font-family:sans-serif">'.$status["status_name"].'</b>';
                        else
                            echo '<b id="status_text" style="color:orange; font-size:1.2em; font-family:sans-serif">'.$status["status_name"].'</b>';
                    ?>             
                    <b id='client_status' style="font-size:1.2em; font-family:sans-serif"></b>
                </div>
            </div>
            
            
            <div id='list_warnings'>
                <script type="text/javascript">var status_messages ='<?php echo json_encode($messages);?>';</script> 
                    <?php
                        /*$flag=false;
                        if($status['status_id']==2){
                            echo '<div class="alert alert-danger" role="alert"><ul id="dangers_warnings"><li style="margin-bottom:0.7em;">'.$status["status_message"].'</ul></div>';                                
                            echo '<div class="alert alert-info" role="alert"><ul id="success_warnings">';
                        }
                        else
                        if($status['status_id']!=1){
                            echo '<div id="container_middle_warnings" class="alert alert-warning" role="alert"><ul id="middle_warnings"><li style="margin-bottom:0.7em;">'.$status["status_message"].'</ul></div>';
                            echo '<div class="alert alert-info" role="alert"><ul id="success_warnings">';
                        }
                        else{                                
                            echo '<div class="alert alert-info" role="alert"><ul id="success_warnings">'
                               . '<li style="margin-bottom:0.7em;"> O Instagram só permite que você siga alredor de 7000 perfis. Precisamos que você siga máximo 6000 perfis para iniciar a ferramenta;';                   
                            }
                        echo    '<li style="margin-bottom:0.7em;"> Nossa ferramenta é interligada ao Instagram, por isso, pode sofrer variações no desempenho a cada atualização feita pelo Instagram;
                                <li style="margin-bottom:0.7em;"> Casso altere seu nome de usuário ou senha no Instagram, o seviço de Dumbu será desconetado temporáriamente. Somente precisa fazer login no Dumbu para atualizar as suas credenciais e continuar recevendo o serviço;
                                <li style="margin-bottom:0.7em;"> Nunca utilice outras ferramentas junto a Dumbu. 
                                </ul>
                                </div>';*/
                    ?> 
            </div>
        </div>
    </div>
</div>
    
<br><br>