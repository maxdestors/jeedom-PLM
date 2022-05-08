<?php

// namespace plmet;

class UrlBuilderv2 {

    private $baseUrl = 'https://api.weatherlink.com/v2';
    private $apiKey;
    private $apiSecret;

    public function __construct($apiKey, $apiSecret) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
    }

    public function getFullUrl($subUrl, $inputParameters) {
        $parameters = array_merge(
            $inputParameters,
            [
                "api-key" => $this->apiKey,
                "t" => time().''
            ]
        );
        $parameters['api-signature'] = $this->calculateSignature($parameters);
        return $this->baseUrl . $subUrl . '?' . http_build_query($parameters);
    }

    private function calculateSignature($parametersToHash) {
        ksort($parametersToHash);
        $stringToHash = "";
        foreach ($parametersToHash as $parameterName => $parameterValue) {
            $stringToHash = $stringToHash . $parameterName . $parameterValue;
        }
        $apiSignature = hash_hmac("sha256", $stringToHash, $this->apiSecret);
        return $apiSignature;
    }
}