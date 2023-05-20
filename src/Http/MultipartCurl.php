<?php


namespace BotMan\Drivers\Telegram\Http;


use BotMan\BotMan\Http\Curl;
use CURLFile;

class MultipartCurl extends Curl
{
    /**
     * {@inheritdoc}
     */
    public function post(
        $url,
        array $urlParameters = [],
        array $postParameters = [],
        array $headers = [],
        $asJSON = false
    ) {
        $request = $this->prepareRequest($url, $urlParameters, $headers);

        curl_setopt($request, CURLOPT_POST, true);

        if ($asJSON === true) {
            curl_setopt($request, CURLOPT_POSTFIELDS, json_encode($postParameters));
        } else {
            curl_setopt($request, CURLOPT_POSTFIELDS, $this->buildPostFields($postParameters));
        }

        return $this->executeRequest($request);
    }

    protected function buildPostFields($fields, $existingKeys = "", &$result = []){
        if (($fields instanceof CURLFile) or !(is_array($fields) or is_object($fields))) {
            $result[$existingKeys] = $fields;
        } else {
            foreach ($fields as $key => $item) {
                $this->buildPostFields($item, $existingKeys ?
                    $existingKeys . "[$key]" : $key, $result);
            }
        }
        return $result;
    }
}