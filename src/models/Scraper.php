<?php

namespace model;

class Scraper {

    private static $frontPageQuery = '//li/a';
    private static $calendarFronPageQuery = '//div[@class="col s12 center"]//li/a';


    private $okDays = array();

    /**
     * The url scraping should start with
     * @var string
     */
    private $url;

    private $curlOptions;

    public function __construct($url) {
        $this->url = $url;

    }

    public function scrape() {
        $frontPageNodes = $this->getDOMNodeList($this->url, self::$frontPageQuery);
        $links = array();

        foreach($frontPageNodes as $node) {
            $links[] = $node->getAttribute("href");
        }

        $this->doCalendar($links[0]);
}


    private function doCalendar($href) {
        $calendareNodes = $this->getDOMNodeList($this->url . $href, self::$calendarFronPageQuery);
        foreach($calendareNodes as $node) {
            $tableHead = $this->getDOMNodeList($this->url . $href . "/" . $node->getAttribute("href"),
                                                        '//table//thead/th');
            //TODO: scrape tableBody and compare to tHead

        }
    }

    /**
     * Takes an url to a webpage and makes a DOMXPatch of it.
     *
     * @param $url
     * @return \DOMXPath
     */
    private function getDOMXPath($url) {
        // get the page via curl first
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);


        $data = curl_exec($ch);
        curl_close($ch);

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
}