<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
            <meta charset="utf-8">
            <title>DUMBU</title>

            <style type="text/css">

            ::selection { background-color: #E13300; color: white; }
            ::-moz-selection { background-color: #E13300; color: white; }

            
            
            
            a {
                color: white;
                background-color: transparent;
                font-weight: normal;
                font-size: 10px;
            }

            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }

            #body {
                    margin: 0 15px 0 15px;
            }

            p.footer {
                text-align: right;
                font-size: 11px;
                border-top: 1px solid #D0D0D0;
                line-height: 32px;
                padding: 0 10px 0 10px;
                margin: 20px 0 0 0;
            }

            #container {                
                z-index:1;
                position: absolute;
                top:0%;
                left: 15%;
                width: 70%;
                height: 100%;
            }
            
            #cabecera {
                z-index:2;
                position: absolute; 
                background-color: #0f0f0f;
                top:0%;            
                height: 65%;
                width: 100%; 
            } 
            
            #como_funciona {
                z-index:2;
                position: absolute; 
                background-color: #f4f4f4;
                top:65%;
                height: 35%;
                width: 100%; 
            }
                        
            

            </style>
    </head>
    
    <body >
        <div id="container">
            <div id="cabecera">
                
                    <h2 style="color:white; position: absolute; top:2%; left: 46%; width:120px">dumbu</h2>                      
                    <h1 style="color:white; position: absolute; top:16%; left: 20%;">Ganhe em média 3 mil* seguidores reais por mes.</h1>
                
                    <div style="position: absolute; top:2%; left: 70%;"><a>COMO FUNCIONA</a></div>
                    <div style="position: absolute; top:2%; left: 80%;"><a>ASINAR AGORA</a></div>
                    <div style="position: absolute; top:2%; left: 90%;"><a href=''>ENTRAR</a></div>
                    
                    
                    
                    <div aling="center" style="background-color: #D0D0D0; position: absolute; top: 40%; height: 22%;left: 40%;width: 25%;">
                        <form  action="http://localhost/dumbu/src/index.php/welcome/user_do_login" method="POST">
                            <p ALIGN=center>Instragram User:    <input type="text" name="user_name"><br>                            
                            Instragram Pass:                    <input type="password" name="user_pass"><br>
                            <!--Perfil d Interesse:                 <input type="text" name="Perfil"><br>
                            Quantid. a seguer:                 <input type="text" name="Num"><br>-->
                            <br>                                <input type="submit" value="Enviar"></p>
                        </form> 
                    </div>
                    
            </div>

            <div id="como_funciona">
                
            </div>

            
        
        </div>
        
    </body>
</html>