<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends CI_Controller {

    public function do_payment($payment_data) {
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        // Check client payment in mundipagg
        $Payment = new \dumbu\cls\Payment();
        $response = $Payment->create_recurrency_payment($payment_data);
        // Save Order Key
        var_dump($response->OrderResult->OrderKey);
    }

    public function check_payment() {
        $this->load->model('class/user_model');
        $this->load->model('class/client_model');
        $this->load->model('class/user_role');
        $this->load->model('class/user_status');
        // Get all users
        $this->db->select('*');
        $this->db->from('clients');
        $this->db->join('users', 'clients.user_id = users.id');
//        $this->db->where('status !=', user_status::UNFOLLOW);
        //TESTE
        $this->db->where('status_id', user_status::UNFOLLOW);
        $clients = $this->db->get()->result_array();
        // Check payment for each user
        foreach ($clients as $client) {
            $checked = $this->check_client_payment($client);
            var_dump($client);
        }
    }

    public function check_client_payment($client) {
        require_once $_SERVER['DOCUMENT_ROOT'] . '/dumbu/worker/class/Payment.php';
        // Check client payment in mundipagg
        $Payment = new \dumbu\cls\Payment();
        $result = $Payment->check_payment($client['order_key']);
        if ($result->isSuccess()) {
            $data = $result->getData();
            var_dump($data);
            $SaleDataCollection = $data->SaleDataCollection[0];
            $LastSaledData = NULL;
            // Get last client payment
            foreach ($SaleDataCollection->CreditCardTransactionDataCollection as $SaleData) {
                if ($SaleData->CapturedAmountInCents == NULL) {
                    break;
                }
                var_dump($SaleData);
                $LastSaledData = $SaleData;
            }
            // Check difference between last payment and now
            $last_saled_date = new DateTime($LastSaledData->DueDate);
            $now = DateTime::createFromFormat('U', time());
            $diff_info = $last_saled_date->diff($now);
            var_dump($diff_info);
            if ($diff_info->days > 34) {
                // TODO: Block client by paiment
                print "Block this client: " . json_encode($client);
            } elseif ($diff_info->days > 6) {
                // Send email to Client
                $this->send_payment_email($client);
            }
        } else {
            throw new Exception("Payment error: " . json_encode($result->getData()));
        }
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
        }
        else {
            throw new Exception("Email not sent to: " . json_encode($client));
        }
    }

}
