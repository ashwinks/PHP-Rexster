<?php

    namespace Rexster;
    
    class Factory {
        
        /**
         * 
         * @param Client $client
         * @param array $data
         * @param string $type
         * @return NULL|Vertex|Edge|[array]Vertex|[array]Edge
         */
        public static function getObject(Client $client, $data = array(), $type = null){
            
            if (!$data || empty($data) || !isset($data['results']) || empty($data['results'])){
                return false;
            }

            if (isset($data['results'][0]) && is_array($data['results'][0]) && count($data['results']) >= 1){

                $collection = array();
                foreach ($data['results'] as $item){
                    
                    $classname = self::_getClassNameFromData($item);
                    array_push($collection, new $classname($client, $item));
                    
                }
            
                return $collection;
            
            }else if (count($data['results']) > 0 && !isset($data['results'][0])){

                $classname = self::_getClassNameFromData($data['results']);

                return new $classname($client, $data['results']);

            }else{

                return false;
                
            }

        }
        
        public static function getGeneric(Client $client, array $data = array()){
            
            if (!$data || empty($data)){
                return false;
            }
            
            return new Object($client, $data);
            
        }
        
        private static function _getClassNameFromData($data){
            
            if (isset($data['_type'])){

                return '\\' . __NAMESPACE__ . '\\' . ucfirst(strtolower($data['_type']));
                
            }else if (isset($data['_outV']) || isset($data['inV'])){
                    
                return '\\' . __NAMESPACE__ . '\\Edge';
                
            }else{
                
                return '\\' . __NAMESPACE__ . '\\Object';
                
            }
            
        }

    }