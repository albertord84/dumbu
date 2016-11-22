

<br><br><p class="section-titles">ASSINAR</p><br>
<div class="row">
    <div class="col-md-2"></div> 
    <div class="col-md-8">
        <button id="sete_dias" style="width:35%;margin:0%" type="button">
            <img class="img-btn" style="width:100%;margin:0%" src="<?php echo base_url().'assets/img/siete-dias-verde.png'?>"/>
        </button>
        <strong>OU</strong>
        <button id="palno_mensal" style="width:35%;margin:0%" type="button">
            <img class="img-btn" style="width:100%;margin:0%" src="<?php echo base_url().'assets/img/plano-mensual-gris.png'?>"/>
        </button>
    </div>
    <div class="col-md-2"></div> 
</div>

<br><br>


<div class="container-fluid filter-menu">
    <div class="row">
        <div id="login_panel" class="col-md-4">   <!--col-xs-4 col-sm-4 filter-buttons-->
            <hr>
            <label>PASSO 1</label><br><br>
            <button type="button" style="width:60%; padding:0%;">
                <img class="img-btn" style="width:100%;margin:0%" src="<?php echo base_url().'assets/img/login-instagram-3.png'?>"/>
            </button>
            <form id="login_sign_in"  action="#" method="#"  style="width: 60%; border-radius: 5px; border:1px solid silver; margin-left:20%;  padding:4%" class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
                <div class="form-group">                   
                   <input id = "signin_clientLogin" type="text" class="form-control"  placeholder="Usuário" required>
                </div>
                <div class="form-group">
                   <input id = "signin_clientPassword" type="password" class="form-control" placeholder="Senha" required>
                </div>                
                <div class="form-group">
                    <button id = "signin_btn_insta_login"  type="button" class="btn btn-success btn-block" >Login</button>
                </div>
             </form>
            <br><br>          
        </div>
        
        <div id="data_panel" class="col-md-4">   <!--col-xs-4 col-sm-4 filter-buttons-->
            <hr>
            <label>PASSO 2</label><br><br>            
            <label>INFORMAÇÕES DE PAGAMENTO</label><br>            
            <div class="form-group" style="width:100%">                   
                <input id="client_credit_card_name" type="text" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" placeholder="Nome no cartão" required style="text-transform:uppercase;">                 
            </div>
            <div class="form-group" style="width:100%">                                      
                <input id="client_email" type="email" class="form-control" placeholder="E-mail" required>
            </div>
            <div class=" form-group" style="width:100%">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 filter-buttons">
                        <input id="client_credit_card_number" type="text" class="form-control" placeholder="Número no cartão" data-mask="0000 0000 0000 0000" maxlength="16" required>
                    </div>
                    <div class="col-xs-2 col-sm-2 filter-buttons"></div>
                    <div class="col-xs-4 col-sm-4 filter-buttons">
                        <input id="client_credit_card_cvv" type="text" class="form-control" placeholder="CVV" maxlength="4" required>
                    </div>
                </div>
            </div>
            <div class=" form-group" style="width:100%">
                <div class="row">                    
                    <div class="col-xs-4" style="text-align:right; margin-top:2%">
                        <label>Validade:</label>
                    </div>                    
                    <div class="col-xs-4">
                        <div class="form-group">      
                            <select id="client_credit_card_validate_month" class="form-control">
                                <option>01</option><option>02</option><option>03</option>
                                <option>04</option><option>05</option><option>06</option>
                                <option>07</option><option>08</option><option>09</option>
                                <option>10</option><option>11</option><option>12</option>
                            </select>      
                        </div>
                    </div>             
                    <div class="col-xs-4">                        
                        <div class="form-group">      
                            <select id="client_credit_card_validate_year" class="form-control">
                                 <option>2017</option><option>2018</option>
                                <option>2019</option><option>2020</option><option>2021</option>
                                <option>2022</option><option>2023</option><option>2024</option>
                                <option>2025</option><option>2026</option><option>2027</option>
                                <option>2028</option><option>2029</option><option>2030</option>
                                <option>2031</option><option>2032</option><option>2033</option>
                                <option>2034</option><option>2035</option><option>2036</option>
                                <option>2037</option><option>2038</option><option>2039</option>
                            </select>      
                        </div>
                    </div>
                </div>            
                <br>
            </div>
            <div class="alert alert-success" role="alert">
                Caso não haja o cancelamento em até 7 dias começará o plano mensal automáticamente!
            </div>
            
        </div>   
        
        <div id="sing_in_panel" class="col-md-4">   
            <hr>     
            <label>PASSO 3</label><br><br>
            <button id="btn_sing_in" type="button" style="width:60%; padding:0%;">
                <img class="img-btn" style="width:100%;margin:0%" src="<?php echo base_url().'assets/img/assinar3.png'?>"/>
            </button>
            <br><br>
            <label  class="checkbox-inline">
                <input id="check_declaration" type="checkbox" name="declaration">
                Declaro que li e aceito os <a id="lnk_use_term" style="text-decoration:underline; color:blue" href="#">termos de uso</a>
            </label>
            <br><br>            
        </div>        
    </div>
