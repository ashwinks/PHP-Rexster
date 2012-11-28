<?php

	require_once 'tests/library/Rexster/RexsterTests.php';

    class RexsterGraphTest extends RexsterTests {

        
        public function testGetGraph(){

            $graph = $this->graph->get();

            $this->assertTrue($graph instanceof Rexster_Graph);
            $this->assertTrue($graph->name == $this->graph_name);

        }

        public function testGetVertex(){

            $vertex = $this->graph->getVertex($this->vertex_id);

            $this->assertTrue($vertex instanceof Rexster_Vertex);
            $this->assertTrue(isset($vertex->_id));
            $this->assertTrue(!empty($vertex->_id));
            $this->assertTrue(isset($vertex->id));
            $this->assertTrue(!empty($vertex->id));

        }

        public function testGetDirectionalVerticesInvalidDirection(){

            $direction = 'fail';

            $this->setExpectedException('Rexster_Exception', "Invalid direction {$direction}");

            $vertices = $this->graph->getDirectionalVertices($this->vertex_id, $direction);

        }

        public function testGetDirectionalVerticesInvalidVertexId(){

            $direction = 'both';

            $this->setExpectedException('Rexster_Exception', "Invalid vertex id");

            $vertices = $this->graph->getDirectionalVertices(null, $direction);

        }

        public function testGetDirectionalVerticesIn(){

            $vertices = $this->graph->getDirectionalVertices($this->vertex_id, 'in');

            $this->assertTrue(is_array($vertices));
            $this->assertTrue($vertices[0] instanceof Rexster_Vertex);
            $this->assertTrue(!empty($vertices[0]->_id));
            $this->assertTrue(!empty($vertices[0]->id));
            $this->assertTrue(isset($vertices[0]->_id));
            $this->assertTrue(isset($vertices[0]->id));

        }

        public function testGetDirectionalVerticesOut(){

            $vertices = $this->graph->getDirectionalVertices($this->vertex_id, 'out');

            $this->assertTrue(is_array($vertices));
            $this->assertTrue($vertices[0] instanceof Rexster_Vertex);
            $this->assertTrue(!empty($vertices[0]->_id));
            $this->assertTrue(!empty($vertices[0]->id));
            $this->assertTrue(isset($vertices[0]->_id));
            $this->assertTrue(isset($vertices[0]->id));

        }

        public function testGetDirectionalVerticesBoth(){

            $vertices = $this->graph->getDirectionalVertices($this->vertex_id, 'both');

            $this->assertTrue(is_array($vertices));
            $this->assertTrue($vertices[0] instanceof Rexster_Vertex);
            $this->assertTrue(!empty($vertices[0]->_id));
            $this->assertTrue(!empty($vertices[0]->id));
            $this->assertTrue(isset($vertices[0]->_id));
            $this->assertTrue(isset($vertices[0]->id));
            $this->assertEquals('vertex', $vertices[0]->_type);

        }

        public function testGetEdgeInvalidEdgeId(){

            $this->setExpectedException('Rexster_Exception', "Invalid edge id");

            $edge = $this->graph->getEdge(null);

        }

        public function testGetEdge(){

            $edge = $this->graph->getEdge($this->edge_id);

            $this->assertTrue($edge instanceof Rexster_Edge);
            $this->assertTrue(!empty($edge->_id));
            $this->assertTrue(!empty($edge->id));
            $this->assertTrue(isset($edge->_id));
            $this->assertTrue(isset($edge->id));
            $this->assertEquals('edge', $edge->_type);
        }

        public function testGetIndexInvalidIndexName(){

            $this->setExpectedException('Rexster_Exception', 'Invalid index name');

            $this->graph->getIndexedData(null, 'key', 'value');

        }

        public function testGetIndexInvalidKey(){

            $indexname = "participant";

            $this->setExpectedException('Rexster_Exception', "Invalid index key for index ({$indexname})");

            $this->graph->getIndexedData($indexname, null, 'value');

        }

        public function testGetIndexInvalidValue(){

            $indexname = "participant";
            $key = 'key';

            $this->setExpectedException('Rexster_Exception', "Invalid index ({$indexname} -- {$key}) value");

            $this->graph->getIndexedData($indexname, $key, null);

        }

        public function testGetIndex(){

            $indexname = 'type';
            $key = 'type';
            $value = 'participant';

            $vertices = $this->graph->getIndexedData($indexname, $key, $value);

            $this->assertTrue(is_array($vertices));
            $this->assertTrue($vertices[0] instanceof Rexster_Vertex);
            $this->assertTrue(!empty($vertices[0]->_id));
            $this->assertTrue(!empty($vertices[0]->id));
            $this->assertTrue(isset($vertices[0]->_id));
            $this->assertTrue(isset($vertices[0]->id));
            $this->assertEquals('vertex', $vertices[0]->_type);

        }

        public function testGetIndices(){

            $indices = $this->graph->getIndexes();

            $this->assertTrue(is_array($indices));
            $this->assertTrue($indices[0] instanceof Rexster_Index);

        }
        
        public function testGetVerticesByAttributeMultiple(){
 
            $vertices = $this->graph->getVertexByAttribute('type', 'object');
                
            $this->assertTrue(is_array($vertices));
            foreach ($vertices as $vertex){
                $this->assertNotEmpty($vertex->id);
            }

        }
        
        public function testGetVerticesByAttributeSingle(){

        	$vertex = $this->graph->getVertexByAttribute('oid', 'relationship_single');

        	$this->assertTrue($vertex instanceof Rexster_Vertex);
        	$this->assertNotEmpty($vertex->id);
        
        }
        

    }