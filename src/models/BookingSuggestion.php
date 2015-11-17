<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2015-11-16
 * Time: 17:18
 */

namespace model;


class BookingSuggestion {



    /**
     * @var String
     */
    private $movieName;

    /**
     * @var String
     */
    private $day;

    /**
     * @var string
     */
    private $time;

    /**
     * @var array time
     */
    private $availableDinnerTimes = array();


    public function __construct($name, $day, $time) {
        $this->movieName = $name;
        $this->day = $day;
        $this->time = $time;
    }

    public function getMovieName() {
        return $this->movieName;
    }

    public function getDay() {
        return $this->day;
    }

    public function getTime() {
        return $this->time;
    }

    public function addAvailableDinnerTime($time) {
        $this->availableDinnerTimes[] = date( "H:i", $time);
    }

    public function getAvailableDinnerTimes() {
        return $this->availableDinnerTimes;
    }


}