<?php

    require_once 'Rexster.php';
    require_once 'Rexster_Vertex.php';
    require_once 'Rexster_Edge.php';
    require_once 'Rexster_Index.php';

    class Rexster_Graph extends Rexster {

        public $data = array();

        /**
         * Get current graph
         *
         * @throws Rexster_Exception
         */
        public function get(){

            $response = $this->makeRequest('GET');

            foreach ($response['data'] as $k => $v){
                $this->data[$k] = $v;
            }

            return $this;

        }

        /**
         * Get a specific vertex by id
         * 
         * @param int $vertex_id
         * @throws Rexster_Exception
         * @return Rexster_Vertex
         */
        public function getVertex($vertex_id){

            if (empty($vertex_id)){
                throw new Rexster_Exception("Invalid vertex id");
            }

            $response = $this->makeRequest('GET', "/vertices/{$vertex_id}");

            return new Rexster_Vertex($this, $response['data']['results']);

        }
        
        /**
         * Get a vertex by a key value pair
         * 
         * @param string $attribute_key
         * @param string $attribute_value
         */
        public function getVertexByAttribute($attribute_key, $attribute_value){
            
            if (empty($attribute_key)){
                throw new Rexster_Exception("Invalid attribute key");
            }
            
            if (empty($attribute_value)){
                throw new Rexster_Exception("Invalid attribute value");
            }
            
            $attribute_key = urlencode($attribute_key);
            $attribute_value = urlencode($attribute_value);
            
            $response = $this->makeRequest('GET', "/vertices?key={$attribute_key}&value={$attribute_value}");

            if (count($response['data']['results']) > 1){
                
	            $arr = array();
	            foreach ($response['data']['results'] as $item){
	            	array_push($arr, new Rexster_Vertex($this, $item));
	            }
	            
            	return $arr;
            	
            }else{
                
                return new Rexster_Vertex($this, $response['data']['results'][0]);
                
            }

        }

        /**
         * Get vertices from this vertex in any direction
         * 
         * @param int $vertex_id
         * @param string $direction - in, out, both
         * @param string $label
         * @throws Rexster_Exception
         * @return multitype:array of Rexster_Vertex
         */
        public function getDirectionalVertices($vertex_id, $direction, $label = null){

            $direction = rtrim(strtolower($direction), 'e');

            $directions = array('in', 'out', 'both');
            if (!in_array($direction, $directions)){
                throw new Rexster_Exception("Invalid direction {$direction}");
            }

            if (empty($vertex_id)){
                throw new Rexster_Exception("Invalid vertex id");
            }

            $url = "/vertices/{$vertex_id}/{$direction}";
            if ($label && !empty($label)){
                $url .= "?_label={$label}";
            }

            $response = $this->makeRequest('GET', $url);

            $arr = array();
            foreach ($response['data']['results'] as $item){
                array_push($arr, new Rexster_Vertex($this, $item));
            }

            return $arr;

        }

        /**
         * Update a vertex by id
         * 
         * @param int $vertex_id
         * @param mixed $data
         * @throws Rexster_Exception
         * @return Rexster_Vertex
         */
        public function updateVertex($vertex_id, $data){


            if (empty($vertex_id)){
                throw new Rexster_Exception("Invalid vertex id");
            }

            if (empty($data)){
                throw new Rexster_Exception("Invalid data to update");
            }

            $response = $this->makeRequest('PUT', "/vertices/{$vertex_id}", $data);

            return new Rexster_Vertex($this, $response['data']);

        }

        /**
         * Get all edges in the graph
         * 
         * @return multitype:array of Rexster_Edge
         */
        public function getEdges(){

            $response = $this->makeRequest('GET', '/edges');

            $arr = array();
            foreach ($response['data']['results'] as $item){
                array_push($arr, new Rexster_Edge($this, $item));
            }

            return $arr;

        }

        /**
         * Get a specific edge by id
         * 
         * @param int $edge_id
         * @throws Rexster_Exception
         * @return Rexster_Edge
         */
        public function getEdge($edge_id){

            if (empty($edge_id)){
                throw new Rexster_Exception("Invalid edge id");
            }

            $response = $this->makeRequest('GET', "/edges/{$edge_id}");
            
            $e = new Rexster_Edge($this, $response['data']);

            return $e;

        }

        /**
         * Get all vertices by index
         * 
         * @deprecated as of Blueprints 2.0
         * @param string $indexname
         * @param string $key
         * @param string $value
         * @throws Rexster_Exception
         * @return multitype:array of Rexster_Vertex
         */
        public function getIndexedData($indexname, $key, $value){

            if (empty($indexname)){
                throw new Rexster_Exception("Invalid index name");
            }

            if (empty($key)){
                throw new Rexster_Exception("Invalid index key for index ({$indexname})");
            }

            if (empty($value)){
                throw new Rexster_Exception("Invalid index ({$indexname} -- {$key}) value");
            }

            $key = urlencode($key);
            $value = urlencode($value);

            $response = $this->makeRequest('GET', "/indices/{$indexname}?key={$key}&value={$value}");

            $arr = array();
            foreach ($response['data']['results'] as $item){
                array_push($arr, new Rexster_Vertex($this, $item));
            }

            return $arr;

        }

        /**
         * Get all indexes in this graph
         * 
         * @deprecated as of Blueprints 2.0
         * 
         * @return multitype:array of Rexster_Index
         */
        public function getIndexes(){

            $response = $this->makeRequest('GET', '/indices');

            $arr = array();
            foreach ($response['data']['results'] as $item){
                array_push($arr, new Rexster_Index($this, $item));
            }

            return $arr;

        }

        public function __get($key){
            return $this->data[$key];
        }

        public function __set($key, $value){
            $this->data[$key] = $value;
        }

        public function __isset($key){
            return isset($this->data[$key]);
        }


    }