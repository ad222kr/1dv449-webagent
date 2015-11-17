<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2015-11-16
 * Time: 17:18
 */

namespace model;


class Movie {



    /**
     * @var String
     */
    private $name;

    /**
     * @var String
     */
    private $day;

    /**
     * @var string
     */
    private $time;


    public function __construct($name, $day, $time) {
        $this->name = $name;
        $this->day = $day;
        $this->time = $time;
    }

    public function getName() {
        return $this->name;
    }

    public function getDay() {
        return $this->day;
    }

    public function getTime() {
        return $this->time;
    }


}