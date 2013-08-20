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
			
			if (isset($response['results']) && count($response['results']) > 0){
			    return Factory::getGeneric($this->getClient(), $response);
			}else{
			    return false;
			}

	    }
	    
	    public function getNthDepthVertices($starting_vertex_id, $depth, $include_path = false, $label_filter = null){
	    	 
	    	$script = 'g.v(' . $starting_vertex_id . ').as(\'x\').outE(';
	    	if ($label_filter){
	    		$script .= '\'' . $label_filter . '\'';
	    	}
	    	$script .= ').inV.loop(\'x\'){it.loops<' . ($depth + 1) . '}';
	    	 
	    	if ($include_path){
	    		$script .= ".path";
	    	}
	    
	    	return $this->runScript($script);
	    	 
	    }

	}