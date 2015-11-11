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
            $url = $this->view->getUrl();
            $this->scraper = new \model\Scraper($url);
            $this->scraper->scrape();
            $this->view = new ScrapeView();
        }

    }

    public function getView() {
        return $this->view;
    }

}