<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function do_payment($payment_data) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        // Check client payment in mundipagg
        $Payment = new \dumbu\cls\Payment();
        $response = $Payment->create_recurrency_payment($payment_data);
        // Save Order Key
        var_dump($response->Data->OrderResult->OrderKey);
    }

    public function check_payment() {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        echo "Check Payment Inited...!<br>\n";
        echo date("Y-m-d h:i:sa");

        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        // Get all users
        $this->db->select('*');
        $this->db->from('clients');
        $this->db->join('users', 'clients.user_id = users.id');
        // TODO: COMENT
//        $this->db->where('id', "1");
        $this->db->where('role_id', user_role::CLIENT);
        $this->db->where('status_id <>', user_status::DELETED);
        $this->db->where('status_id <>', user_status::BEGINNER);
        $this->db->where('status_id <>', user_status::DONT_DISTURB);
        // TODO: COMMENT MAYBE
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_PAYMENT);  // This status change when the client update his pay data
//        $this->db->or_where('status_id', user_status::ACTIVE);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_INSTA);
//        $this->db->or_where('status_id', user_status::VERIFY_ACCOUNT);
//        $this->db->or_where('status_id', user_status::UNFOLLOW);
//        $this->db->or_where('status_id', user_status::BLOCKED_BY_TIME);
//        $this->db->or_where('status_id', user_status::INACTIVE);
//        $this->db->or_where('status_id', user_status::PENDING);
        $clients = $this->db->get()->result_array();
        // Check payment for each user
        foreach ($clients as $client) {
            $clientname = $client['name'];
            $clientid = $client['user_id'];
            $promotional_days = $GLOBALS['sistem_config']->PROMOTION_N_FREE_DAYS;
            $init_date_2d = new DateTime();
            $init_date_2d = $init_date_2d->setTimestamp(strtotime("+$promotional_days days", $client['init_date']));
            $testing = new DateTime("now") < $init_date_2d;
            if ($client['order_key'] != NULL) {
                if (!$testing) { // Not in promotial days
                    $checked = $this->check_client_payment($client);
                    if ($checked) {
                        //var_dump($client);
                        print "\n<br>Client in day: $clientname (id: $clientid)<br>\n";
                    } else {
                        print "\n<br>----Client with payment issue: $clientname (id: $clientid)<br>\n<br>\n<br>\n";
                    }
                }
            } else {
                print "\n<br>Client without ORDER KEY!!!: $clientname (id: $clientid)<br>\n";
            }
        }

        echo "\n\n<br>Job Done!" . date("Y-m-d h:i:sa") . "\n\n";
    }

    public function check_client_initial_payment($initial_order_key) {
        $Payment = new \dumbu\cls\Payment();
        $result = $Payment->check_payment($initial_order_key);
        if (is_object($result) && $result->isSuccess()) {
            $data = $result->getData();
            //var_dump($data);
            $SaleDataCollection = $data->SaleDataCollection[0];
            foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                // Get last client payment
                //$SaleData = $SaleDataCollection->CreditCardTransactionDataCollection[0];
                $SaleDataDate = new DateTime($SaleData->DueDate);
                if ($SaleData->CapturedAmountInCents != NULL) {
                    return TRUE;
                }
                //var_dump($SaleData);
            }
        }
        return FALSE;
    }

    public function check_client_payment($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new dumbu\cls\system_config();
        // Check client payment in mundipagg
        $Payment = new \dumbu\cls\Payment();
        // Check outhers payments
        $IOK_ok = $client['initial_order_key'] ? $this->check_client_initial_payment($client['initial_order_key']) : TRUE;
//        $POK_ok = $client['pending_order_key'] ? $this->check_client_initial_payment($client['pending_order_key']) : TRUE;
        $IOK_ok = $IOK_ok || $POK_ok; // Whichever is paid
        // Check normal recurrency payment
        $result = $Payment->check_payment($client['order_key']);
        if (is_object($result) && $result->isSuccess()) {
            $data = $result->getData();
            //var_dump($data);
            $SaleDataCollection = $data->SaleDataCollection[0];
            $LastSaledData = NULL;
            // Get last client payment
            foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                $SaleDataDate = new DateTime($SaleData->DueDate);
//                $LastSaleDataDate = new DateTime($LastSaledData->DueDate);
                //$last_payed_date = DateTime($LastSaledData->DueDate);
                if ($SaleData->CapturedAmountInCents != NULL && ($LastSaledData == NULL || $SaleDataDate > new DateTime($LastSaledData->DueDate))) {
                    $LastSaledData = $SaleData;
                }
                //var_dump($SaleData);
            }
            $now = DateTime::createFromFormat('U', time());
            $this->load->model('class/user_status');
            $this->load->model('class/user_model');
            if ($LastSaledData != NULL) { // if have payment
                // Check difference between last payment and now
                $last_saled_date = new DateTime($LastSaledData->DueDate);
                $diff_info = $last_saled_date->diff($now);
                //var_dump($diff_info);
                // Diff in days
                $diff_days = $diff_info->days;
//                $diff_days = ($diff_info->m * 30) + $diff_info->days;
                print "\n<br> Diff days: $diff_days";
                // TODO: Put 34 in system_config
//                $diff_days = 35;
//                $client['email'] = 'albertord84@gmail.com';
                if ($diff_days > 34) { // Limit to bolck
                    //Block client by paiment
                    if ($client['status_id'] != user_status::BLOCKED_BY_PAYMENT) {
                        $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT));
                        $this->send_payment_email($client, 0);
                        print "This client was blocked by payment just now: " . $client['user_id'];
                        // TODO: Put 31 in system_config    
                    }
                } elseif ($diff_days > 31) { // Limit to advice
                    // Send email to Client
                    // TODO: Think about send email
                    print "Diff in days bigger tham 31 days: $diff_days ";
                    $this->load->model('class/dumbu_system_config');
                    $this->send_payment_email($client, 34 - $diff_days + 1);
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::PENDING));
                } else {
//                    print_r($client);
                    if ($client['status_id'] == user_status::PENDING || $client['status_id'] == user_status::BLOCKED_BY_PAYMENT) {
                        $this->user_model->update_user($client['user_id'], array('status_id' => user_status::ACTIVE));
                    }
                    return TRUE;
                }
            } else if ($client['status_id'] != user_status::BLOCKED_BY_PAYMENT) { // if have not payment jet
                print "\n<br> LastSaledData = NULL";
                $pay_day = new DateTime();
                $pay_day->setTimestamp($client['pay_day']);
                $diff_info = $pay_day->diff($now);
                $diff_days = $diff_info->days;
//                $diff_days = ($diff_info->m * 30) + $diff_info->days;
                // TODO: check whend not pay and block user
                if ($now > $pay_day) {
                    print "\n<br>This client has not payment since '$diff_days' days (PROMOTIONAL?): " . $client['name'] . "<br>\n";
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::PENDING));
                    // TODO: limit email by days diff
                    //$diff_days = 6;
                    if ($diff_days >= 0) {
//                        print "\n<br>Email sent to " . $client['email'] . "<br>\n";
                        $this->load->model('class/dumbu_system_config');
                        $this->send_payment_email($client, dumbu_system_config::DAYS_TO_BLOCK_CLIENT - $diff_days);
                        // TODO: limit email by days diff
                        if ($diff_days >= dumbu_system_config::DAYS_TO_BLOCK_CLIENT) {
                            //Block client by paiment
                            $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT));
                            ///////////////////////////////////////$this->send_payment_email($client);
                            print "This client was blocked by payment just now: " . $client['user_id'];
                            // TODO: Put 31 in system_config    
                        }
                    }
                } else if ($IOK_ok === FALSE) { // Si está en fecha de promocion pero no pagó initial order key
                    //Block client by paiment
                    $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT));
                    $this->send_payment_email($client, 0);
                    ///////////////////////////////////////$this->send_payment_email($client);
                    print "This client was blocked by payment just now: " . $client['user_id'];
                }
            }
        } else {
            $bool = is_object($result);
            $str = is_object($result) && is_callable($result->getData()) ? json_encode($result->getData()) : "NULL";
//            throw new Exception("Payment error: " . $str);
            print ("\n<br>Payment error: " . $str . " \nClient name: " . $client['name'] . "<br>\n");
        }
        return FALSE;
//        print "<pre>";
//        print json_encode($result->getData(), JSON_PRETTY_PRINT);
//        print "</pre>";
    }

    public function send_payment_email($client, $diff_days = 0) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        //$datas = $this->input->post();
        $result = $this->Gmail->send_client_payment_error($client['email'], $client['name'], $client['login'], $client['pass'], $diff_days);
        if ($result['success']) {
            $clientname = $client['name'];
            print "<br>Email send to client: $clientname<br>";
        } else {
            print "<br>Email NOT sent to: " . json_encode($client, JSON_PRETTY_PRINT);
//            throw new Exception("Email not sent to: " . json_encode($client));
        }
    }

}
