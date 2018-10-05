<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class SubscriptionController extends Controller
{

    public function updateNextRebillProduct($orderId, $LLProductID)
    {
        $resp = [];

        // $demoCampaignID = 1030;
        // $orderId = 238214;
        // $LLProductID = 1969;

        $LLCreds = $this->getLLCreds();
        $data = [
            'username' => $LLCreds['LLCRM_APIUsername'],
            'password' => $LLCreds['LLCRM_APIPassword'],
        ];

        $orderUpdateUrl = 'https://' . $LLCreds['LLCRM_Instance'] . '.limelightcrm.com/admin/membership.php?username=' . $LLCreds['LLCRM_APIUsername'] . '&password=' . $LLCreds['LLCRM_APIPassword'] . '&method=order_update&order_ids=' . $orderId . '&sync_all=&actions=next_rebill_product&values=' . $LLProductID . '&return_format=json';

        //====== Fetching The Product Details =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $orderUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        if ($response_output['response_code'] == 100) {
            $resp['type'] = 'success';
            $resp['msg'] = 'Successfully updated Next Rebill Product';
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Response:' . $response_output['response_code'];
        }

        echo json_encode($resp);
        die();

    }

    public function updateRecurring($orderId, $status)
    {
        $resp = [];

        //  $orderId = 238214;
        //  $status = 'stop';
        //  $status = 'start';
        //  $status = 'reset';

        $LLCreds = $this->getLLCreds();

        $data = [
            'username' => $LLCreds['LLCRM_APIUsername'],
            'password' => $LLCreds['LLCRM_APIPassword'],
        ];

        $orderUpdateUrl = 'https://' . $LLCreds['LLCRM_Instance'] . '.limelightcrm.com/admin/membership.php?username=' . $LLCreds['LLCRM_APIUsername'] . '&password=' . $LLCreds['LLCRM_APIPassword'] . '&method=order_update_recurring&order_id=' . $orderId . '&status=' . $status . '&return_format=json';

        //====== Fetching The Product Details =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $orderUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        if ($response_output['response_code'] == 100) {
            $resp['type'] = 'success';
            $resp['msg'] = 'Successfully Updated Next Recurring';
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Response:' . $response_output['response_code'];
        }

        echo json_encode($resp);
        die();

    }

    public function updateShippingAddress($orderId, $inputArr)
    {
        $resp = [];

        // $demoCampaignID = 1030;
        // $orderId = 238214;
        // $LLProductID = 1969;

        $shipping_address1 = $inputArr['shipping_address1'];
        $shipping_city = $inputArr['shipping_city'];
        $shipping_zip = $inputArr['shipping_zip'];
        $shipping_state = $inputArr['shipping_state'];
        $shipping_country = $inputArr['shipping_country'];

        $LLCreds = $this->getLLCreds();
        $data = [
            'username' => $LLCreds['LLCRM_APIUsername'],
            'password' => $LLCreds['LLCRM_APIPassword'],
        ];

        $order_param = $orderId . ',' . $orderId . ',' . $orderId . ',' . $orderId . ',' . $orderId;
        $actions_param = 'shipping_address1,shipping_city,shipping_zip,shipping_state,shipping_country';
        $value_param = urlencode($shipping_address1) . ',' . urlencode($shipping_city) . ',' . urlencode($shipping_zip) . ',' . $shipping_state . ',' . urlencode($shipping_country);

        $orderUpdateUrl = 'https://' . $LLCreds['LLCRM_Instance'] . '.limelightcrm.com/admin/membership.php?username=' . $LLCreds['LLCRM_APIUsername'] . '&password=' . $LLCreds['LLCRM_APIPassword'] . '&method=order_update&order_ids=' . $order_param . '&sync_all=&actions='.$actions_param.'&values=' . $value_param . '&return_format=json';

        //====== Updating The Shipping Details =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $orderUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        if ($response_output['response_code'] == 100) {
            $resp['type'] = 'success';
            $resp['msg'] = 'Successfully updated Shipping Address to ' . $address;
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Response:' . $response_output['response_code'];
        }

        echo json_encode($resp);
        die();

    }

    // public function updateBillingInfo($orderId, $inputArr)
    public function updateBillingInfo($orderId,$inputArr)
    {
        $resp = [];

        // $demoCampaignID = 1030;
        // $orderId = 238214;

        // $inputArr['billing_address1'] = 'Gautam';
        // $inputArr['billing_city'] = '';
        // $inputArr['billing_zip'] = '';
        // $inputArr['billing_state'] = '';
        // $inputArr['billing_country'] = '';
        // $inputArr['cc_number'] = '';



        $billing_address1 = $inputArr['billing_address1'];
        $billing_city = $inputArr['billing_city'];
        $billing_zip = $inputArr['billing_zip'];
        $billing_state = $inputArr['billing_state'];
        $billing_country = $inputArr['billing_country'];
        $cc_number = $inputArr['cc_number'];

        $LLCreds = $this->getLLCreds();
        $data = [
            'username' => $LLCreds['LLCRM_APIUsername'],
            'password' => $LLCreds['LLCRM_APIPassword'],
        ];

        $order_param = $orderId . ',' . $orderId . ',' . $orderId . ',' . $orderId . ',' . $orderId. ',' . $orderId;
        $actions_param = 'billing_address1,billing_city,billing_zip,billing_state,billing_country,cc_number';
        $value_param = urlencode($billing_address1) . ',' . urlencode($billing_city) . ',' . urlencode($billing_zip) . ',' . $billing_state . ',' . urlencode($billing_country) . ',' . urlencode($cc_number);

        $orderUpdateUrl = 'https://' . $LLCreds['LLCRM_Instance'] . '.limelightcrm.com/admin/membership.php?username=' . $LLCreds['LLCRM_APIUsername'] . '&password=' . $LLCreds['LLCRM_APIPassword'] . '&method=order_update&order_ids=' . $order_param . '&sync_all=&actions='.$actions_param.'&values=' . $value_param . '&return_format=json';

        //====== Updating The Shipping Details =======//
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $orderUpdateUrl);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $curl_response = curl_exec($ch);
        parse_str($curl_response, $response_output);
        curl_close($ch);

        if ($response_output['response_code'] == 100) {
            $resp['type'] = 'success';
            $resp['msg'] = 'Successfully updated Billing Information.';
        } else {
            $resp['type'] = 'error';
            $resp['msg'] = 'Error! Response:' . $response_output['response_code'];
        }

        echo json_encode($resp);
        die();

    }


}
