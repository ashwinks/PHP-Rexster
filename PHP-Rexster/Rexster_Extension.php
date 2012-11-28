<?php

    require_once 'Rexster_Object.php';
    require_once 'Rexster_Edge.php';
    require_once 'Rexster_Vertex.php';

    class Rexster_Extension {

        public $graph;

        public function __construct(Rexster_Graph $graph, $extension_path){

            if (empty($graph) || !($graph instanceof Rexster_Graph)){
                throw new Rexster_Exception("Graph should be instance of Rexter_Graph");
            }

            if (empty($extension_path)){
                throw new Rexster_Exception("Invalid extension path");
            }

            $this->graph = $graph;
            $this->path = $extension_path;

        }

        public function makeGetRequest($data){
            return $this->_makeRequest('GET', $data);
        }

        public function makePutRequest($data){
            return $this->_makeRequest('PUT', $data);
        }

        public function makePostRequest($data){
            return $this->_makeRequest('POST', $data);
        }

        public function makeDeleteRequest($data){
            return $this->_makeRequest('DELETE', $data);
        }

        private function _makeRequest($method, $data){

            if (empty($method)){
                throw new Rexster_Exception("Invalid request method");
            }

            $response = $this->graph->makeRequest($method, $this->path, $data);

            $tmp = array();

            $itr = isset($response['data']['results'])? $response['data']['results'] : $response['data'];

            foreach ($itr as $item){

                if (is_array($item) && isset($item['_type'])){
                    switch ($item['_type']){

                        case 'edge':

                            array_push($tmp, new Rexster_Edge($this->graph, $item));

                            break;

                        case 'vertex':

                            array_push($tmp, new Rexster_Vertex($this->graph, $item));

                            break;

                    }
                }

            }

            if (empty($tmp)){
                return new Rexster_Object($this->graph, $itr);
            }

            return $tmp;

        }

    }