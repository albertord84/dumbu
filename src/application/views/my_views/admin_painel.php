

<script type="text/javascript" src="<?php echo base_url().'assets/js/admin.js'?>"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url().'assets/css/table.css'?>">

<div style="width:100%;height:100%; background-color:white">    
    <div style="position:absolute; top:2%; left:67%; width:30%; height:5%; border:1px solid silver;">
        
    </div>
    <div id="cnt_table" class="container-table">
        <center>            
            <TABLE id="tbl_user" style="z-index:5;"> 
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nome</th>
                        <th scope="col">Login</th>
                        <th scope="col">Senha</th>
                        <th scope="col">Email</th>
                        <th scope="col">Data pagamento</th>
                        <th scope="col">Status</th>
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
                        }
                    }


                    for($i=0;$i<count($clients);$i++){
                            echo '<TR id="row'.$i.'">
                                        <TD  style="visibility:hidden;display:none;">  '.$clients[$i]['id'].'</TD>
                                        <TD class="td-left">  '.($i+1).'</TD>
                                        <TD class="td-left">  '.$clients[$i]['name'].'</TD>
                                        <TD class="td-left">  '.$clients[$i]['login'].'</TD>
                                        <TD class="td-center">  '.$clients[$i]['pass'].'</TD>
                                        <TD class="td-left">  '.$clients[$i]['email'].'</TD>
                                        <TD class="td-center">  '. $clients[$i]['pay_day'].'</TD>                                        
                                        <TD class="td-center">  '.get_name_status($clients[$i]['status_id']).'</TD>
                                            
                                        
                                 </TR>';
                        }
                    ?>
                </tbody>
            </TABLE>
        </center>
    </div>
</div>
