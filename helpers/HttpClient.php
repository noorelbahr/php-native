<?php

class HttpClient
{
    public function get($url, $params = [], $options = [])
    {
        return $this->request($url, 'GET', $params, $options);
    }

    public function post($url, $params = [], $options = [])
    {
        return $this->request($url, 'POST', $params, $options);
    }

    private function request($url, $method = 'GET', $params = [], $options = [])
    {
        // cUrl init
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Set params
        $payload = http_build_query($params);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        }

        // Set header
        if (count($options))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $options);

        // Execute
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
