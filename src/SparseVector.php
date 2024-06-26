<?php

namespace Pgvector;

class SparseVector
{
    protected $dimensions;
    protected $indices;
    protected $values;

    public function __construct($dimensions, $indices, $values)
    {
        $this->dimensions = $dimensions;
        $this->indices = $indices;
        $this->values = $values;
    }

    public static function fromDense($value)
    {
        $dimensions = count($value);
        $indices = [];
        $values = [];
        foreach ($value as $i => $v) {
            if ($v != 0) {
                $indices[] = $i;
                $values[] = floatval($v);
            }
        }
        return new SparseVector($dimensions, $indices, $values);
    }

    public static function fromString($value)
    {
        $parts = explode('/', $value, 2);
        $dimensions = intval($parts[1]);
        $indices = [];
        $values = [];
        $elements = explode(',', substr($parts[0], 1, -1));
        foreach ($elements as $e) {
            $ep = explode(':', $e, 2);
            $indices[] = intval($ep[0]) - 1;
            $values[] = floatval($ep[1]);
        }
        return new SparseVector($dimensions, $indices, $values);
    }

    public function __toString()
    {
        $elements = [];
        for ($i = 0; $i < count($this->indices); $i++) {
            $elements[] = ($this->indices[$i] + 1) . ':' . $this->values[$i];
        }
        return '{' . implode(',', $elements) . '}/' . $this->dimensions;
    }

    public function toArray()
    {
        $result = array_fill(0, $this->dimensions, 0.0);
        for ($i = 0; $i < count($this->indices); $i++) {
            $result[$this->indices[$i]] = $this->values[$i];
        }
        return $result;
    }
}
