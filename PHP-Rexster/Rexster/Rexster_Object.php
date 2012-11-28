<?php

	require 'Rexster_Exception.php';
	
    class Rexster_Object {

        public $properties;
        public $graph;
        public $id;

        private $extension_path = null;

        /**
         * @param Rexster_Graph $graph_obj
         * @param mixed $data
         * @throws Rexster_Exception
         */
        public function __construct(Rexster_Graph $graph_obj, $data){

            if (!$graph_obj || empty($graph_obj) || !$graph_obj instanceof Rexster_Graph){
                throw new Rexster_Exception("Invalid graph object");
            }

            $this->graph = $graph_obj;

            if (isset($data['results'])){
                $data = $data['results'];
            }

            if (is_array($data) || is_object($data)){

                if (isset($data['_id']) && !empty($data['_id'])){
                    $this->id = $data['_id'];
                }

                $this->properties = $data;

            }

        }

        /**
         * Creates an item in the graph
         * 
         * @return requested object type
         */
        public function create(){

            $response = $this->graph->makeRequest('POST', $this->path, $this->properties);

            $child = get_class($this);

            return new $child($this->graph, $response['data']['results']);

        }

        /**
         * Update an item in the graph
         * 
         * @param string $method - PUT OR POST
         * @throws Rexster_Exception
         * @return requested object type
         */
        public function update($method = 'POST'){

            if (empty($this->id)){
                throw new Rexster_Exception("Invalid object id to update");
            }

            if ($this->id != $this->_id){
                throw new Rexster_Exception("Node ID's do not match? this->_id = {$this->_id} -- this->id = {$this->id}");
            }

            if (empty($this->properties)){
                throw new Rexster_Exception("Invalid properies to update - Properties array should be an associative array of property => value");
            }

            $url = "/{$this->path}/{$this->id}";
            if (!empty($this->extension_path)){
                $url .= "/{$this->extension_path}";
            }

            $response = $this->graph->makeRequest($method, $url, $this->properties);

            $child = get_class($this);

            return new $child($this->graph, $response['data']['results']);

        }
        
        public function getId(){
            
            if (isset($this->id)){
                return $this->id;
            }else{
                return $this->properties['_id'];
            }

        }

        public function setExtensionPath($path){

            $this->extension_path = trim($path, '/');

        }

        public function __get($key){

        	if( isset($this->properties[$key]) ) {
            	return $this->properties[$key];
        	}

        }

        public function __set($key, $value){

            $this->properties[$key] = $value;

        }

        public function __isset($key){

            return isset($this->properties[$key]);

        }

        public function __unset($key){

            unset($this->properties[$key]);

        }

        public function toArray(){

            $data = $this->properties;
            $data['id'] = $this->id;
            
            return $data;

        }

    }