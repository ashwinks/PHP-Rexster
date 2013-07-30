<?php

	namespace Rexster\Extension;
	
	use Rexster\Factory;

	class Gremlin extends Base{

	    public function runScriptOnEdge($edge_id, $script){
	        
	        if (empty($edge_id)){
	            throw new \InvalidArgumentException('Invalid edge id');
	        }
	        
	        return $this->runScript($script, "/edges/{$edge_id}");
	        
	    }
	    
	    public function runScriptOnVertex($vertex_id, $script){
	        
	        if (empty($vertex_id)){
	        	throw new \InvalidArgumentException('Invalid vertex id');
	        }
	         
	        return $this->runScript($script, "/vertices/{$vertex_id}");
	        
	    }
	    
	    public function runScriptOnGraph($script){
	        
	        return $this->runScript($script);
	        
	    }
	    
	    public function runScript($script, $path = ''){
	        
	        if (empty($script)){
	            throw new \InvalidArgumentException('Invalid gremlin script');
	        }
	        
			$response = $this->getClient()->makeRequest('GET', $path . '/tp/gremlin', array('script' => $script));
			
			return Factory::getGeneric($this->getClient(), $response);

	    }
	    
	}