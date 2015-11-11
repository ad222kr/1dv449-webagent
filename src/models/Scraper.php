<?php

namespace model;

class Scraper {

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
        $this->getLinksToApp($this->url);
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

        $data = curl_exec($ch);
        curl_close($ch);

        // make a DOMDocument of it
        $domDoc = new \DOMDocument();
        if ($domDoc->loadHTML($data)){
            return new \DOMXPath($domDoc);
        }

    }

    private function getLinksToApp($url) {
        $xpath = $this->getDOMXPath($url);
        $links = $xpath->query('//li/a');

        var_dump($links);
    }





}