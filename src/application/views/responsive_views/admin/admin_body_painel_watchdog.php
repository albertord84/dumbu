<br><br>
<form action="<?php echo base_url().'index.php/admin/list_filter_view'?>" method="post">  
<script type="text/javascript" src="<?php echo base_url() . 'assets/canvasjs-1.9.6/canvasjs.min.js'; ?>"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/js/chart.js'; ?>"></script>   
        <div id="login_container2">
            <div class="center filters">
               <b>WATCHDOG</b> 
            <div id="admin_form" class="row"></div>
            </div>
           
            <div id="admin_form" class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <div class="center filters">
                    <div class="center filters">
                        <b>ID do cliente</b>
                        <input id="user_id" class="form-control" placeholder="ID do cliente" value="<?php if (isset($form_filter) && $form_filter[user_id] != "") { echo $form_filter[user_id]; } ?>">
                    </div>
                        
                    </div> 
                </div>
                <div class="col-md-4">
                    <div class="center filters">
                    <!--<b>Assinatura (inic)</b>
                        <input id = "signin_initial_date" type="text" class="form-control"  placeholder="MM/DD/YYYY" >-->
                        <b>Data da busca</b>
                    </div>
                    <div class="col-xs-1">
                        <b>do</b>
                    </div>
                    <div class="col-xs-5">
                        <input id="date_from" type="text"  name="date_from" placeholder="mm/dd/yyyy" class="form-control" value="<?php if (isset($form_filter) && $form_filter[date_from] != "") { echo $form_filter[date_from]; } ?>">
                    </div>
                    <div class="col-xs-1">
                        <b>até</b>
                    </div>
                    <div class="col-xs-5">
                        <input id="date_to" type="text" name="date_to" placeholder="mm/dd/yyyy" class="form-control" value="<?php if (isset($form_filter) && $form_filter[date_to] != "") { echo $form_filter[date_to]; } ?>">
                    </div>
                </div>
                
                 <div class="col-md-2">
                    <div class="center">
                        <br>
                        <button  style="min-width:200px" id = "execute_querywd" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                            <span class="ladda-label">Listar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
    <hr>
    
        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-10">
                <?php
                    if(isset($result)){
                        $num_rows=count($result);
                        echo '<br><b style="color:green">User id: </b><b>'.$form_filter['user_id'].'</b>';
                        echo '<br><p><b style="color:green">No de acciones: </b><b>'.$num_rows.'</b></p><br>';
                    }
                ?>
            </div>
            <div class="col-xs-1"></div>
        </div>
        
                

        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-4">
              <div class="row"></div>
                <table class="table">
                    <tr class="list-group-item-success">
                        <td style="max-width:240px; padding:5px"><b>Action</b></td>
                        <td style="max-width:240px; padding:5px"><b>Data</b></td>
                        <td style="max-width:240px; padding:5px"></td>
                    </tr>
                </table>
                
            </div>
            <div class="col-xs-1"></div>
        </div>
        
         
     <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10">
            <table class="table">
                <?php 
                    if (isset($result) && $form_filter['query'] == 1) {                        
                        for($i=0;$i<$num_rows;$i++){
                            //if ($result[$i]['date'] >= strtotime('date_from'.'00:00:01') && $result[$i]['date'] <= strtotime('date_to'.'23:59:59')&& $form_filter['query'] == 1) { 
                            //echo '<tr id="'.$result[$i]['id'].'" class="my_row">';
                            echo '<tr id="row-client-'.$result[$i]['id'].'" style="visibility: visible;display: block">';
                                
                                echo '<td style="width:240px; padding:5px">';
                                    echo $result[$i]['action'].'<br>';
                                echo '</td>';
                                
                                echo '<td style="width:240px; padding:5px">';
                                    echo date('d-m-Y h:i:sa',$result[$i]['date']).'<br>';                                    
                                echo '</td>';
                                                              
                           echo '</tr>';
                            //}
                        }
                   }
                    else if ($form_filter['query'] == 2) {
                        for ($i = 0; $i < $num_rows; $i++) {
                            echo $result[$i]['email'].'<br>';
                        }
                    }
                ?>
            </table>
        
    </div>
        
        
    
    
    