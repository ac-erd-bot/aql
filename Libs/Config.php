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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 */

namespace com\kbcmdba\aql ;

/**
 * Configuration for this tool set
 */
class Config
{

    /**
     * Configuration Class
     *
     * Note: There are no setters for this class. All the configuration comes from
     * config.xml (described in config_sample.xml).
     *
     * Usage Examples:
     *
     * Constructor:
     * $config = new Config() ;
     *
     * Getting configuration data:
     * $dbh = mysql_connect( $config->getDbHost() . ':' . $config->getDbPort()
     * , $config->getDbUser()
     * , $config->getDbPass()
     * ) ;
     * $dbh->mysql_select_db( $config->getDbName() ) ;
     */
    
    /**
     * #@+
     *
     * @var string
     */
    private $_baseUrl = null;

    private $_dbHost = null;

    private $_dbPort = null;

    private $_dbUser = null;

    private $_dbPass = null;

    private $_dbName = null;

    private $_timeZone = null;

    /**
     * #@-
     */
    
    /**
     * #@+
     *
     * @var int
     */
    private $_minRefresh = null;

    private $_defaultRefresh = null;

    /**
     * #@-
     */
    
    /**
     * Class Constructor
     *
     * @param string $dbHost
     * @param string $dbPort
     * @param string $dbName
     * @param string $dbUser
     * @param string $dbPass
     * @throws \Exception
     * @SuppressWarnings indentation
     */
    public function __construct($dbHost = null, $dbPort = null, $dbName = null, $dbUser = null, $dbPass = null)
    {
        if (! is_readable('config.xml')) {
            throw new \Exception("Unable to load configuration from config.xml!");
        }
        $xml = simplexml_load_file('config.xml');
        if (! $xml) {
            throw new \Exception("Invalid syntax in config.xml!");
        }
        $errors = "";
        $cfgValues = [
            'minRefresh' => 15,
            'defaultRefresh' => 60
        ];
        $paramList = [
            'dbHost' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'dbPass' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'dbName' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'dbPort' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'dbUser' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'baseUrl' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'timeZone' => [
                'isRequired' => 1,
                'value' => 0
            ],
            'minRefresh' => [
                'isRequired' => 0,
                'value' => 0
            ],
            'defaultRefresh' => [
                'isRequired' => 0,
                'value' => 0
            ]
        ];
        // verify that all the parameters are present and just once.
        foreach ($xml as $v) {
            $key = (string) $v['name'];
            if ((! isset($paramList[$key])) || ($paramList[$key]['value'] != 0)) {
                $errors .= "Unset or multiply set name: " . $key . "\n";
            } else {
                $paramList[$key]['value'] ++;
                switch ($key) {
                    case 'minRefresh':
                    case 'defaultRefresh':
                        $cfgValues[$key] = (int) $v;
                        break;
                    default:
                        $cfgValues[$key] = (string) $v;
                }
            }
        }
        foreach ($paramList as $key => $x) {
            if ((1 === $x['isRequired']) && (0 === $x['value'])) {
                $errors .= "Missing parameter: " . $key . "\n";
            }
        }
        if ($errors !== '') {
            throw new \Exception("\nConfiguration problem!\n\n" . $errors . "\n");
        }
        $this->_baseUrl = $cfgValues['baseUrl'];
        $this->_dbHost = (! isset($dbHost)) ? $cfgValues['dbHost'] : $dbHost;
        $this->_dbPort = (! isset($dbPort)) ? $cfgValues['dbPort'] : $dbPort;
        $this->_dbName = (! isset($dbName)) ? $cfgValues['dbName'] : $dbName;
        $this->_dbUser = (! isset($dbUser)) ? $cfgValues['dbUser'] : $dbUser;
        $this->_dbPass = (! isset($dbPass)) ? $cfgValues['dbPass'] : $dbPass;
        $this->_minRefresh = $cfgValues['minRefresh'];
        $this->_defaultRefresh = $cfgValues['defaultRefresh'];
        $this->_timeZone = $cfgValues['timeZone'];
        ini_set('date.timezone', $this->_timeZone);
    }

    /**
     * Another magic method...
     *
     * @return string
     */
    public function __toString()
    {
        return "Config::__toString not implemented for security reasons.";
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->_baseUrl;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getDbHost()
    {
        return $this->_dbHost;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getDbPort()
    {
        return $this->_dbPort;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getDbUser()
    {
        return $this->_dbUser;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getDbPass()
    {
        return $this->_dbPass;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getDbName()
    {
        return $this->_dbName;
    }

    /**
     * Getter
     *
     * @return string
     */
    public function getTimeZone()
    {
        return $this->_timeZone;
    }

    /**
     * Getter
     *
     * @return int
     */
    public function getMinRefresh()
    {
        return $this->_minRefresh;
    }

    /**
     * Getter
     *
     * @return int
     */
    public function getDefaultRefresh()
    {
        return $this->_defaultRefresh;
    }

    /**
     * Return the DSN for this connection
     *
     * @param $dbType
     * @return string
     * @SuppressWarnings indentation
     */
    public function getDsn($dbType = 'mysql')
    {
        $this->_dsn = $dbType . ':host=' . $oConfig->getDbHost() . ':' . $oConfig->getDbPort() . ';dbname=' . $oConfig->getDbName();
        return ($this->_dsn);
    }
}
