<?php


namespace Icarus\ConsistentHash;


class ConsistentHash
{
    protected $virtualDomNum;
    protected $hashFunc;
    protected $virtualDom = [];

    public function __construct($virtualDomNum = 200, $hashFunc = 'crc32')
    {
        $this->virtualDomNum = $virtualDomNum;
        $this->hashFunc = $hashFunc;
    }

    /**
     * @param $node
     * @return $this
     */
    public function addNode($node)
    {
        for ($i = 0; $i < $this->virtualDomNum; $i++) {
            $hash = $this->createHash($node . $i);
            $this->virtualDom[$hash] = $node;
        }
        return $this;
    }

    /**
     * @param $nodes
     * @return string
     */
    public function addNodes($nodes)
    {
        if (!is_array($nodes)) {
            return "param is not a array";
        }
        foreach ($nodes as $node) {
            $this->addNode($node);
        }
    }

    /**
     * @return array
     */
    public function getNodeList()
    {
        return $this->virtualDom;
    }

    /**
     * @param $ip
     * @return mixed|string
     */
    public function getNode($ip)
    {
        if (empty($this->virtualDom)) {
            return "Node exists";
        }
        ksort($this->virtualDom);
        $hash = $this->createHash($ip);
        foreach ($this->virtualDom as $key => $value) {
            if ($key > $hash) {
                return $this->virtualDom[$key];
            }
        }
        return $this->virtualDom[0];

    }

    /**
     * @param $string
     * @return mixed
     */
    protected function createHash($string)
    {
        return call_user_func($this->hashFunc, $string);
    }
}