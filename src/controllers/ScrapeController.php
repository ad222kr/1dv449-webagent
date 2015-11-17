<?php

namespace controller;

use view\ScrapeView;

class ScrapeController {

    private $scraper;
    private $view;

    public function __construct(\view\FormView $view) {
        $this->view = $view;
    }

    public function doControl() {
        if ($this->view->userPressedScrape()) {
           //TODO: Move code here, now outside to not push button everytime
        }

        $url = $this->view->getUrl();
        $this->scraper = new \model\Scraper($url);
        $this->scraper->scrape();
        $this->view = new ScrapeView($this->scraper->getMovieSuggestions());

    }

    public function getView() {
        return $this->view;
    }

}