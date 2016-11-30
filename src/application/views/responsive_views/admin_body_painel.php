

<script type="text/javascript" src="<?php echo base_url().'assets/js/admin.js'?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assets/css/table.css'?>">

<div style="width:100%;height:200%; background-color:white;">    
    
    <div id="cnt_table" class="container-table" >
        <center>            
            <TABLE id="tbl_user" style="z-index:5;"> 
                <thead>
                    <tr>
                        <th scope="col">No.</th>                        
                        <th scope="col">Id.</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Login</th>
                        <th scope="col">Senha</th>
                        <th scope="col">Email</th>
                        <th scope="col">Data pagamento</th>
                        <th scope="col">Status</th>
                        <th scope="col">Nome C.Credito</th>
                        <th scope="col">Numero C.Credito</th>
                        <th scope="col">Order key</th>
                        <th scope="col">Seguidores iniciais</th>
                        <th scope="col">Seguindo inicial</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <!--<th scope="col">No.</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Login</th>
                        <th scope="col">Senha</th>
                        <th scope="col">Email</th>
                        <th scope="col">Data pagamento</th>
                        <th scope="col">Status</th>-->
                    </tr>
                </tfoot>

                <tbody >
                    <?php
                    
                    function get_name_status($val){
                        switch ($val){
                            case 1:    return 'ACTIVE';
                            case 2:    return 'BLOCKED_BY_PAYMENT';
                            case 3:    return 'BLOCKED_BY_INSTA';
                            case 4:    return 'DELETED';
                            case 5:    return 'INACTIVE';
                            case 6:    return 'PENDING';
                            case 7:    return 'UNFOLLOW';
                            case 8:    return 'BEGINNER';
                            case 9:    return 'VERIFY_ACCOUNT';
                        }
                    }


                    for($i=0;$i<count($clients);$i++){
                        if($i%2)
                            $a='<TR id="row'.$i.'" style="background-color:silver">';
                        else 
                            $a='<TR id="row'.$i.'" style="background-color:white">';
                        
                            echo   $a.'<TD class="td-left">  '.($i+1).'</TD>
                                        <TD  class="td-center">  '.$clients[$i]['id'].'</TD>
                                        <TD class="td-left">  '.$clients[$i]['name'].'</TD>
                                        <TD class="td-left">  '.$clients[$i]['login'].'</TD>
                                        <TD class="td-center">  '.$clients[$i]['pass'].'</TD>
                                        <TD class="td-left">  '.$clients[$i]['email'].'</TD>
                                        <TD class="td-center">  '. date('d-m-Y H:i', $clients[$i]['pay_day']) .'</TD>                                        
                                        <TD class="td-center">  '.get_name_status($clients[$i]['status_id']).'</TD>
                                        

                                        <TD class="td-center">  '.$clients[$i]['credit_card_number'].'</TD>
                                        <TD class="td-center">  '.($clients[$i]['credit_card_name']).'</TD>
                                        <TD class="td-center">  '.($clients[$i]['order_key']).'</TD>
                                        <TD class="td-center">  '.($clients[$i]['insta_followers_ini']).'</TD>
                                        <TD class="td-center">  '.($clients[$i]['insta_following']).'</TD>
                                        
                                 </TR>';
                        }
                    ?>
                </tbody>
            </TABLE>
        </center>
    </div>
</div>
