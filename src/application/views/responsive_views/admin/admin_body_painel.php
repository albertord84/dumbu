<br><br>
<form action="<?php echo base_url().'index.php/admin/list_filter_view'?>" method="post">            
            <div class="row">
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
                        <b>Dia de pagamento</b>
                        <select id="pay_day" class="form-control" disabled="true">
                            <option>--SELECT--</option>
                            <option>TODOS</option>
                            <option>01</option><option>02</option><option>03</option><option>04</option><option>05</option>
                            <option>06</option><option>07</option><option>08</option><option>09</option><option>10</option>
                            <option>11</option><option>12</option><option>13</option><option>14</option><option>15</option>
                            <option>16</option><option>17</option><option>18</option><option>19</option><option>20</option>
                            <option>21</option><option>22</option><option>23</option><option>24</option><option>25</option>
                            <option>26</option><option>27</option><option>28</option><option>29</option><option>30</option>
                            <option>31</option>
                        </select>
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
                        <input id="order_key_client" type="email" class="form-control" placeholder="Order Key">
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
                        <!--<td class="center"><b>Perfis ativos</b></td>
                        <td class="center"><b>Perfis eliminados</b></td>-->
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
                    case 6:    return 'PENDING';
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
                                    echo '<b>Dumbu ID: </b>'.$result[$i]['id'].'<br>';
                                    echo '<b>Insta name: </b>'.$result[$i]['name'].'<br>';
                                    echo '<b>Profile: </b>'.$result[$i]['login'].'<br>';
                                    echo '<b>Password: </b>'.$result[$i]['pass'].'<br>';
                                    echo '<b>Email: </b>'.$result[$i]['email'].'<br><br>';
                                    echo '<b>Status: </b><b style="color:red">'.get_name_status($result[$i]['status_id']).'</b><br>';
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
                                echo '</td>';
                                echo '<td style="width:240px; padding:5px">';
                                    echo '<b>CC number: </b>'.$result[$i]['credit_card_number'].'<br>';
                                    echo '<b>CC name: </b>'.$result[$i]['credit_card_name'].'<br>';
                                    echo '<b>CC exp month: </b>'.$result[$i]['credit_card_exp_month'].'<br>';
                                    echo '<b>CC exp year: </b>'.$result[$i]['credit_card_exp_year'].'<br><br>';
                                    echo '<b>Payment day: </b>'.date('d',$result[$i]['pay_day']).'<br>';
                                    echo '<b>Plane: </b> ('.$result[$i]['plane_id'].'):---> <br>'.$result[$i]['initial_val'].' --- '.$result[$i]['normal_val'];
                                    echo '<b>Initial order key: </b>'.$result[$i]['initial_order_key'].'<br>';
                                    echo '<b>Recurrency order key: </b>'.$result[$i]['order_key'].'<br>';
                                echo '</td>';
                                echo '<td style="width:240px; padding:5px">';
                                    if($result[$i]['order_key'])
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['id'].'" class="btn btn-success ladda-button delete-recurence"  data-style="expand-left" data-spinner-color="#ffffff"> <span class="ladda-label">Cancelar recurrencia</span></button><br><br>';
                                    else
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['id'].'" class="btn btn-success ladda-button delete-recurence"  data-style="expand-left" data-spinner-color="#ffffff" disabled="true"> <span class="ladda-label">Cancelar recurrencia</span></button><br><br>';
                                    if($result[$i]['status_id']==4||$result[$i]['status_id']==5){                                        
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['id'].'" class="btn btn-success ladda-button desactive-cliente"  data-style="expand-left" data-spinner-color="#ffffff" disabled="true"> <span class="ladda-label">Desactivar cliente</span></button><br><br>';
                                    }                                        
                                    else    
                                        echo '<button style="width:160px" type="button" id="'.$result[$i]['id'].'" class="btn btn-success ladda-button desactive-cliente"  data-style="expand-left" data-spinner-color="#ffffff"> <span class="ladda-label">Desactivar cliente</span></button><br><br>';                                    
                                    
                                    echo '<a target="_blank" href="'.base_url().'index.php/admin/reference_profile_view?id='.$result[$i]['id'].'" ><button style="width:160px" type="button" class="btn btn-success"> <span class="ladda-label">Perfis de referência</span></button></a><br><br>';
                                    echo '<a target="_blank" href="'.base_url().'index.php/admin/reference_profile_view?id='.$result[$i]['id'].'" ><button style="width:160px" type="button" class="btn btn-success" disabled="true"> <span class="ladda-label">Check login</span></button></a><br><br>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    }               
                ?>
            </table>
        </div>
        <div class="col-xs-1"></div>            
    </div>