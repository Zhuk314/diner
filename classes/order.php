<?php

class Order
{
    private $_food;
    private $_meel;
    private $_condiments;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->_food = "";
    }

    /**
     * @return string
     */
    public function getFood(): string
    {
        return $this->_food;
    }

    /**
     * @param string $food
     */
    public function setFood(string $food): void
    {
        $this->_food = $food;
    }

    /**
     * @return mixed
     */
    public function getMeel()
    {
        return $this->_meel;
    }

    /**
     * @param mixed $meel
     */
    public function setMeel($meel): void
    {
        $this->_meel = $meel;
    }

    /**
     * @return mixed
     */
    public function getCondiments()
    {
        return $this->_condiments;
    }

    /**
     * @param mixed $condiments
     */
    public function setCondiments($condiments): void
    {
        $this->_condiments = $condiments;
    }


}