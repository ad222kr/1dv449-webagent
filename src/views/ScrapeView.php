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
                        Filmen ". $movie->getMovieName() . " klockan " . $movie->getTime() . " på " . $movie->getDay() .
                        ". Följande tider finns tillgänliga på Zekes Restaurang";

            $html.= "<ul>";

            if (empty($movie->getAvailableDinnerTimes())){
                $html.= "<li>Inga tider finns</li>";
            }
            else {
                foreach ($movie->getAvailableDinnerTimes() as $time) {

                    $html.= "<li>" . $time . "</li>";

                }
            }

            $html.= "</ul></li>";
        }
        return $html;
    }
}