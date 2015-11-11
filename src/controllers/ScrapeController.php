<?php

namespace controller;

use view\ScrapeView;

class ScrapeController {

    private $scraper;
    private $view;

    public function __construct(\view\FormView $view, \model\Scraper $scraper) {
        $this->view = $view;
        $this->scraper = $scraper;
    }

    public function doControl() {
        if ($this->view->userPressedScrape()) {
            // Change view to scrapeview
            $this->view = new ScrapeView();
        }

    }

    public function getView() {
        return $this->view;
    }

}