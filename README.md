PHP-Rexster
===========

PHP SDK for the [Rexster REST API](https://github.com/tinkerpop/rexster/wiki/Basic-REST-API)

Usage
===========

Create a graph object
```php
$graph = new Graph('http://localhost:8182', 'testing');
```

Get a vertex from the graph by vertex id
```php
$vertex = $graph->getVertex($vertex_id);
```

Get an edge from the graph by edge id
```php
$edge = $graph->getEdge($edge_id);
```

Get a vertex by an indexed property or attribute
```php
$vertex = $graph->getVertexByAttribute('name', $name);
```

Get all vertices with offsets for paging
```php
$graph->setOffsetStart(5);
$graph->setOffsetEnd(10);
$vertices = $graph->getVertices();
```

Create a vertex
```php
$vertex = new Vertex($graph);
$vertex->some_property = "some property";
$vertex->another_attribute = "haay";
$vertex->create();
```

Create an edge
```php
$edge = new Edge($graph);
$e->some_property = 'edge name property';
$e->_inV = 4;
$e->_outV = 8;
$e->_label = "knows";
```

Create an in/out edges for a given vertex
```php
$vertex = $graph->getVertex($vertex_id);
$vertex->createOutEdge($to_vertex_id, 'likes', $data);
$vertex->createInEdge($from_vertex_id, 'likes', $data);
```

Gremlin scripts
```php
$gr = new Gremlin($g);
$response = $gr->runScript("g.v({$vertex_id}).out.path");
```
