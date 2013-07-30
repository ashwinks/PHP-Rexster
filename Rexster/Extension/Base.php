<?php

	namespace Rexster\Extension;
	
	abstract class Base {
	    
		/**
	     * @var \Rexster\Client
	     */
	    protected $_client;
	    
	    public function __construct(\Rexster\Client $client){
	        
	        $this->_client = $client;
	        
	    }
	    
	    /**
	     * 
	     * @return \Rexster\Client
	     */
	    public function getClient(){
	        
	        return $this->_client;
	        
	    }
	    
	}