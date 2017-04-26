<br><br>
<form action="<?php echo base_url().'index.php/admin/list_filter_view'?>" method="post">            
            <div id="admin_form" class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Status</b>   
                        <select id="client_status" class="form-control">
                            <option value="-1">--SELECT--</option>
                            <option value="0">TODOS OS STATUS</option>
                            <option value="1">ACTIVE</option>
                            <option value="2">BLOCKED_BY_PAYMENT</option>
                            <option value="3">BLOCKED_BY_PASSWORD</option>
                            <option value="4">DELETED</option>
                            <option value="6">PENDENT_BY_PAYMENT</option>
                            <option value="7">UNFOLLOW</option>
                            <option value="8">BEGINNER</option>
                            <option value="9">VERIFY_ACCOUNT</option>
                            <option value="10">BLOCKED_BY_TIME</option>
                        </select>
                    </div> 
                </div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Assinatura (inic)</b>
                        <input id = "signin_initial_date" type="text" class="form-control"  placeholder="DD/MM/YYYY" disabled="true">       
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="center filters">
                        <b>Assinatura (fim)</b>   
                        <input id = "signin_final_date" type="text" class="form-control"  placeholder="DD/MM/YYYY" disabled="true">       
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="center filters">
                         <b>Ano expiração CC</b>
                        <select id="credit_card_expiration_year" class="form-control" disabled="true">
                            <option>--SELECT--</option>
                            <option>TODOS</option>
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
                <div class="col-md-2">
                    <div class="center filters">
                        <b>ID do cliente</b>
                        <input id="credit_card_name" class="form-control" placeholder="Credit Card Name">
                    </div>
                </div>    
                <div class="col-md-1"></div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Perfil do cliente</b>
                        <input id = "profile_client" type="text" class="form-control"  placeholder="Perfil do cliente">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Email do cliente</b>                        
                        <input id="email_client" type="email" class="form-control" placeholder="Email do cliente">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Order Key</b>
                        <input id="order_key_client"  class="form-control" placeholder="Order Key">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Insta ID</b>
                        <input id="ds_user_id" class="form-control" placeholder="ds_user_id">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="center filters">
                        <b>Credit Card name</b>
                        <input id="credit_card_name" class="form-control" placeholder="Credit Card Name">
                    </div>
                </div>
            </div>
            <br><br>            
            

            <div class="row">        
                <div class="center">
                    <button  style="min-width:200px" id = "execute_query" type="button" class="btn btn-success ladda-button"  data-style="expand-left" data-spinner-color="#ffffff">
                        <span class="ladda-label">Listar</span>
                    </button>
               </div>
            </div>
        
        </form>
        <br><br>

        <div class="row">
            <div class="col-xs-1"></div>
            <div class="col-xs-10">
                <table class="table">
                    <tr class="list-group-item-success">
                        <td style="max-width:240px; padding:5px"><b>No.</b></td>
                        <td style="max-width:240px; padding:5px"><b>Dados pessoais</b></td>
                        <td style="max-width:240px; padding:5px"><b>Dados de Instagaram</b></td>
                        <td style="max-width:240px; padding:5px"><b>Dados bancários</b></td>
                        <td style="max-width:240px; padding:5px"><b>Operações</b></td>
                    </tr>
                </table>
            </div>
            <div class="col-xs-1"></div>
        </div>
    
        <?php 
            function get_name_status($val){
                switch ($val){
                    case 1:    return 'ACTIVE';
                    case 2:    return 'BLOCKED_BY_PAYMENT';
                    case 3:    return 'BLOCKED_BY_PASSWORD';
                    case 4:    return 'DELETED';
                    case 5:    return 'INACTIVE';
                    case 6:    return 'PENDENT_BY_PAYMENT';
                    case 7:    return 'UNFOLLOW';
                    case 8:    return 'BEGINNER';
                    case 9:    return 'VERIFY_ACCOUNT';
                    case 10:    return 'BLOCKED_BY_TIME';
                }
            }
        ?>
        
    <div class="row">
        <div class="col-xs-1"></div>
        <div class="col-xs-10">
            <table class="table">
                <?php                    
                    if(isset($result)){
                        $num_rows=count($result);
                        for($i=0;$i<$num_rows;$i++){
                            //echo '<tr id="'.$result[$i]['id'].'" class="my_row">';
                            echo '<tr id="row-client-'.$result[$i]['id'].'" style="visibility: visible;display: block">';
                                echo '<td >';
                                    echo '<br><br><br><br><b>'.($i+1).'</b>';
                                echo '</td>';                                
                                echo '<td style="width:240px; padding:5px">';
                                    echo '<b>Dumbu ID: </b>'.$result[$i]['user_id'].'<br>';
                                    echo '<b>Insta name: </b>'.$result[$i]['name'].'<br>';
                                    echo '<b>Profile: </b>'.$result[$i]['login'].'<br>';
                                    echo '<b>Password: </b>'.$result[$i]['pass'].'<br>';
                                    echo '<b>Email: </b>'.$result[$i]['email'].'<br><br>';
                                    echo '<b>Status: </b><b id="label_status_'.$result[$i]['user_id'].'" style="color:red">'.get_name_status($result[$i]['status_id']).'</b><br>';
                                    echo '<b>Status date: </b>'.date('d-m-Y',$result[$i]['status_date']).'<br>';                                
                                    echo '<b>Sign-in date: </b>'.date('d-m-Y',$result[$i]['init_date']).'<br>';                                    
                                    if($result[$i]['end_date'])
                                        echo '<b>Sign-out date: </b>'.date('d-m-Y',$result[$i]['end_date']).'<br>';
                                    else
                                        echo '<b>Sign-out date: </b>----<br>';
                                echo '</td>';
                                echo '<td style="width:240px; padding:5px">';
                                    echo '<b>InstaG ID: </b>'.$result[$i]['insta_id'].'<br>';
                                    echo '<b>Initial followers: </b>'.$result[$i]['insta_followers_ini'].'<br>';
                                    echo '<b>Initial following: </b>'.$result[$i]['insta_following'].'<br><br>';                                
                                    echo '<b>Actual followers: </b> <a target="_blank"href="https://www.instagram.com/'.$result[$i]['login'].'/">View in IG</a><br>';
                                    echo '<b>Actual following: </b> <a target="_blank"href="https://www.instagram.com/'.$result[$i]['login'].'/">View in IG</a><br>';                                
                                    echo '<br><br>';
                                    if($result[$i]['ticket_peixe_urbano']!=NULL){
                                        if($result[$i]['ticket_peixe_urbano_status_id']==='1'){
                                            echo '<button style="width:160px" title="CONFERIDO" type="button" class="btn btn-success" alt="" data-toggle="modal" data-target="#myModal_1"> <span class="ladda-label">Peixe urbano</span></button><br><br>';
                                            echo '<div class="modal fade" style="top:30%" id="myModal_1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog modal-sm" role="document">                                                          
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                      <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                      <h4 class="modal-title" id="myModalLabel">CUPOM Peixe Urbano</h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                        CUPOM: '.$result[$i]['ticket_peixe_urbano'].'                                                                      
                                                                    <select id="select_option_ticket_peixe_urbano_status_id_1" class="form-control" disabled="true">
                                                                        <option value="1" selected="true">CONFERIDO</option>
                                                                        <option value="2" >PENDENTE</option>
                                                                        <option value="3">ERRADO</option>
                                                                    </select>                                                                      
                                                                  </div>
                                                                  <div class="modal-footer">                                                                      
                                                                      <button disabled="true" id="btn_change_ticket_peixe_urbano_status_id_1" type="button" class="btn btn-primary text-center ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                                                          <span class="ladda-label"><div style="color:white; font-weight:bold">Mudar Status</div></span>
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                          </div>                                                        
                                                    </div> '; 
                                        }else
                                        if($result[$i]['ticket_peixe_urbano_status_id']==='2'){
                                            echo '<button id="btn_cupom_'.$result[$i]['user_id'].'" style="width:160px" title="PENDENTE" type="button" class="btn btn-primary" alt="" data-toggle="modal" data-target="#myModal_2"> <span class="ladda-label">Peixe urbano</span></button><br><br>';                                            
                                            echo '<div class="modal" style="top:30%" id="myModal_2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog modal-sm" role="document">                                                          
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                      <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                      <h4 class="modal-title" id="myModalLabel">CUPOM Peixe Urbano</h4>
                                                                  </div>
                                                                  <div id="cupom_container" class="modal-body">
                                                                        <p id="'.$result[$i]['user_id'].'"> CUPOM: '.$result[$i]['ticket_peixe_urbano'].'</p>
                                                                        <select id="select_option_ticket_peixe_urbano_status_id" class="form-control">
                                                                            <option id="option_confered" value="1">CONFERIDO</option>
                                                                            <option id="option_pending" value="2" selected="true">PENDENTE</option>
                                                                            <option id="option_wrong" value="3">ERRADO</option>
                                                                        </select>                                                                      
                                                                  </div>
                                                                  <div class="modal-footer">                                                                      
                                                                      <button id="btn_change_ticket_peixe_urbano_status_id" type="button" class="btn btn-primary text-center ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                                                          <span class="ladda-label"><div style="color:white; font-weight:bold">Mudar Status</div></span>
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                        </div>                                                        
                                                  </div> ';                                           
                                        }else
                                        if($result[$i]['ticket_peixe_urbano_status_id']==='3'){
                                            echo '<button style="width:160px" title="ERRADO" type="button" class="btn btn-danger" alt="" data-toggle="modal" data-target="#myModal_3"> <span class="ladda-label">Peixe urbano</span></button><br><br>';                                                                                        
                                            echo '<div class="modal fade" style="top:30%" id="myModal_3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                        <div class="modal-dialog modal-sm" role="document">                                                          
                                                              <div class="modal-content">
                                                                  <div class="modal-header">
                                                                      <button id="btn_modal_close" type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                      <h4 class="modal-title" id="myModalLabel">CUPOM Peixe Urbano</h4>
                                                                  </div>
                                                                  <div class="modal-body">
                                                                        CUPOM: '.$result[$i]['ticket_peixe_urbano'].'                                                                      
                                                                    <select id="select_option_ticket_peixe_urbano_status_id_3" class="form-control" disabled="true">
                                                                        <option value="1" >CONFERIDO</option>
                                                                        <option value="2" >PENDENTE</option>
                                                                        <option value="3" selected="true">ERRADO</option>
                                                                    </select>                                                                      
                                                                  </div>
                                                                  <div class="modal-footer">                                                                      
                                                                      <button disabled="true" id="btn_change_ticket_peixe_urbano_status_id_3" type="button" class="btn btn-primary text-center ladda-button" data-style="expand-left" data-spinner-color="#ffffff">
                                                                          <span class="ladda-label"><div style="color:white; font-weight:bold">Mudar Status</div></span>
                                                                      </button>
                                                                  </div>
                                                              </div>
                                                          </div>                                                        
                                                    </div> '; 
                                        }
                                    }
                                        
                                    
                                echo '</td>';
                                echo '<td style="width:240px; padding:5px">';
                                    echo '<b>CC number: </b>'.$result[$i]['credit_card_number'].'<br>';
                                    echo '<b>CC name: </b>'.$result[$i]['credit_card_name'].'<br>';
                                    echo '<b>CC exp month: </b>'.$result[$i]['credit_card_exp_month'].'<br>';
                                    echo '<b>CC exp year: </b>'.$result[$i]['credit_card_exp_year'].'<br><br>';
                                    echo '<b>Payment day: </b>'.date('d',$result[$i]['pay_day']).'<br>';                                    
                                    if($result[$i]['initial_val'])
                                        echo '<b>Plane: </b> ('.$result[$i]["plane_id"].') <br> '.$result[$i]['initial_val'].' | '.$result[$i]['normal_val'].'<br>';
                                    else
                                        echo '<b>Plane: </b> ('.$result[$i]["plane_id"].') <br> *** | '.$result[$i]['normal_val'].'<br>';
                                    
                                    //echo '<b>Initial order key: </b>'.$result[$i]['initial_order_key'].'<br>';
                                    echo '<b>Initial order key: </b><a href="https://dashboard.mundipagg.com/#/9d0703f8-98a6-4f61-a28f-6be3771f3510/live/transactions?currentTab=creditCardTransactions&pageNumber=1&sortField=CreateDate&sortMode=DESC&pageSize=20&identifier='.$result[$i]['initial_order_key'].'" target="_blank">'.$result[$i]['initial_order_key'].'</a><br>';
                                    //echo '<b>Recurrency order key: </b>'.$result[$i]['order_key'].'<br>';
                                    echo '<b>Recurrency order key: </b><a href="https://dashboard.mundipagg.com/#/9d0703f8-98a6-4f61-a28f-6be3771f3510/live/transactions?currentTab=creditCardTransactions&pageNumber=1&sortField=CreateDate&sortMode=DESC&pageSize=20&identifier='.$result[$i]['order_key'].'" target="_blank">'.$result[$i]['order_key'].'</a><br>';
                                    //echo '<b>Pending order key: </b>'.$result[$i]['pending_order_key'].'<br>';
                                    echo '<b>Pending order key: </b><a href="https://dashboard.mundipagg.com/#/9d0703f8-98a6-4f61-a28f-6be3771f3510/live/transactions?currentTab=creditCardTransactions&pageNumber=1&sortField=CreateDate&sortMode=DESC&pageSize=20&identifier='.$result[$i]['pending_order_key'].'" target="_blank">'.$result[$i]['pending_order_key'].'</a><br>';
                                echo '</td>';
                                echo '<td style="width:240px; padding:5px">';
                                    if($result[$i]['order_key'])
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['user_id'].'" class="btn btn-success ladda-button delete-recurence"  data-style="expand-left" data-spinner-color="#ffffff"> <span class="ladda-label">Cancelar recurrencia</span></button><br><br>';
                                    else
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['user_id'].'" class="btn btn-success ladda-button delete-recurence"  data-style="expand-left" data-spinner-color="#ffffff" disabled="true"> <span class="ladda-label">Cancelar recurrencia</span></button><br><br>';
                                    if($result[$i]['status_id']==4||$result[$i]['status_id']==5){                                        
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['user_id'].'" class="btn btn-success ladda-button desactive-cliente"  data-style="expand-left" data-spinner-color="#ffffff" disabled="true"> <span class="ladda-label">Desactivar cliente</span></button><br><br>';
                                    }                                        
                                    else    
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['user_id'].'" class="btn btn-success ladda-button desactive-cliente"  data-style="expand-left" data-spinner-color="#ffffff"> <span class="ladda-label">Desactivar cliente</span></button><br><br>';                                                                        
                                    echo '<a target="_blank" href="'.base_url().'index.php/admin/reference_profile_view?id='.$result[$i]['user_id'].'" ><button style="width:160px" type="button" class="btn btn-success"> <span class="ladda-label">Perfis de referência</span></button></a><br><br>';
                                    
                                echo '</td>';
                            echo '</tr>';
                        }
                    }               
                ?>
            </table>
        </div>
        <div class="col-xs-1"></div>            
    </div>