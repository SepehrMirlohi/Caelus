<?php


class Requests
{


    public array|null $header = [];
    public string $host;
    public array $fixed_params;
    public array $parameters = [];

    public function __construct($host, $fixed_params = null)
    {
        $this->host = $host;
        $this->fixed_params = $fixed_params;

    }

    // Send requests with different types
    public function post($parameters): array|string|null
    {
        $this->parameters = array_merge($this->parameters, $parameters);
//        if (empty($this->header)){
//            return "Authorization Error.";
//        }
        if (!empty($this->fixed_params)){
            $this->set_fixed_params();
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>   $this->parameters,
            CURLOPT_HTTPHEADER => $this->header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);

    }

    public function get(): array|string
    {
        if (empty($this->host)){
            return "Host not found!";
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->host,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }

    // Set headers with different types

    public function setBearerToken($token): void
    {
        $this->header = array(
            'Authorization: Bearer '. $token
        );
    }

    public function setBasicAuth($username, $password): void
    {
        $this->header = array(
            'Authorization: Basic '. base64_encode($username . ":" . $password)
        );
    }

    public function setNoAuth(): void
    {
        $this->header = ["NoAuth"];
    }

    // private methods

    private function set_fixed_params(): void
    {
        $this->parameters = array_merge($this->parameters, $this->fixed_params);
    }
}