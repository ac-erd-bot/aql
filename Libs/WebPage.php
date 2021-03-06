<?php

/*
 *
 * aql - Active Query Listing
 *
 * Copyright (C) 2018 Kevin Benton - kbcmdba [at] gmail [dot] com
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace com\kbcmdba\aql ;

/**
 * Web Page
 */
class WebPage
{
    private $_pageTitle ;
    private $_mimeType ;
    private $_meta ;
    private $_head ;
    private $_styles ;
    private $_top ;
    private $_body ;
    private $_bottom ;
    private $_data ;

    /**
     * Class constructor
     *
     * @param string Title
     */
    public function __construct($title = '')
    {
        $this->setPageTitle($title) ;
        $this->setMimeType('text/html') ;
        $this->setMeta( [ 'Access-Control-Allow-Origin: *'
                        , "Expires: Wed, 21 Feb 2018 00:00:00 GMT"
                        , 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0'
                        , 'Cache-Control: post-check=0, pre-check=0', false
                        , 'Pragma: no-cache'
                        ] ) ;
        $this->setHead(
            <<<HTML
  <link rel="stylesheet" href="css/main.css" />
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/latest/themes/smoothness/jquery-ui.css" /> -->
  <!-- <script src="//code.jquery.com/ui/latest/jquery-ui.js"></script> -->
  <script type="text/javascript" src="https://code.jquery.com/jquery-latest.js"></script>
  <script src="js/common.js"></script>

HTML
                      ) ;
        $this->setStyles('') ;
        $this->setTop('') ;
        $this->setBody('') ;
        $this->setBottom('') ;
        $this->setData('') ;
    }

    /**
     * What will the web page look like if it's rendered now?
     *
     * @return string
     */
    public function __toString()
    {
        if ('text/html' === $this->getMimeType()) {
            //@formatter:off
            return ("<html>\n"
                   . "<head>\n"
                   . "<!-- StartOfPage -->\n"
                   . '  <title>' . $this->getPageTitle() . "</title>\n"
                   . $this->getHead()
                   . $this->getStyles()
                   . "</head>\n"
                   . "<body>\n"
                   . $this->getTop()
                   . $this->getBody()
                   . $this->getBottom()
                   . "\n<!-- EndOfPage --></body>\n"
                   . "</html>\n"
                   ) ;
        // @formatter:on
        } else {
            return ($this->getData()) ;
        }
    }

    /**
     *
     * Get the full contents of the page.
     *
     * @return void
     * @SuppressWarnings indentation
     */
    public function displayPage()
    {
        header('Content-type: ' . $this->getMimeType()) ;
        foreach ($this->getMeta() as $meta) {
            header($meta) ;
        }
        echo $this->__toString() ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setPageTitle($pageTitle)
    {
        $this->_pageTitle = $pageTitle ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getPageTitle()
    {
        return $this->_pageTitle ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setMimeType($mimeType)
    {
        $this->_mimeType = $mimeType ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->_mimeType ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setHead($head)
    {
        $this->_head = $head ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getHead()
    {
        return $this->_head ;
    }

    /**
     *
     * Append array of meta strings with another string
     *
     * @param String $metaString
     * @throws WebPageException
     */
    public function appendMeta($metaString)
    {
        if (is_string($metaString)) {
            array_push($this->_meta, $metaString) ;
        } else {
            throw ( new WebPageException("Improper usage of appendMeta") ) ;
        }
    }

    /**
     * Setter
     *
     * @param mixed $meta Array of values to pass to header()
     * @throws WebPageException
     */
    public function setMeta($meta)
    {
        if (is_array($meta)) {
            $this->_meta = $meta ;
        } else {
            throw ( new WebPageException("setMeta requires an array") ) ;
        }
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getMeta()
    {
        return $this->_meta ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setStyles($styles)
    {
        $this->_styles = $styles ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getStyles()
    {
        return $this->_styles ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setTop($top)
    {
        $this->_top = $top ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getTop()
    {
        return $this->_top ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setBody($body)
    {
        $this->_body = $body ;
    }

    /**
     * Append the body string
     *
     * @param string
     */
    public function appendBody($body)
    {
        $this->_body .= $body ;
    }
    
    /**
     * Getter
     *
     * @return string
     */
    public function getBody()
    {
        return $this->_body ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setBottom($bottom)
    {
        $this->_bottom = $bottom ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getBottom()
    {
        return $this->_bottom ;
    }

    /**
     * Setter
     *
     * @param string
     */
    public function setData($data)
    {
        $this->_data = $data ;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getData()
    {
        return $this->_data ;
    }
}
