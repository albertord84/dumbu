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
        // TODO: UNCOMENT
        $this->db->where('role_id', user_role::CLIENT);
        $this->db->where('status_id <>', user_status::DELETED);
        $this->db->where('status_id <>', user_status::BEGINNER);
        $this->db->where('status_id <>', user_status::BLOCKED_BY_PAYMENT);  // This status change when the client update his pay data
//        $this->db->where('status_id', user_status::BLOCKED_BY_INSTA);
//        $this->db->where('status_id', user_status::VERIFY_ACCOUNT);
//        $this->db->where('status_id', user_status::UNFOLLOW);
//        $this->db->where('status_id', user_status::BLOCKED_BY_PAYMENT);
//        $this->db->where('status_id', user_status::BLOCKED_BY_TIME);
        $clients = $this->db->get()->result_array();
        // Check payment for each user
        foreach ($clients as $client) {
            $clientname = $client['name'];
            $clientid = $client['user_id'];
            if ($client['order_key'] != NULL) {
                $checked = $this->check_client_payment($client);
                if ($checked) {
                    //var_dump($client);
                    print "\n<br>Client in day: $clientname (id: $clientid)<br>\n";
                } else {
                    print "\n<br>----Client with payment issue: $clientname (id: $clientid)<br>\n<br>\n<br>\n";
                }
            } else {
                print "\n<br>Client without ORDER KEY!!!: $clientname (id: $clientid)<br>\n";
            }
        }

        echo "\n\n<br>Job Done!" . date("Y-m-d h:i:sa") . "\n\n";
    }

    public function check_client_payment($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        // Check client payment in mundipagg
        $Payment = new \dumbu\cls\Payment();
        $result = $Payment->check_payment($client['order_key']);
        if (is_object($result) && $result->isSuccess()) {
            $data = $result->getData();
            //var_dump($data);
            $SaleDataCollection = $data->SaleDataCollection[0];
            $LastSaledData = NULL;
            // Get last client payment
            foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                if ($SaleData->CapturedAmountInCents == NULL) {
                    break;
                }
                //var_dump($SaleData);
                $LastSaledData = $SaleData;
            }
            $now = DateTime::createFromFormat('U', time());
            if ($LastSaledData != NULL) { // if have payment
                // Check difference between last payment and now
                $last_saled_date = new DateTime($LastSaledData->DueDate);
                $diff_info = $last_saled_date->diff($now);
                //var_dump($diff_info);
                // Diff in days
                $diff_days = $diff_info->days;
//                $diff_days = ($diff_info->m * 30) + $diff_info->days;
            } else { // if have not payment jet
                print "\n<br> LastSaledData = NULL";
                $pay_day = new DateTime();
                $pay_day->setTimestamp($client['pay_day']);
                $diff_info = $pay_day->diff($now);
                $diff_days = $diff_info->days;
//                $diff_days = ($diff_info->m * 30) + $diff_info->days;
                // TODO: check whend not pay and block user
                if ($now > $pay_day)
                    print "\n<br>This client has not payment since '$diff_days' days (PROMOTIONAL?): " . $client['name'] . "<br>\n";
            }
            print "\n<br> Diff days: $diff_days";
            // TODO: Put 34 in system_config
            //$diff_days = 35;
            if ($diff_days > 34) { // Limit to bolck
                //Block client by paiment
                $this->load->model('class/user_status');
                $this->load->model('class/user_model');
                $this->user_model->update_user($client['user_id'], array('status_id' => user_status::BLOCKED_BY_PAYMENT));
                print "This client was blocked by payment just now: " . json_encode($client);
                // TODO: Put 31 in system_config    
            } elseif ($diff_days > 31) { // Limit to advice
                // Send email to Client
                // TODO: Think about send email
                print "Diff in days bigger tham 31 days: $diff_days";
                $this->send_payment_email($client);
            } else {
                return TRUE;
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

    public function send_payment_email($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Gmail.php';
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/system_config.php';
        $GLOBALS['sistem_config'] = new \dumbu\cls\system_config();
        $this->Gmail = new \dumbu\cls\Gmail();
        $datas = $this->input->post();
        $result = $this->Gmail->send_client_payment_error($client['email'], $client['name'], $client['login'], $client['pass']);
        if ($result['success']) {
            $clientname = $client['name'];
            print "<br>Email send to client: $clientname<br>";
        } else {
            throw new Exception("Email not sent to: " . json_encode($client));
        }
    }

}
