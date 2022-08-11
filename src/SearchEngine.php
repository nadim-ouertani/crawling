<?php

namespace SearchEngine;
use ArrayIterator;
use Exception;

Class SearchEngine {
    public $engine, $api;

    public function setEngine($engine) {
        $this->engine = $engine;
    }

    public function setApi($api) {
        $this->api = $api;
    }

    /**
     * @throws Exception
     */
    public function search(array $keywords): ArrayIterator
    {
        require_once('DataHelper.php');
        $helpers = new DataHelper();
        $finalResult = array();
        foreach ($keywords as $value) {
            $responseArray = $helpers->filterData($helpers->curlData($value, $this->engine, $this->api));
            $finalResult[] = $helpers->processingData($responseArray, $value);
        }
        return new ArrayIterator(array_reduce($finalResult, 'array_merge', array()));
    }
}