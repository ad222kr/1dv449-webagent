<?php

namespace model;

require_once("src/models/Movie.php");

class Scraper {

    private static $frontPageQuery = '//li/a';
    private static $calendarFronPageQuery = '//div[@class="col s12 center"]//li/a';


    private $availableDays = array();
    private $movieSuggestions = array();

    /**
     * The url scraping should start with
     * @var string
     */
    private $url;

    private $curlOptions;

    public function __construct($url) {
        $this->url = "localhost:8080"; //$url;
    }

    public function scrape() {
        $frontPageNodes = $this->getDOMNodeList($this->url, self::$frontPageQuery);
        $links = array();

        foreach($frontPageNodes as $node) {
            $links[] = $node->getAttribute("href");
        }

        $this->availableDays = $this->getAvailableDays($links[0]);
        $this->movieSuggestions = $this->getMovieSuggestions($links[1]);
    }

    private function getMovieSuggestions($href) {
        $url = $this->url . $href;
        $movies = array();

        $dayOptions = $this->filterDisabled($this->getDOMNodeList($url, '//select[@id="day"]/option'));
        $movieOptions = $this->filterDisabled($this->getDOMNodeList($url, '//select[@id="movie"]/option'));
        
        foreach($movieOptions as $movieOpt) {

            foreach ($dayOptions as $dayOpt) {
                $jsonMovies = json_decode($this->getPageData($url."/check?day=" .$dayOpt->getAttribute("value") .
                                                             "&movie=" . $movieOpt->getAttribute("value")));
                foreach ($jsonMovies as $jsonMovie) {
                    if ($jsonMovie->status === 1) {
                        $movie = new Movie();
                        $movie->name = $movieOpt->nodeValue;
                        $movie->day = $dayOpt->nodeValue;
                        $movie->time = $jsonMovie->time;
                        $movies[] = $movie;
                    }
                }
            }
        }

        foreach($movies as $movie) {
            echo $movie->name . " " . $movie->day . " " .$movie->time ."<br/>";
        }
        return $movies;
    }

    /**
     * Filters the select-elements that are disabled
     * and returns an array (since it uses array_filter)
     *
     * @param \DOMNodeList $DOMNodeList
     *
     * @return array
     */
    private function filterDisabled(\DOMNodeList $DOMNodeList) {
        return array_filter(iterator_to_array($DOMNodeList),
            function($node) {
                return empty($node->getAttribute("disabled"));
            });
    }

    private function getAvailableDays($href) {
        $calendareNodes = $this->getDOMNodeList($this->url . $href, self::$calendarFronPageQuery);

        $calendars = array();

        foreach($calendareNodes as $node) {
            $tableHead = $this->getDOMNodeList($this->url . $href . "/" . $node->getAttribute("href"),
                                                        '//table//thead//tr//th'); // string dep
            $tableBody = $this->getDOMNodeList($this->url . $href . "/" . $node->getAttribute("href"),
                                               '//table//tbody//tr//td'); // string dep
            $calendars[] = $this->getCalendarArray($tableHead, $tableBody);
        }

        return call_user_func_array('array_intersect_assoc', $calendars);

    }

    private function getCalendarArray(\DOMNodeList $tableHead, \DOMNodeList $tableBody) {
        $calendar = array();

        for ($i = 0; $i < $tableHead->length; $i++) {
            $calendar[$tableHead->item($i)->nodeValue] = strtolower($tableBody->item($i)->nodeValue);
        }
        return $calendar;
    }

    /**
     * Takes an url to a webpage and makes a DOMXPatch of it.
     *
     * @param $url
     * @return \DOMXPath
     */
    private function getDOMXPath($url) {
        // get the page via curl first

        $data = $this->getPageData($url);
        // make a DOMDocument of it
        $domDoc = new \DOMDocument();
        if ($domDoc->loadHTML($data)){
            return new \DOMXPath($domDoc);
        } else {
            die("Fel vid inläsning av html-dokument");
        }
    }

    private function getDOMNodeList($url, $query) {
        $xpath = $this->getDOMXPath($url);
        return $xpath->query($query);
    }

    private function getPageData($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    private function translateDayToSwedish($day) {
        switch(strtolower($day)) {
            case "friday":
                return "Fredag";
            case "saturday":
                return "Lördag";
            case "sunday":
                return "Söndag";
        }
    }

}