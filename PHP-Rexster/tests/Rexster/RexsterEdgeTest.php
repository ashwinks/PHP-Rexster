<?php

	require_once 'tests/library/Rexster/RexsterTests.php';

    class RexsterEdgeTest extends RexsterTests {


        public function testUpdateEdge(){

            $edge_id = $this->edge_id;

            $edge = $this->graph->getEdge($edge_id);

            $this->assertTrue($edge instanceof Rexster_Edge);
            $this->assertSame($edge_id, $edge->_id);
            $this->assertSame($edge_id, $edge->id);

            $edge->position = 'soneposition';
            $edge->someotherproperty = 'Ashywin';
            $response = $edge->update();

            $this->assertTrue($response instanceof Rexster_Edge);
            $this->assertSame($edge_id, $response->_id);
            $this->assertSame($edge_id, $response->id);

            foreach ($response as $k => $v){
                $this->assertSame($v, $response->{$k});
            }

        }

        public function testUpdateEdgeInvalidId(){

            $this->setExpectedException('Rexster_Exception', "Invalid object id to update");
            
            $edge = new Rexster_Edge($this->graph, null);
            $edge->update();

        }

        public function testUpdateEdgeMismatchedIds(){

            $edge_id = $this->edge_id;
            $fake_edge_id = 123231;

            $this->setExpectedException('Rexster_Exception', "Node ID's do not match? this->_id = {$edge_id} -- this->id = {$fake_edge_id}");

            $graph = new Rexster_Graph($this->graph_url, $this->graph_name);
            $edge = $graph->getEdge($edge_id);
            $edge->id = $fake_edge_id;

            $edge->update();

        }

        public function testCreateEdge(){

            $properties = array(
                '_outV' => 525,
                '_label' => 'played',
                '_inV' => 377,
                'someproperty' => 'someval',
                'position' => 4
            );

            $edge = new Rexster_Edge($this->graph, $properties);
            $response = $edge->create();

            $this->assertTrue($response instanceof Rexster_Edge);
            $this->assertTrue(!empty($response->id));
            $this->assertTrue(!empty($response->_id));

            foreach ($properties as $k => $v){
                $this->assertSame($v, $response->{$k});
            }

        }

    }