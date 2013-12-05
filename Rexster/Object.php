<?php

    namespace Rexster;

    class Object extends Collection {

        protected $_client;
        protected $_path = null;

        public $id;
        public $version;
        public $total_size;
        public $query_time;

        public function __construct(Client $client, array $data = array()){

            if (!$client || !$client instanceof Client){
                throw new \InvalidArgumentException('Invalid client object');
            }

            $this->_client = $client;
            
            if (isset($data['version'])){
                $this->version = $data['version'];
            }
            
            if (isset($data['totalSize'])){
                $this->total_size = $data['totalSize'];
            }
            
            if (isset($data['queryTime'])){
                $this->query_time = $data['queryTime'];
            }

            if (isset($data['results'])){
                $data = $data['results'];
            }

            if (!empty($data)){

                if (isset($data['_id']) && !empty($data['_id'])){
                    $this->id = $data['_id'];
                }

                $this->setData($data);

            }
            
        }
        
        public function getPath(){
            
            return $this->_path;
            
        }
        
        /**
         * 
         * @return \Rexster\Client
         */
        public function getClient(){
            
            return $this->_client;
            
        }

        /**
         * Creates an item in the graph
         * 
         * @return requested object type
         */
        public function create(){

            $response = $this->getClient()->makeRequest('POST', $this->getPath(), $this->toArray());
            
            return Factory::getObject($this->getClient(), $response);

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
                throw new \InvalidArgumentException('Invalid object id to update');
            }

            if ($this->id != $this->_id){
                throw new \InvalidArgumentException("Node ID's do not match? this->_id = {$this->_id} -- this->id = {$this->id}");
            }

            $data = $this->toArray();
            if (empty($data)){
                throw new \InvalidArgumentException("Invalid properies to update - Properties array should be an associative array of property => value");
            }
            
            $reserved = array('id', '_id');
            foreach ($reserved as $key){
                if (isset($data[$key])){
                    unset($data[$key]);
                }
            }

            $url = '/' . $this->getPath() . '/' . $this->getId();

            $response = $this->getClient()->makeRequest($method, $url, $data);
            
            return Factory::getObject($this->getClient(), $response);

        }
        
        public function delete(array $properties = array()){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException('Invalid object id to update');
            }
            
            if ($this->id != $this->_id){
                throw new \InvalidArgumentException("Node ID's do not match? this->_id = {$this->_id} -- this->id = {$this->id}");
            }

            $url = '/' . $this->getPath() . '/' . $this->getId();
            
            if (!empty($properties)){
                $tmp = array();
                foreach ($properties as $property){
                    $tmp[$property] = $property;
                }
                unset($properties);
                $properties = $tmp;
            }
            
            $response = $this->getClient()->makeRequest("DELETE", $url, $properties);
            
            return Factory::getGeneric($this->getClient(), $response);

        }
        
        public function getId(){
            
            if (isset($this->id)){
                return $this->id;
            }else if ($this->getProperty('_id')){
                return $this->getProperty('_id');
            }else{
                return null;
            }

        }

        public function getProperty($property_name){
            
            if (isset($this->_data[$property_name])){
                return $this->_data[$property_name];
            }
            
            return null;
        }
        
        public function toArray(){
            
            $data = parent::toArray();
            
            if (isset($this->id)){
                $data['id'] = $this->id;
            }
            
            return $data;
            
        }

    }