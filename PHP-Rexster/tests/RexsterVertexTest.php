<?php

	require_once 'tests/library/Rexster/RexsterTests.php';

    class RexsterVertexTest extends RexsterTests {

        public function testUpdateVertex(){

            $vertex_id = $this->vertex_id;

            $vertex = $this->graph->getVertex($vertex_id);

            $this->assertTrue($vertex instanceof Rexster_Vertex);
            $this->assertEquals($vertex_id, $vertex->_id);
            $this->assertEquals($vertex_id, $vertex->id);

            $vertex->lastName = 'so2meshit';
            $vertex->firstName = 'Ashywin';
            $response = $vertex->update();

            $this->assertTrue($response instanceof Rexster_Vertex);
            $this->assertEquals($vertex_id, $response->_id);
            $this->assertEquals($vertex_id, $response->id);

            foreach ($vertex as $k => $v){
                $this->assertEquals($v, $response->{$k});
            }

        }

        public function testUpdateVertexInvalidId(){

            $this->setExpectedException('Rexster_Exception', "Invalid object id to update");

            $vertex = new Rexster_Vertex($this->graph, null);
            $vertex->update();

        }

        public function testUpdateVertexMismatchedIds(){

            $vertex_id = 524;
            $fake_vertex_id = 123231;

            $this->setExpectedException('Rexster_Exception', "Node ID's do not match? this->_id = {$vertex_id} -- this->id = {$fake_vertex_id}");

            $vertex = $this->graph->getVertex($vertex_id);
            $vertex->id = $fake_vertex_id;

            $vertex->update();

        }

        public function testCreateVertex(){

            $properties = array(
                'firstName' => 'greer',
                'lastName' => 'swallows',
                'type' => 'participant'
            );

            $vertex = new Rexster_Vertex($this->graph, $properties);
            $response = $vertex->create();

            $this->assertTrue($response instanceof Rexster_Vertex);
            $this->assertTrue(!empty($response->id));
            $this->assertTrue(!empty($response->_id));

            foreach ($properties as $k => $v){
                $this->assertSame($v, $response->{$k});
            }

        }

        public function testGetEdges(){

            $vertex_id = $this->vertex_id;

            $vertex = $this->graph->getVertex($vertex_id);

            $edges = $vertex->getDirectionalEdges('bothE');

            $this->assertTrue(is_array($edges));
            foreach ($edges as $edge){
                $this->assertTrue($edge instanceof Rexster_Edge);
                $this->assertTrue(isset($edge->id) && !empty($edge->id));
                $this->assertTrue(isset($edge->_id) && !empty($edge->_id));
            }

        }

        public function testGetEdgesByLabel(){

            $vertex_id = $this->vertex_id;

            $vertex = $this->graph->getVertex($vertex_id);

            $edges = $vertex->getDirectionalEdges('both', 'involved');

            $this->assertTrue(is_array($edges));
            foreach ($edges as $edge){
                $this->assertTrue($edge instanceof Rexster_Edge);
                $this->assertTrue(isset($edge->id) && !empty($edge->id));
                $this->assertTrue(isset($edge->_id) && !empty($edge->_id));
            }

        }

        public function testGetEdgesInvalidDirection(){

            $vertex_id = $this->vertex_id;
            $direction = 'flux';

            $this->setExpectedException('Rexster_Exception', "Invalid direction {$direction}");

            $vertex = $this->graph->getVertex($vertex_id);

            $edges = $vertex->getDirectionalEdges($direction);

        }

        public function testGetEdgesInvalidVertexId(){

            $vertex_id = $this->vertex_id;
            $direction = 'out';

            $this->setExpectedException('Rexster_Exception', "Invalid vertex id");
            
            $vertex = $this->graph->getVertex($vertex_id);
            unset($vertex->id);

            $edges = $vertex->getDirectionalEdges($direction);

        }

    }