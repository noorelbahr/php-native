<?php

class SlightlyBigFlip
{
    public function requestDisbursement($amount, $bankCode, $accountNumber, $remark = null)
    {
        $result = $this->request('disburse', 'POST', [
            'bank_code' => $bankCode,
            'account_number' => $accountNumber,
            'amount' => $amount,
            'remark' => $remark
        ]);
    }

    private function request($path, $method, $params = [])
    {
        $url    = SB_FLIP_URL . '/' . trim($path, '/');
        $secret = SB_FLIP_SECRET_KEY;

        // Set header options
        $headerOptions = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: basic ' . base64_encode($secret . ':')
        ];

        // Request
        $http = new HttpClient();
        if ($method === 'POST')
            return $http->post($url, $params, $headerOptions);
        elseif ($method === 'GET')
            return $http->get($url, $params, $headerOptions);
    }
}
