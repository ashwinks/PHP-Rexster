<?php

    require_once 'Rexster_Object.php';
    require_once 'Rexster_Edge.php';

    class Rexster_Vertex extends Rexster_Object {

        protected $path = 'vertices';

        /**
         * Gets edges in any direction for this vertex 
         * 
         * @param string $direction - in, out, both
         * @param string $label
         * @throws Rexster_Exception
         * @return multitype:array of Rexster_Edge
         */
        public function getDirectionalEdges($direction, $label = null){

            $direction = rtrim(strtolower($direction), 'e');
            $directions = array('in', 'out', 'both');
            if (!in_array($direction, $directions)){
                throw new Rexster_Exception("Invalid direction {$direction}");
            }

            if (empty($this->id)){
                throw new Rexster_Exception("Invalid vertex id");
            }

            $url = "{$this->path}/{$this->id}/{$direction}E";
            if ($label && !empty($label)){
                $url .= "?_label={$label}";
            }

            $response = $this->graph->makeRequest('GET', $url);
            
            $arr = array();
            foreach ($response['data']['results'] as $item){
                array_push($arr, new Rexster_Edge($this->graph, $item));
            }

            return $arr;

        }
        
        public function createOutEdge($toVertex, $label = ''){
            
            if (empty($toVertex)){
                throw new Rexster_Exception("Invalid vertex id");
            }
            
            $data = array(
            	'_outV' => $this->id, 
            	'_inV' => $toVertex
            );
            
            if (!empty($label)){
                $data['_label'] = $label;
            }
            
            $e = new Rexster_Edge($this->graph, $data);
            
            return $e->create();
  
        }
        
        public function createInEdge($fromVertex, $label = ''){
            
            if (empty($fromVertex)){
            	throw new Rexster_Exception("Invalid vertex id");
            }
            
            $data = array(
            	'_outV' => $fromVertex,
            	'_inV' => $this->id
            );
            
            if (!empty($label)){
            	$data['_label'] = $label;
            }
            
            $e = new Rexster_Edge($this->graph, $data);
            
            return $e->create();
        	
        }

    }