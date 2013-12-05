<?php

    namespace Rexster;

    class Edge extends Object {

        protected $_path = 'edges';
 
        public function getInVertex(){
            
            return $this->getProperty('_inV');
            
        }
        
        public function getOutVertex(){
            
            return $this->getProperty('_outV');
            
        }
        
        public function getLabel(){
            
            return $this->getProperty('_label');
            
        }
        
        /**
         * (non-PHPdoc)
         * @see \Rexster\Object::create()
         */
        public function create(){
            
            if (!$this->getProperty('_inV')){
                throw new \InvalidArgumentException('Missing _inV (in vertex) property');
            }
            
            if (!$this->getProperty('_outV')){
                throw new \InvalidArgumentException('Missing _outV (out vertex) property');
            }
            
            if (!$this->getProperty('_label')){
                throw new \InvalidArgumentException('Missing _label property');
            }
            
            return parent::create();
            
        }
        
        public function delete(array $properties = array()){
        
            if (!empty($properties)){
                throw new \RuntimeException('Cannot delete properties from edges yet - Rexster deletes the entire edge');
            }
            
            return parent::delete();
        
        }
        
    }
