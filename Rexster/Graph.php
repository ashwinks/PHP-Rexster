<?php

	namespace Rexster;

	class Graph extends Client {


	    /**
	     * Get info for the current graph
	     * 
	     * @return array
	     */
		public function getInfo(){
	
			$response = $this->makeRequest('GET');
			
			return Factory::getGeneric($this, $response);

		}
		
		/**
		 * Get all vertices
		 * 
		 * @return Ambigous <NULL, \Rexster\Vertex, \Rexster\Edge, [array]\Rexster\Vertex, [array] \Rexster\Edge>
		 */
		public function getVertices(){
	
			$response = $this->makeRequest('GET', '/vertices');
	
			return Factory::getObject($this, $response);
		}
	
		public function getVertex($vertex_id){
	
			if (empty($vertex_id)){
				throw new \InvalidArgumentException('Invalid vertex id');
			}
	
			$response = $this->makeRequest('GET', "/vertices/{$vertex_id}");

			return Factory::getObject($this, $response);
	
		}
	
		public function getVertexByAttribute($attribute_key, $attribute_value){
	
			if (empty($attribute_key)){
				throw new \InvalidArgumentException('Invalid attribute key');
			}
	
			if (empty($attribute_value)){
				throw new \InvalidArgumentException('Invalid attribute value');
			}
	
			$attribute_key = urlencode($attribute_key);
			$attribute_value = urlencode($attribute_value);
	
			$response = $this->makeRequest('GET', "/vertices?key={$attribute_key}&value={$attribute_value}");
	
			return Factory::getObject($this, $response);
	
		}
	
		public function getDirectionalVertices($vertex_id, $direction, $label = null){
	
			$direction = rtrim(strtolower($direction), 'e');
	
			$directions = array('in', 'out', 'both');
			if (!in_array($direction, $directions)){
				throw new \InvalidArgumentException("Invalid direction {$direction}");
			}
	
			if (empty($vertex_id)){
				throw new \InvalidArgumentException('Invalid vertex id');
			}
	
			$url = "/vertices/{$vertex_id}/{$direction}";
			if ($label && !empty($label)){
				$url .= "?_label={$label}";
			}
	
			$response = $this->makeRequest('GET', $url);
			
			return Factory::getObject($this, $response);
	
		}
	
		public function updateVertex($vertex_id, $data){

			if (empty($vertex_id)){
				throw new \InvalidArgumentException('Invalid vertex id');
			}
	
			if (empty($data)){
				throw new \InvalidArgumentException('Invalid data to update');
			}
	
			$response = $this->makeRequest('POST', "/vertices/{$vertex_id}", $data);
	
			return Factory::getObject($this, $response);
	
		}
		
		public function getEdgesByAttribute($attribute_key, $attribute_value){
		
			if (empty($attribute_key)){
				throw new \InvalidArgumentException('Invalid attribute key');
			}
		
			if (empty($attribute_value)){
				throw new \InvalidArgumentException('Invalid attribute value');
			}
		
			$attribute_key = urlencode($attribute_key);
			$attribute_value = urlencode($attribute_value);
		
			$response = $this->makeRequest('GET', "/edges?key={$attribute_key}&value={$attribute_value}");
		
			return Factory::getObject($this, $response);
		
		}
	
		public function getEdges(){

			$response = $this->makeRequest('GET', '/edges');
			
			return Factory::getObject($this, $response);
	
		}
	
		public function getEdge($edge_id){
	
			if (empty($edge_id)){
				throw new \InvalidArgumentException("Invalid edge id");
			}
	
			$response = $this->makeRequest('GET', "/edges/{$edge_id}");
	
			return Factory::getObject($this, $response);

		}
	
		public function getKeyIndices(){
	
			$response = $this->makeRequest('GET', '/keyindices');
	
			return $response;
	
		}
	
		public function runGremlinScript($script, $path = ''){
	
			$url = "{$path}/tp/gremlin?script={$script}";
	
			$response = $this->makeRequest('GET', $url);
	
			return $response;
	
		}
	
	}