<?php

namespace model;

require_once("src/models/BookingSuggestion.php");

class Scraper {

    /**
     * Queries used by the scraper-
     *
     * @var string
     */
    private static $frontPageQuery = '//li/a';
    private static $calendarFrontPageQuery = '//div[@class="col s12 center"]//li/a';
    private static $calendarTableHeadQuery = '//table//thead//tr//th';
    private static $calendarTableBodyQuery = '//table//tbody//tr//td';
    private static $moviePageDayQuery = '//select[@id="day"]/option[not(@disabled)]';
    private static $moviePageMovieQuery = '//select[@id="movie"]/option[not(@disabled)]';

    /**
     * @var array - [String][String]
     */
    private $availableDays = array();

    /**
     * @var array [\model\BookingSuggestion]
     */
    private $movieSuggestions = array();

    /**
     * @var array
     */
    private $dinnerTableSuggestions = array();

    /**
     * The url scraping should start with
     * @var string
     */
    private $url;


    /**
     * @param $url - string
     */
    public function __construct($url) {
        libxml_use_internal_errors(TRUE); // hides errors from MS_word generated shit
        $this->url = preg_replace('{/$}', '', $url);
    }

    public function scrape() {
        $links = $this->scrapeFrontPageLinks();

        $this->scrapeAvailableDays($links[0]);

        $this->scrapeMovieSuggestions($links[1]);

        $this->scrapeDinnerTableSuggestions($links[2]);
    }

    /**
     * Scrapers the frontpage for the first 3 links
     *
     * @return array
     */
    private function scrapeFrontPageLinks() {
        $frontPageNodes = $this->getDOMNodeList($this->url, self::$frontPageQuery);
        $links = array();

        foreach($frontPageNodes as $node) {
            $links[] = $node->getAttribute("href");
        }

        return $links;
    }

    /**
     * @param $href - part of the url to the dinner-page
     */
    private function scrapeDinnerTableSuggestions($href) {
        $url = $this->url . $href;

        foreach ($this->availableDays as $day) {
            $dayPrefix = $this->getDayPrefix($day);

            $dinnerTimes = $this->getDOMNodeList($url, '//input[contains(@value, "' . $dayPrefix .'")]');
            $this->setAvailableTimesForMovieSuggestion($dinnerTimes, $day);
        }
    }

    /**
     * Takes a \DomNodeList of available dinnertimes. Checks that the day is right and that
     * time of reservation is 2 hours after movie. Adds correct times to booking-suggestion.
     *
     * @param \DOMNodeList $dinnerTimes
     * @param $day
     */
    private function setAvailableTimesForMovieSuggestion(\DOMNodeList $dinnerTimes, $day) {
        foreach ($this->movieSuggestions as $suggestion) {
            if ($suggestion->getDay() === $day) {
                foreach ($dinnerTimes as $dinnerTime) {
                    $dinnerStartTime = substr($dinnerTime->getAttribute("value"), 3, 2);
                    $movieTime = substr($suggestion->getTime(), 0, 2);
                    if (intval($movieTime) + 2 <= intval($dinnerStartTime)) {
                        $suggestion->addAvailableDinnerTime(strtotime($dinnerStartTime . ":00")); //variable is w/o seconds
                    }
                }
            }
        }
    }

    /**
     * @param $day string
     * @return string
     */
    private function getDayPrefix($day) {
        switch($day) {
            case "Fredag":
                return "fre";
            case "Lördag":
                return "lor";
            case "Söndag":
                return "son";
        }
    }

    /**
     * @param $href - part of the url to the movie-page that will be added to base-url
     * @return array of \model\Movie with suggestions
     */
    private function scrapeMovieSuggestions($href) {

        //TODO: this would need refactoring
        $url = $this->url . $href;

        $dayOptions = $this->getDOMNodeList($url, self::$moviePageDayQuery);
        $movieOptions = $this->getDOMNodeList($url, self::$moviePageMovieQuery);

        foreach($movieOptions as $movieOpt) {
            foreach ($dayOptions as $dayOpt) {
                foreach ($this->availableDays as $day) {
                    if ($day === $dayOpt->nodeValue) {
                        $jsonMovies = json_decode($this->getPageData($url."/check?day=" .$dayOpt->getAttribute("value")
                            . "&movie=" . $movieOpt->getAttribute("value")));

                        foreach ($jsonMovies as $jsonMovie) {
                            if ($jsonMovie->status === 1) { //status == 1 indicates there are seats left
                                $this->movieSuggestions[] = new BookingSuggestion($movieOpt->nodeValue,
                                    $dayOpt->nodeValue, $jsonMovie->time);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $href - part of the url to the calendar-page that will be added to base-url
     * @return array of type string
     */
    private function scrapeAvailableDays($href) {
        $calendareNodes = $this->getDOMNodeList($this->url . $href, self::$calendarFrontPageQuery);

        $calendars = array();

        foreach($calendareNodes as $node) {
            $tableHead = $this->getDOMNodeList($this->url . $href . "/" . $node->getAttribute("href"),
                                               self::$calendarTableHeadQuery);
            $tableBody = $this->getDOMNodeList($this->url . $href . "/" . $node->getAttribute("href"),
                                               self::$calendarTableBodyQuery);
            $calendars[] = $this->getCalendarArray($tableHead, $tableBody);
        }

        $days = call_user_func_array('array_intersect_assoc', $calendars);
        foreach ($days as $key => $value) {
            $this->availableDays[] = $key; // Key is the day, value is "OK". Easier to work with it without key
        }
    }

    /**
     * Takes the tablehead and tablebody of a persons calendar and makes it into an array tellin
     * which days are available and which are not
     *
     * @param \DOMNodeList $tableHead - says what day it is
     * @param \DOMNodeList $tableBody - sats if the day is free or not
     * @return array - key[string] - value[string]. "Friday" => "Ok" etc.
     */
    private function getCalendarArray(\DOMNodeList $tableHead, \DOMNodeList $tableBody) {
        $calendar = array();

        for ($i = 0; $i < $tableHead->length; $i++) {
            $calendar[$this->translateDayToSwedish($tableHead->item($i)->nodeValue)] =
                strtolower($tableBody->item($i)->nodeValue);
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

    /**
     * Takes an url to a webpage and makes a DOMNodeList of it
     *
     * @param $url
     * @param $query
     * @return \DOMNodeList
     */
    private function getDOMNodeList($url, $query) {
        $xpath = $this->getDOMXPath($url);
        return $xpath->query($query);
    }

    /**
     * Just gets the pages data without doing anything with it
     *
     * @param $url
     * @return string | false. Page data if success, false if failure
     */
    private function getPageData($url) {
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_FOLLOWLOCATION => TRUE,
            CURLOPT_USERAGENT => "skrapan@skapan.com" // good ethics to add mail addres in useragent?
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);

        if (!$data)
            trigger_error(curl_error($ch));

        curl_close($ch);
        return $data;
    }

    /**
     * @return array
     */
    public function getMovieSuggestions() {
        return $this->movieSuggestions;
    }

    /**
     * @param $day
     * @return string
     */
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