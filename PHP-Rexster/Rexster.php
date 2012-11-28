<?php

    require_once 'Rexster_Object.php';

    abstract class Rexster {

        public $base_url = null;
        public $graph_name = null;

        public function __construct($base_url, $graph_name){

            if (!function_exists('curl_init')) {
    			throw new Rexster_Exception('cURL extension not enabled or installed');
    		}

    		if (empty($base_url)){
    		    throw new Rexster_Exception('Invalid base url');
    		}

    		if (empty($graph_name)){
    		    throw new Rexster_Exception("Invalid graph name");
    		}

    		$this->graph_name = $graph_name;
    		$this->base_url = rtrim($base_url, '/') . '/graphs';

        }

        public function getCustom($url){

            if (empty($url)){
                throw new Exception("Invalid URL");
            }

            $response = $this->makeRequest('GET', "/{$url}");

            return new Rexster_Object($this, $response['data']);

        }

        public function postCustom($url, array $data = array()){

            if (empty($url)){
                throw new Exception("Invalid URL");
            }

            $response = $this->makeRequest('POST', "/{$url}", $data);

            return new Rexster_Object($this, $response['data']);

        }

        public function putCustom($url, array $data = array()){

            if (empty($url)){
                throw new Rexster_Exception("Invalid URL");
            }

            $response = $this->makeRequest('PUT', "/{$url}", $data);

            return new Rexster_Object($this, $response['data']);

        }

        public function makeRequest($method, $path = null, $data = null, $accept = 'application/json'){

            if (empty($method)){
                throw new Rexster_Exception('Invalid request method');
            }

            $method = strtoupper($method);

            $url = "{$this->base_url}/{$this->graph_name}";
            if ($path){
                $path = trim($path, '/');
                $url .= "/{$path}";
            }

            $options = array(
                CURLOPT_URL => $url,
                CURLOPT_HEADER => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => $method
            );

            $headers = array("Accept: {$accept}");

            if (!empty($data)){

                // type casting
                if (is_array($data) && ($method == 'POST' || $method == 'PUT')){
                    foreach ($data as $k => &$v){
                        if (substr($k, 0, 1) == '_') continue;
                        if (is_int($v)){
                            $v = "(integer,{$v})";
                        }else if (is_float($v)){
                            $v = "(float,{$v})";
                        }
                    }
                }

                if (is_array($data)){
                    $data = http_build_query($data);
                }else{

                    $json_payload = (json_decode($data) != NULL) ? true : false;
                    if ($json_payload){
                        $headers[] = 'Content-Type: application/json';
                    }

                    $headers[] = 'Content-Length: ' . strlen($data);
                }

                switch ($method){

                    case 'POST':
                    case 'PUT':

                        $options[CURLOPT_POSTFIELDS] = $data;

                        break;

                    case 'GET':

                        $options[CURLOPT_URL] .= '?' . $data;

                        break;

                }

            }

            $options[CURLOPT_HTTPHEADER] = $headers;

            $ch = curl_init();
            curl_setopt_array($ch, $options);
            
            $response = array();
            $response['raw_data'] = curl_exec($ch);
            $response['code'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $response['data'] = json_decode($response['raw_data'], true);

            if (($response['code'] >= 200) && ($response['code'] < 300)){

                return $response;

            }else{

                $error_msg = "Curl {$method} request to {$url} failed. Response Code: {$response['code']}";
                if (isset($response['data']['message'])){
                    $error_msg .= " - Message: {$response['data']['message']}";
                }
                $error_msg .= '. Curl Options: ' . print_r($options, true);
                $error_msg .= '. Payload: ' . print_r($data, true);


                throw new Rexster_Exception($error_msg, $response['code']);
            }

        }

    }