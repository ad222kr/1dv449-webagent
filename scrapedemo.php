<?php

/**
 * Test shit
 */
class scrapedemo {

    public function run() {
        $data = $this->curl_get_request("http://coursepress.lnu.se/kurser/");

        $dom = new DOMDocument();

        if ($dom->loadHTML($data)) {
            $xpath = new DOMXPath($dom);

            $items = $xpath->query('//ul[@id = "blogs-list"]//div[@class = "item-title"]/a');

            foreach($items as $item) {
                echo $item->nodeValue . " --> " . $item->getAttribute("href") . "<br />";
            }

        } else {
            die("Fel vid inläsning av html-dokument");
        }
    }

    private function curl_get_request($url) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $data = curl_exec($ch);

        curl_close($ch);

        return $data;

    }


    private function curl_cookie_handling($url) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, TRUE);

        $post_arr = array(
            "code" => "1234"
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_arr);

        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__) . "/kaka.txt");

        $data = curl_exec($ch);
    }

}