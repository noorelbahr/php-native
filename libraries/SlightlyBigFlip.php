<?php

class SlightlyBigFlip
{
    /**
     * Request disbursement
     * - - -
     * @param $withdrawal
     * @return mixed
     */
    public function requestDisbursement($withdrawal)
    {
        return $this->request(
            'disburse',
            'POST',
            $withdrawal->id,
            [
                'bank_code'         => $withdrawal->bank_code,
                'account_number'    => $withdrawal->account_number,
                'amount'            => $withdrawal->amount,
                'remark'            => $withdrawal->remark
            ]
        );
    }

    /**
     * Check disburse status
     * - - -
     * @param $withdrawal
     * @return mixed
     */
    public function checkStatus($withdrawal)
    {
        return $this->request(
            'disburse/' . $withdrawal->reference_id,
            'GET',
            $withdrawal->id,
            [],
            'status'
        );
    }

    /**
     * PRIVATE
     * Request
     * - - -
     * @param $path
     * @param $method
     * @param null $ref
     * @param array $params
     * @param null $type
     * @return mixed
     */
    private function request($path, $method, $ref = null, $params = [], $type = null)
    {
        $url    = SB_FLIP_URL . '/' . trim($path, '/');
        $secret = SB_FLIP_SECRET_KEY;

        // Set header options
        $headerOptions = [
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: basic ' . base64_encode($secret . ':')
        ];

        // Save log
        $logModel = new ThirdPartyLog();
        $logData = [
            'reference_id'  => $ref,
            'server'        => 'sbflip',
            'type'          => $type ? : $path,
            'time'          => time(),
            'payload'       => json_encode([
                'params' => $params,
                'header' => $headerOptions
            ])
        ];
        $logModel->create($logData);
        unset($logData['payload']);
        $log = $logModel->findWhere($logData);

        // Request
        $http = new HttpClient();
        $result = null;
        if ($method === 'POST')
            $result = $http->post($url, $params, $headerOptions);
        elseif ($method === 'GET')
            $result = $http->get($url, $params, $headerOptions);

        // Update log response
        if ($log) {
            $logModel->update($log->id, [
                'response' => $result
            ]);
        }

        return json_decode($result);
    }
}
