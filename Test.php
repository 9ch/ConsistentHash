<?php

use Icarus\ConsistentHash\ConsistentHash;

require_once "./vendor/autoload.php";

$con = new ConsistentHash();
$con->addNodes(['Node1', 'Node2', 'Node3']);
var_dump($con->getNodeList());
var_dump($con->getNode('127.0.0.1'));//'Node2'