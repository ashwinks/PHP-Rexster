<?php

    namespace Rexster;

    class Vertex extends Object {

        protected $_path = 'vertices';

        /**
         * Gets edges in any direction for this vertex 
         * 
         * @param string $direction - in, out, both
         * @param string $label
         * @throws Exception
         * @return multitype:array of \Rexster\Edge
         */
        public function getDirectionalEdges($direction, $label = null){

            $direction = rtrim(strtolower($direction), 'e');
            $directions = array('in', 'out', 'both');
            if (!in_array($direction, $directions)){
                throw new \InvalidArgumentException("Invalid direction {$direction}");
            }

            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }

            $url = $this->getPath() . '/' . $this->getId() . "/{$direction}E";
            if ($label && !empty($label)){
                $url .= "?_label={$label}";
            }

            $response = $this->getClient()->makeRequest('GET', $url);
            
            return Factory::getObject($this->getClient(), $response);
            
        }
        
        public function getOutEdges($label = null){
            
            return $this->getDirectionalEdges('out', $label);
            
        }
        
        public function getInEdges($label = null){
            
            return $this->getDirectionalEdges('in', $label);
            
        }
        
        public function getDirectionalVertices($direction){
            
            $direction = rtrim(strtolower($direction), 'e');
            $directions = array('in', 'out', 'both');
            if (!in_array($direction, $directions)){
                throw new \InvalidArgumentException("Invalid direction {$direction}");
            }
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $url = $this->getPath() . '/' . $this->getId() . "/{$direction}";
            
            $response = $this->graph->makeRequest('GET', $url);
            
            return Factory::getObject($this->getClient(), $response);
            
        }
        
        public function createOutEdge($toVertex, $label = '', array $properties = array()){
            
            if (empty($toVertex)){
                throw new \InvalidArgumentException('Invalid vertex id');
            }
            
            $data = array(
                '_outV' => $this->getId(), 
                '_inV' => $toVertex
            );
            
            if (!empty($label)){
                $data['_label'] = $label;
            }
            
            if (!empty($properties)){
                $data = array_merge($data, $properties);
            }
            
            $edge = new Edge($this->getClient(), $data);
            
            return $edge->create();
  
        }
        
        public function createInEdge($fromVertex, $label = '', array $properties = array()){
            
            if (empty($fromVertex)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $data = array(
                '_outV' => $fromVertex,
                '_inV' => $this->getId()
            );
            
            if (!empty($label)){
                $data['_label'] = $label;
            }
            
            if (!empty($properties)){
                $data = array_merge($data, $properties);
            }
            
            $e = new Edge($this->getClient(), $data);
            
            return $e->create();
            
        }
        
        public function getOutVerticesCount(){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $response = $this->getClient()->makeRequest('GET', $this->getPath() . '/' . $this->getId() . '/outCount');
            
            return Factory::getGeneric($this->getClient(), $response);
   
        }
        
        public function getInVerticesCount(){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $response = $this->getClient()->makeRequest('GET', $this->getPath() . '/' . $this->getId() . '/inCount');
            
            return Factory::getGeneric($this->getClient(), $response);
            
        }
        
        public function getBothDirectionVerticesCount(){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $response = $this->getClient()->makeRequest('GET', $this->getPath() . '/' . $this->getId() . '/bothCount');
            
            return Factory::getGeneric($this->getClient(), $response);
 
        }
        
        public function getOutVertexIds(){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $response = $this->getClient()->makeRequest('GET', $this->getPath() . '/' . $this->getId() . '/outIds');

            return Factory::getGeneric($this->getClient(), $response);
            
        }
        
        public function getInVertexIds(){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $response = $this->getClient()->makeRequest('GET', $this->getPath() . '/' . $this->getId() . '/inIds');
            
            return Factory::getGeneric($this->getClient(), $response);
  
        }

        public function getBothDirectionVertexIds(){
            
            if (empty($this->id)){
                throw new \InvalidArgumentException("Invalid vertex id");
            }
            
            $response = $this->getClient()->makeRequest('GET', $this->getPath() . '/' . $this->getId() . '/bothIds');
            
            return Factory::getGeneric($this->getClient(), $response);
            
        }
        
    }