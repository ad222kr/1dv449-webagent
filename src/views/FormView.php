<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 2015-11-11
 * Time: 13:28
 */

namespace view;


class FormView {
    private static $urlInputId = "ScrapeView::Url";
    private static $submitId = "ScrapeView::Submit";

    private static $urlInputLabel = "Ange URL att skrapa";

    public function getResponse() {
        return "<form action='' method='post'>
                    ". $this->getTextField(self::$urlInputLabel, self::$urlInputId, "text")."
                    <input type='submit' name='".self::$submitId."'>
                  </form>";
    }



    /**
     * @param $title
     * @param $name
     * @param $type
     * @return string
     */
    private function getTextField($title, $name, $type) {
        return "<label for='$name'>$title: </label>
                <input id='$name' type='$type' name='$name' />";
    }



    public function getUrl() {
        if (isset($_POST[self::$urlInputId]))
            return $_POST[self::$urlInputId];

        return "";
    }

    public function userPressedScrape() {
        return isset($_POST[self::$submitId]);
    }
}