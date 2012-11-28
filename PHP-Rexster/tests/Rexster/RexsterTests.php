<?php

    require_once 'tests/TestControllerAbstract.php';
    require_once 'Rexster/Rexster_Graph.php';
    require_once 'Rexster/Rexster_Vertex.php';
    require_once 'Rexster/Rexster_Edge.php';

    class RexsterTests extends TestControllerAbstract {
        
        public $graph_url = 'http://localhost:8182';
        public $graph_name = 'testing';
        public $graph;
        
        public $vertex_id = 200;
        public $edge_id = 212123;
        
        public function setUp(){
            
            parent::setUp();
            
            $this->graph = new Rexster_Graph($this->graph_url, $this->graph_name);
            
            
        }
        
        public function testMock(){
            $this->assertTrue(true);
        }

    }