    
    
    
    <div class="col-md-7"></div>
    <div class="col-md-5" style="margin-left:-1.2%">        
            <div class="row" >        
                <nav class="navbar navbar-inverse navbar-right"  role="navigation">  <!--style="background-color:transparent;border-color:transparent;"-->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle"  data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">  <!--style="background-color:transparent;"-->
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div  class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">           
                         <ul class="nav navbar-nav navbar-left">
                             <li><a  href="#lnk_how_function">COMO FUNCIONA</a></li> <!--style="color:white"-->
                            <li><a  href="#lnk_sign_in_now">ASSINAR AGORA</a></li> <!--style="color:white"-->
                            <li class="dropdown" >
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">ENTRAR<b class="caret"></b></a> <!--style="background-color:transparent; color:white" -->
                                <ul class="dropdown-menu" style="padding: 15px; min-width: 220px;">
                                    <li>
                                        <div class="row">
                                            <div class="col-md-12" >
                                                <form id="usersLoginForm"  action="#" method="#"  class="form" role="form"  accept-charset="UTF-8" >
                                                    <div class="form-group">                                                                
                                                         <input id="userLogin" type="text" class="form-control" placeholder="Usuário" required>
                                                    </div>
                                                    <div class="form-group">                                                                
                                                        <input id="userPassword" type="password" class="form-control" placeholder="Senha" required>
                                                    </div>                                                             
                                                    <div class="form-group">
                                                        <button id="btn_dumbu_login" type="button" class="btn btn-success btn-block">Entrar</button>
                                                    </div>                                                    
                                                </form>
                                            </div>
                                        </div>
                                    </li>                                                
                                </ul>
                            </li>
                         </ul>
                    </div>
                </nav> 
            </div>
    </div>


    <div class="center">
        <a href="#"><img  class="logo" src="<?php echo base_url().'assets/img/dumbu_logo_png.png'?>"/></a>        
    </div>
   

<!--<center>
    <div id = "usersLoginForm" >
        <form method = "post" action = "">
            <img type = "image"       id = "userCloseLogin"   src = "<?php //echo base_url().'assets/img/close.png'?>">
            <input type = "text"      id = "userLogin"         placeholder = "Usuário">
            <input type = "password"  id = "userPassword"      placeholder = "***"  >
            <input type = "button"    id = "btn_dumbu_login"       value = "Entrar" style="cursor:pointer;">
        </form>
        <img id="waiting" src="<?php //echo base_url().'assets/img/waiting.gif'?>"/>
    </div>
</center>
-->