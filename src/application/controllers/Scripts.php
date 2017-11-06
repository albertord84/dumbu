<?php

class Scripts extends CI_Controller {
    
    public function t1() {
	$this->load->model('class/client_model');
	$query="SELECT * FROM clients
            INNER JOIN users ON clients.user_id = users.id
            INNER JOIN plane ON clients.plane_id = plane.id
            WHERE 
                    users.role_id = 2
            AND users.status_id <> 4
            AND users.status_id <> 8
            AND users.status_id < 11
            AND (clients.actual_payment_value = '' OR clients.actual_payment_value is null)";
	$result=$this->client_model->execute_sql_query($query);
	foreach ($result as $row ) {
		$this->client_model->update_client($row['user_id'], array(
			'actual_payment_value' => $row['normal_val']));
	}
	echo count($result);
}
    
    public function paypal() {
        $this->load->view('test_view');
    }
    
    public function update_client_after_retry_payment_success($user_id) {  
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();        
        $this->load->model('class/client_model');
        $this->load->model('class/user_model');
        $this->load->model('class/user_status');
        //1. recuperar el cliente y su plano
        $client = $this->client_model->get_all_data_of_client($user_id)[0];
        $plane = $this->client_model->get_plane($client['plane_id'])[0];
        //3. crear nueva recurrencia en la Mundipagg para el proximo mes   
        date_default_timezone_set('Etc/UTC');
        $payment_data['credit_card_number'] = $client['credit_card_number'];
        $payment_data['credit_card_name'] = $client['credit_card_name'];
        $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
        $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
        $payment_data['credit_card_cvc'] = $client['credit_card_cvc'];
        if($client['actual_payment_value']!='' && $client['actual_payment_value']!=null)
            $payment_data['amount_in_cents'] = $client['actual_payment_value'];
        else
            $payment_data['amount_in_cents'] = $plane['normal_val'];
        $payment_data['pay_day'] = strtotime("+1 month", time());
        $resp = $this->check_recurrency_mundipagg_credit_card($payment_data, 0);
        //4. salvar nuevos pay_day e order_key
        if (is_object($resp) && $resp->isSuccess()) {
            //2. eliminar recurrencia actual en la Mundipagg
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'initial_order_key' => '',
                'order_key' => $resp->getData()->OrderResult->OrderKey,
                'pay_day' => $payment_data['pay_day']));
            echo '<br>Client '.$user_id.' updated correctly. New order key is:  '.$resp->getData()->OrderResult->OrderKey;
            //5. actualizar status del cliente
            $data_insta = $this->is_insta_user($client['login'], $client['pass']);
            if($data_insta['status'] === 'ok' && $data_insta['authenticated']) {
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::ACTIVE
                ));
                echo ' STATUS = '.user_status::ACTIVE;
            } else
            if ($data_insta['status'] === 'ok' && !$data_insta['authenticated']){
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
                echo ' STATUS = '.user_status::BLOCKED_BY_INSTA;
            }
            else{
                $this->user_model->update_user($user_id, array(
                    'status_date' => time(),
                    'status_id' => user_status::BLOCKED_BY_INSTA
                ));
                echo ' STATUS = '.user_status::VERIFY_ACCOUNT;
            }
        } else{
            $this->client_model->update_user($user_id, array(            
                'status_date' => time(),
                'status_id' => 1)); 
            $this->delete_recurrency_payment($client['order_key']);
            $this->client_model->update_client($user_id, array(
                'initial_order_key' => '',
                'order_key' => '',
                'observation' => 'NÃ‚O CONEGUIDO DURANTE RETENTATIVA - TENTAR CRIAR ANTES DE DATA DE PAGAMENTO',
                'order_key' => $payment_data['pay_day'],
                'observation' => 'NÂO CONEGUIDO DURANTE RETENTATIVA - TENTAR CRIAR ANTES DE DATA DE PAGAMENTO',
                'pay_day' => $payment_data['pay_day']));
            //TO-DO:Ruslan: inserta una pendencia automatica aqui
            
            if (is_object($resp))
                echo '<br>Client '.$user_id.' DONT updated. Wrong order key is:  '.$resp->getData()->OrderResult->OrderKey;
            else 
                echo '<br>Client '.$user_id.' DONT updated. Missing order key';
        }
        
        $this->client_model->update_client($user_id, array(            
            'initial_order_key' => '')); 
    }
           
    public function buy_retry_for_clients_with_puchase_counter_in_zero() {
        $this->load->model('class/client_model');
        $cl=$this->client_model->beginners_with_purchase_counter_less_value(9);
        for($i=1;$i<count($cl);$i++){            
            $clients=$cl[$i];
            $datas=array('client_login'=>$clients['login'],
                         'client_pass'=>$clients['pass'],
                         'client_email'=>$clients['email']);
            $resp=$this->check_user_for_sing_in($datas);
            
            if($resp['success']){
                $datas=array(
                    'pk'=>$clients['user_id'],
                    'credit_card_number'=>$clients['credit_card_number'],
                    'credit_card_cvc'=>$clients['credit_card_cvc'],
                    'credit_card_name'=>$clients['credit_card_name'],
                    'credit_card_exp_month'=>$clients['credit_card_exp_month'],
                    'credit_card_exp_year'=>$clients['credit_card_exp_year'],

                    'plane_type'=>$clients['plane_id'],
                    'ticket_peixe_urbano'=>$clients['ticket_peixe_urbano'],
                    'user_email'=>$clients['email'],
                    'insta_name'=>$clients['name'],
                    'user_login'=>$clients['login'],
                    'user_pass'=>$clients['pass'],
                );            
                $resp=$this->check_client_data_bank($datas);
                if($resp['success']){
                    echo 'Cliente ('.$clients['login'].')   '.$clients['login'].'comprou satisfatoriamente\n<br>';
                } else{
                    $this->client_model->update_client($clients['user_id'], array(
                        'purchase_counter' => -100 ));
                    echo 'Cliente '.$clients['login'].' ERRADO\n<br>';
                }
            } else{
                $this->client_model->update_client($clients['user_id'], array(
                        'purchase_counter' => -100 ));
                echo 'Cliente ('.$clients['login'].') '.$clients['login'].'nÃ£ passou passo 1\n<br>';
            }
        }
    }
        
    public function Pedro(){
        $this->load->model('class/user_model');
        $users= $this->user_model->get_all_users();
        $L=count($users);
        echo 'Num clientes '.$L."<br>";
        $file = fopen("media_pro.txt","w");
        for($i=0;$i<$L;$i++){
            $result=$this->user_model->get_daily_report($users[$i]['id']);
            $Ndaily_R=count($result);
            //echo $i.'----'.$users[$i]['id'].'-----'.count($users).'<br>';
            $N=0; $sum=0;
            if($Ndaily_R>5){
                for($j=1;$j<$Ndaily_R;$j++){
                    $diferencia = $result[$j]['date']-$result[$j-1]['date']; 
                    $horas = (int)($diferencia/(60*60)); 
                    if( $horas>20 && $horas <=30){
                        $N++;
                        $sum=$sum+($result[$j]['followers'] - $result[$j-1]['followers']);
                    }
                }
                //fwrite($file, ($users[$i]['id'].'---'.$users[$i]['status_id'].'---'.$users[$i]['plane_id'].'---'.((int)($sum/$N)).'<br>'));
                echo $users[$i]['id'].'---'.$users[$i]['status_id'].'---'.$users[$i]['plane_id'].'---'.((int)($sum/$N)).'<br>';
                
            }            
        }
        echo 'fin';
        fclose($file);
    }
    
    public function update_ds_user_id() {
        $this->load->model('class/client_model');
        $resul=$this->client_model->select_white_list_model();
        foreach ($resul as $key => $value) {
            $data_insta = $this->check_insta_profile($value['profile']);
            $this->client_model->update_ds_user_id_white_list_model($value['id'],$data_insta->pk);
        }
    }   
    
    public function login_all_clients(){
        $this->load->model('class/user_model');
        $a=$this->user_model->get_all_dummbu_clients();
        $N=count($a);
        for($i=0;$i<$N;$i++){
            $st=$a[$i]['status_id'];
            if($st!=='4' && $st!=='8' && $st!=='11' && $a[$i]['role_id']==='2'){
                echo $i;
                $login=$a[$i]['login'];
                $pass=$a[$i]['pass'];
                $datas['user_login']=$login;
                $datas['user_pass']=$pass;
                $result= $this->user_do_login($datas);
            }
        }
    }
    
    public function time_of_live() {
        $this->load->model('class/user_model');
        $result=$this->user_model->time_of_live_model(4);
        $response=array(
            '0-2-dias'=>array(0,0,0,0,0),
            '2-30-dias'=>array(0,0,0,0,0),
            '30-60-dias'=>array(0,0,0,0,0),
            '60-90-dias'=>array(0,0,0,0,0),
            '90-120-dias'=>array(0,0,0,0,0),
            '120-150-dias'=>array(0,0,0,0,0),
            '150-180-dias'=>array(0,0,0,0,0),
            '180-210-dias'=>array(0,0,0,0,0),
            '210-240-dias'=>array(0,0,0,0,0),
            '240-270-dias'=>array(0,0,0,0,0),
            'mais-270'=>array(0,0,0,0,0));
        
        foreach ($result as $user) {
            $difference=$user['end_date']-$user['init_date'];
            $second = 1;
            $minute = 60*$second;
            $hour   = 60*$minute;
            $day    = 24*$hour;
            
            $plane=$user['plane_id'];
            
            $num_days=floor($difference/$day);            
            if ($num_days<=2) 
                $response['0-2-dias'][$plane]=$response['0-2-dias'][$plane]+1;
            else
            if ($num_days>2 &&$num_days<=30) 
                $response['2-30-dias'][$plane]=$response['2-30-dias'][$plane]+1;
            else
            if ($num_days>30 &&$num_days<=60) 
                $response['30-60-dias'][$plane]=$response['30-60-dias'][$plane]+1;
            else
            if ($num_days>60 &&$num_days<=90) 
                $response['60-90-dias'][$plane]=$response['60-90-dias'][$plane]+1;            
            else
            if ($num_days>90 &&$num_days<=120) 
                $response['90-120-dias'][$plane]=$response['90-120-dias'][$plane]+1;
            else
            if ($num_days>120 &&$num_days<=150) 
                $response['120-150-dias'][$plane]=$response['120-150-dias'][$plane]+1;
            else
            if ($num_days>150 &&$num_days<=180) 
                $response['150-180-dias'][$plane]=$response['150-180-dias'][$plane]+1;
            else
            if ($num_days>180 &&$num_days<=210) 
                $response['180-210-dias'][$plane]=$response['180-210-dias'][$plane]+1;
            else
            if ($num_days>210 &&$num_days<=240) 
                $response['210-240-dias'][$plane]=$response['210-240-dias'][$plane]+1;
            else
            if ($num_days>240 &&$num_days<=270) 
                $response['240-270-dias'][$plane]=$response['240-270-dias'][$plane]+1;
            else 
                $response['mais-270'][$plane]=$response['mais-270'][$plane]+1;
        }        
        var_dump($response);        
    }
    
    public function users_by_month_and_plane() {
        $status = $this->input->get()['status'];
        $this->load->model('class/user_model');
        $result=$this->user_model->time_of_live_model($status);
                
        foreach ($result as $user) {
            $month=date("n", $user['init_date']);
            $year=date("Y", $user['init_date']);
            $cad=$month.'-'.$year.'<br>';
            $plane_id=$user['plane_id'];
            if(!isset($r[$cad][$plane_id] ))
                $r[$cad][$plane_id]=0;
            else
                $r[$cad][$plane_id]=$r[$cad][$plane_id]+1;
        }        
        var_dump($r);        
    }
    
    public function capturer_and_recurrency_for_blocked_by_payment(){
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $result=$this->client_model->get_all_clients_by_status_id(2);        
        foreach ($result as $client) {
            $aa=$client['login'];
            $status_id=$client['status_id'];
            if($client['retry_payment_counter']<13){
                if($client['credit_card_number']!=null && $client['credit_card_number']!=null && 
                        $client['credit_card_name']!=null && $client['credit_card_name']!='' && 
                        $client['credit_card_exp_month']!=null && $client['credit_card_exp_month']!='' && 
                        $client['credit_card_exp_year']!=null && $client['credit_card_exp_year']!='' && 
                        $client['credit_card_cvc']!=null && $client['credit_card_cvc']!='' ){

                    $pay_day = time();
                    $payment_data['credit_card_number'] =$client['credit_card_number'];
                    $payment_data['credit_card_name'] = $client['credit_card_name'];
                    $payment_data['credit_card_exp_month'] = $client['credit_card_exp_month'];
                    $payment_data['credit_card_exp_year'] = $client['credit_card_exp_year'];
                    $payment_data['credit_card_cvc'] = $client['credit_card_cvc'];
                    
                    $difference=$pay_day-$client['init_date'];
                    $second = 1;
                    $minute = 60*$second;
                    $hour   = 60*$minute;
                    $day    = 24*$hour;  
                    $num_days=floor($difference/$day); 

                    $payment_data['amount_in_cents'] =0;
                    if($client['ticket_peixe_urbano']==='AMIGOSDOPEDRO' || $client['ticket_peixe_urbano']==='INSTA15D'){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                    } else
                    if( ($client['ticket_peixe_urbano']==='INSTA50P' ||
                            $client['ticket_peixe_urbano']==='BACKTODUMBU' ||
                            $client['ticket_peixe_urbano']==='BACKTODUMBU-DNLO' ||
                            $client['ticket_peixe_urbano']==='BACKTODUMBU-EGBTO')){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                        if($num_days<=33)
                            $payment_data['amount_in_cents']=$payment_data['amount_in_cents']/2;
                    } else
                    if($client['ticket_peixe_urbano']==='DUMBUDF20'){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                        $payment_data['amount_in_cents']=($payment_data['amount_in_cents']*8)/10;
                    } else
                    if($client['ticket_peixe_urbano']==='INSTA-DIRECT' || $client['ticket_peixe_urbano']==='MALADIRETA'){
                        $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);
                    } else                
                    if($client['actual_payment_value']!=null && 
                            $client['actual_payment_value']!='null' && 
                            $client['actual_payment_value']!='' && 
                            $client['actual_payment_value']!=NULL
                            && $payment_data['amount_in_cents'] ==0
                            )
                        $payment_data['amount_in_cents'] = $client['actual_payment_value'];
                    else
                       $payment_data['amount_in_cents']=$this->client_model->get_normal_pay_value($client['plane_id']);

                    $resp = $this->check_mundipagg_credit_card($payment_data);
                    if((is_object($resp) && $resp->isSuccess()&& $resp->getData()->CreditCardTransactionResultCollection[0]->CapturedAmountInCents>0)){
                        $this->update_client_after_retry_payment_success($client['user_id']);
                        $this->client_model->update_client($client['user_id'], array(
                            'retry_payment_counter' => 0));
                    }else{
                        $this->client_model->update_client($client['user_id'], array(
                        'retry_payment_counter' => $client['retry_payment_counter']+1));
                    }
                }
            } else{
                try{
                    $this->delete_recurrency_payment($client['initial_order_key']);                
                    $this->delete_recurrency_payment($client['order_key']);                
                    $this->client_model->update_user($client['user_id'], array(  
                        'end_date' => time(),
                        'status_date' => time(),
                        'status_id' => 4));
                    $this->client_model->update_client($client['user_id'], array(
                            'observation' => 'Cancelado automaticamente por mais te 10 retentativas de pagamento sem sucessso'));
                    echo 'Client '.$client['user_id'].' cancelado por maxima de retentativas';
                } catch (Exception $e){
                    echo 'Error deleting cliente '.$client['user_id'].' in database';
                }
            }
        }
    }

    public function buy_tester(){
        
    }
    
    public function update_all_retry_clients(){
        $array_ids=array(176, 192, 419, 1290, 1921, 3046, 3179, 3218, 3590, 12707, 564, 3486, 671, 2300, 4123, 4466, 12356, 12373, 12896, 13786, 23410,25073, 15746, 23636, 24426, 15745);
        $N=count($array_ids);
        for($i=0;$i<$N;$i++){
            $this->update_client_after_retry_payment_success($array_ids[$i]);
        }
    }
    
    
    //end
      
}
