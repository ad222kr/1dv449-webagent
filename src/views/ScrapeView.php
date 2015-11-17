<?php

namespace view;

class ScrapeView {

    private $movies;

    public function __construct($movies) {
        $this->movies = $movies;
    }


    public function getResponse() {
        $html = "<h1>Följande filmer hittades</h1>";
        $html.= "<ul>";

        foreach ($this->movies as $movie) {

            $html.= "<li>
                        Filmen ". $movie->getName() . " klockan " . $movie->getTime() . " på " . $movie->getDay() . "
                        <a href='#'>Välj denna och boka bord</a>";

        }

        return $html;
    }

}