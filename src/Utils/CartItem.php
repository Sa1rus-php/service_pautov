<?php

namespace App\Utils;

class CartItem
{
    protected $id;
    protected $price = 0;
    protected $product_id;
    protected $subProduct_id;
    protected $modelSubProduct_id;

    public function __construct(array $options = [])
    {
        if (!empty($options)) {
            $this->configure($options);
        }
    }

    public function configure(array $options)
    {
        foreach ($options as $option => $value) {
            $method = 'set' . $option;
            if (method_exists($this, $method)) {
                $this->$method($value);
            } elseif (method_exists($this, 'get' . $option)) {
                throw new \LogicException('Cannot set read-only property: ' . $option);
            } else {
                $this->$option = $value;
            }
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if (false === $this->validateInteger($id)) {
            throw new \InvalidArgumentException('Id must be an integer and not negative');
        }
        $this->id = (int)$id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        if (false === $this->validateFloat($price)) {
            throw new \InvalidArgumentException('Price must be numeric and not negative');
        }
        $this->price = (float)$price;
    }

    public function getProductId()
    {
        return $this->product_id;
    }

    public function setProductId($product_id)
    {
        if (false === $this->validateInteger($product_id)) {
            throw new \InvalidArgumentException('Product ID must be an integer and not negative');
        }
        $this->product_id = (int)$product_id;
    }

    public function getSubProductId()
    {
        return $this->subProduct_id;
    }

    public function setSubProductId($subProduct_id)
    {
        if (false === $this->validateInteger($subProduct_id)) {
            throw new \InvalidArgumentException('SubProduct ID must be an integer and not negative');
        }
        $this->subProduct_id = (int)$subProduct_id;
    }

    public function getModelSubProductId()
    {
        return $this->modelSubProduct_id;
    }

    public function SetModelSubProductId($modelSubProduct_id)
    {
        if (false === $this->validateInteger($modelSubProduct_id)) {
            throw new \InvalidArgumentException('SubProduct ID must be an integer and not negative');
        }
        $this->modelSubProduct_id = (int)$modelSubProduct_id;
    }

    public function isValid()
    {
        return $this->validateInteger($this->id) &&
            $this->validateInteger($this->product_id) &&
            $this->validateInteger($this->subProduct_id) &&
            $this->validateInteger($this->modelSubProduct_id) &&
            $this->validateFloat($this->price);
    }

    public function validateInteger($value)
    {
        $options = ['options' => ['min_range' => 0, 'max_range' => PHP_INT_MAX]];

        return filter_var($value, FILTER_VALIDATE_INT, $options) !== false;
    }

    public function validateFloat($value)
    {
        $options = ['options' => ['decimal' => '.']];

        return filter_var($value, FILTER_VALIDATE_FLOAT, $options) !== false && ($value >= 0);
    }
}
