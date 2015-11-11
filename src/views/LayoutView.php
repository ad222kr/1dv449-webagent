<?php
namespace view;
/**
 * Class LayoutView
 * Basic layout for a HTML-page
 * @package view
 */
class LayoutView  {
    /**
     * Title of the application
     * @var string
     */
    private static $title = "AppYo";
    /**
     * CSS to be rendered
     *
     * @var array
     */
    private static $css = array(
        "bootstrap" => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css",
        "bootstrap-theme" => "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css",
        "site" => "css/site.css"
    );

    /**
     * Returns a string representing the html-code to load css-files
     *
     * @return string
     */
    private function loadCss() {
        $ret = "";
        foreach (self::$css as $styleSheet) {
            $ret .= '<link rel="stylesheet" href="' . $styleSheet . '">';
        }
        return $ret;
    }
    /**
     * @param $html - Html to be rendered (response of the view-classes)
     */
    public function render($html) {
        echo '<!DOCTYPE html>
            <html>
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1">
                    <title>' . self::$title . '</title>
                    ' . $this->loadCss() .'
                </head>
                <body>
                    <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
                      <div class="container">
                        <div class="navbar-header">
                          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                          </button>
                          <a class="navbar-brand" href="?">' . self::$title .'</a>
                        </div>
                        <div id="navbar" class="navbar-collapse collapse" >
                        </div>
                      </div>
                    </nav>
                    <div class="container">
                        ' . $html .'
                    </div>
                    <footer class="footer">
                        <div class="container">
                            <p class="text-muted">Application created for the course 1DV608 - Web development with php.</p>
                        </div>
                    </footer>
                </body>
            </html>';
    }
}