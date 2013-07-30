<?php

	namespace Rexster;

	abstract class Collection implements \ArrayAccess, \Countable, \Iterator{
	    
	    private $_position = 0;
	    protected $_data;
	    
	    public function __construct(array $data = array()){
	        
	        $this->_data = $data;
	        
	    }
	    
	    public function __get($key){
	    
	        return $this->offsetExists($key)? $this->offsetGet($key) : null;
	      
	    }
	    
	    public function __set($key, $value){
	        
	        $this->offsetSet($key, $value);
	        
	    }
	    
	    public function __isset($key){
	        
	        return $this->offsetExists($key);
	        
	    }
	    
	    public function __unset($key){
	        
	        return $this->offsetUnset($key);
	        
	    }
	    
	    public function setData(array $data){
	        
	        $this->_data = $data;
	        
	        return $this;
	        
	    }
	    
	    public function getData(){
	        
	        return $this->_data;
	        
	    }
	    
	    public function toArray(){
	        
	      	return $this->getData();
	    	
	    }
	
	    public function offsetSet($offset, $value){
	        
	        if (is_null($offset)){
	            $this->_data[] = $value;
	        }else{
	            $this->_data[$offset] = $value;
	        }
	        
	    }
	    
	    public function offsetExists($offset){
	        
	        return isset($this->_data[$offset]);
	        
	    }
	    
	    public function offsetUnset($offset){
	        
	        unset($this->_data[$offset]);
	        
	    }
	    
	    public function offsetGet($offset){
	        
	        return isset($this->_data[$offset])? $this->_data[$offset] : null;
	        
	    }
	    
	    public function count(){
	        
	        return count($this->_data);
	        
	    }
	    
	    public function rewind(){
	        
	        $this->_position = 0;
	        
	    }
	    
	    public function current(){
	        
	        return $this->_data[$this->_position];
	        
	    }
	    
	    public function key(){
	        
	        return $this->_position;
	        
	    }
	    
	    public function next(){
	        
	        ++$this->_position;
	        
	    }
	    
	    public function valid(){
	        
	        return isset($this->_data[$this->_position]);
	        
	    }

	}