</div>
<br>


<!--
<ul class="nav navbar-nav navbar-left">
    <li class="dropdown">
       <a href="http://www.jquery2dotnet.com" class="dropdown-toggle" data-toggle="dropdown">Sign in <b class="caret"></b></a>
       <ul class="dropdown-menu" style="padding: 15px;min-width: 250px;">
          <li>
             <div class="row">
                <div class="col-md-12">
                   <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
                      <div class="form-group">
                         <label class="sr-only" for="exampleInputEmail2">Email address</label>
                         <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
                      </div>
                      <div class="form-group">
                         <label class="sr-only" for="exampleInputPassword2">Password</label>
                         <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                      </div>
                      <div class="checkbox">
                         <label>
                            <input type="checkbox"> Remember me
                         </label>
                      </div>
                      <div class="form-group">
                         <button type="submit" class="btn btn-success btn-block">Sign in</button>
                      </div>
                   </form>
                </div>
             </div>
          </li>
          <li class="divider"></li>
          <li>
             <input class="btn btn-primary btn-block" type="button" id="sign-in-google" value="Sign In with Google">
             <input class="btn btn-primary btn-block" type="button" id="sign-in-twitter" value="Sign In with Twitter">
          </li>
       </ul>
    </li>
 </ul>

-->


<!--

<div id="playground-container" style="overflow: hidden">
    
        <div class="container">
            <div class="row" itemscope="http://schema.org/Code" >
                <div class="col-lg-12" itemprop="programmingLanguage" content="html/css/js">
                    <div id="editor-html" class="playground-editor" itemprop="sampleType">
                        <div class="container">
                            <div class="row">
                               <div class="col-md-12">
                                  <nav class="navbar navbar-default" role="navigation">
                                     
                                     <div class="navbar-header">
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        </button>
                                        <a class="navbar-brand" href="http://www.jquery2dotnet.com">Brand</a>
                                     </div>
                                     
                                     <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                        <ul class="nav navbar-nav">
                                           <li class="active"><a href="http://www.jquery2dotnet.com">Home</a></li>
                                           <li><a href="http://www.jquery2dotnet.com">About Us</a></li>
                                           <li class="dropdown">
                                              <a href="http://www.jquery2dotnet.com" class="dropdown-toggle" data-toggle="dropdown">Pages <b class="caret"></b></a>
                                              <ul class="dropdown-menu">
                                                 <li><a href="http://www.jquery2dotnet.com">Action</a></li>
                                                 <li><a href="http://www.jquery2dotnet.com">Another action</a></li>
                                                 <li><a href="http://www.jquery2dotnet.com">Something else here</a></li>
                                                 <li class="divider"></li>
                                                 <li><a href="http://www.jquery2dotnet.com">Separated link</a></li>
                                                 <li class="divider"></li>
                                                 <li><a href="http://www.jquery2dotnet.com">One more separated link</a></li>
                                              </ul>
                                           </li>
                                        </ul>
                                        <form class="navbar-form navbar-left" role="search">
                                           <div class="form-group">
                                              <input type="text" class="form-control" placeholder="Search">
                                           </div>
                                           <button type="submit" class="btn btn-default">Submit</button>
                                        </form>
                                        <ul class="nav navbar-nav navbar-right">
                                           <li><a href="http://www.jquery2dotnet.com">Sign Up</a></li>
                                           <li class="dropdown">
                                              <a href="http://www.jquery2dotnet.com" class="dropdown-toggle" data-toggle="dropdown">Sign in <b class="caret"></b></a>
                                              <ul class="dropdown-menu" style="padding: 15px;min-width: 250px;">
                                                 <li>
                                                    <div class="row">
                                                       <div class="col-md-12">
                                                          <form class="form" role="form" method="post" action="login" accept-charset="UTF-8" id="login-nav">
                                                             <div class="form-group">
                                                                <label class="sr-only" for="exampleInputEmail2">Email address</label>
                                                                <input type="email" class="form-control" id="exampleInputEmail2" placeholder="Email address" required>
                                                             </div>
                                                             <div class="form-group">
                                                                <label class="sr-only" for="exampleInputPassword2">Password</label>
                                                                <input type="password" class="form-control" id="exampleInputPassword2" placeholder="Password" required>
                                                             </div>
                                                             <div class="checkbox">
                                                                <label>
                                                                <input type="checkbox"> Remember me
                                                                </label>
                                                             </div>
                                                             <div class="form-group">
                                                                <button type="submit" class="btn btn-success btn-block">Sign in</button>
                                                             </div>
                                                          </form>
                                                       </div>
                                                    </div>
                                                 </li>
                                                 <li class="divider"></li>
                                                 <li>
                                                    <input class="btn btn-primary btn-block" type="button" id="sign-in-google" value="Sign In with Google">
                                                    <input class="btn btn-primary btn-block" type="button" id="sign-in-twitter" value="Sign In with Twitter">
                                                 </li>
                                              </ul>
                                           </li>
                                        </ul>
                                     </div>
                                  </nav>
                               </div>
                            </div>
</div>
  </div>
  </div>  </div>  </div>  </div>

-->

