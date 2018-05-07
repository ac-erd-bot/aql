<?php

/**
 * aql
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
 * Host Model
 */
class HostModel extends ModelBase
{
    private $_id ;
    private $_hostName ;
    private $_description ;
    private $_shouldMonitor ;
    private $_shouldBackup ;
    private $_revenueImpacting ;
    private $_decommissioned ;
    private $_alertCritSecs ;
    private $_alertWarnSecs ;
    private $_alertInfoSecs ;
    private $_alertLowSecs ;
    private $_created ;
    private $_updated ;
    private $_lastAudited ;

    /**
     * class constructor
     */
    public function __construct()
    {
        parent::__construct() ;
    }

    /**
     * Validate model for insert
     *
     * @return boolean
     */
    public function validateForAdd()
    {
        return  ((Tools::isNullOrEmptyString(Tools::param('id')))
               && (! Tools::isNullOrEmptyString(Tools::param('hostName')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertCritSecs')))
               && (Tools::isNumeric(Tools::param('alertCritSecs')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertWarnSecs')))
               && (Tools::isNumeric(Tools::param('alertWarnSecs')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertInfoSecs')))
               && (Tools::isNumeric(Tools::param('alertInfoSecs')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertLowSecs')))
               && (Tools::isNumeric(Tools::param('alertLowSecs')))
                ) ;
    }

    /**
     * Validate model for update
     *
     * @return boolean
     */
    public function validateForUpdate()
    {
        return  ((! Tools::isNullOrEmptyString(Tools::param('id')))
               && (Tools::isNumeric(Tools::param('id')))
               && (! Tools::isNullOrEmptyString(Tools::param('hostName')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertCritSecs')))
               && (Tools::isNumeric(Tools::param('alertCritSecs')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertWarnSecs')))
               && (Tools::isNumeric(Tools::param('alertWarnSecs')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertInfoSecs')))
               && (Tools::isNumeric(Tools::param('alertInfoSecs')))
               && (! Tools::isNullOrEmptyString(Tools::param('alertLowSecs')))
               && (Tools::isNumeric(Tools::param('alertLowSecs')))
                ) ;
    }

    
    /**
     * Based on newvalue, set targetValue by reference.
     * Assume result should be true if $newValue is invalid.
     *
     * @param boolean &$targetValue
     * @param boolean $newValue
     */
    private function _setBooleanAssumeTrue(&$targetValue, $newValue)
    {
        if ((! isset($newValue))
           || (false === $newValue)
           || (0 === $newValue)
           || ('0' === $newValue)
            ) {
            $targetValue = 0 ;
        } else {
            $targetValue = 1 ;
        }
    }

    /**
     * Populate model from expected form data.
     */
    public function populateFromForm()
    {
        $this->setHostId(Tools::param('id')) ;
        $this->setHostName(Tools::param('hostName')) ;
        $this->setDescription(Tools::param('description')) ;
        $this->setShouldMonitor(Tools::param('shouldMonitor')) ;
        $this->setShouldBackup(Tools::param('shouldBackup')) ;
        $this->setRevenueImpacting(Tools::param('revenueImpacting')) ;
        $this->setDecommissioned(Tools::param('decommissioned')) ;
        $this->setAlertCritSecs(Tools::param('alertCritSecs')) ;
        $this->setAlertWarnSecs(Tools::param('alertWarnSecs')) ;
        $this->setAlertInfoSecs(Tools::param('alertInfoSecs')) ;
        $this->setAlertLowSecs(Tools::param('alertLowSecs')) ;
        $this->setCreated(Tools::param('created')) ;
        $this->setUpdated(Tools::param('updated')) ;
        $this->setLastAudited(Tools::param('lastAudited')) ;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->_id ;
    }

    /**
     * @param integer $id
     */
    public function setId($id)
    {
        $this->_id = $id ;
    }

    /**
     * @return string
     */
    public function getHostName()
    {
        return $this->_hostName ;
    }

    /**
     * @param string $hostName
     */
    public function setHostName($hostName)
    {
        $this->_hostName = $hostName ;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->_description ;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->_description = $description ;
    }

    /**
     * @return boolean
     */
    public function getShouldMonitor()
    {
        return $this->_shouldMonitor ;
    }

    /**
     * @param string $shouldMonitor
     */
    public function setShouldMonitor($shouldMonitor)
    {
        $this->_setBooleanAssumeTrue($this->_shouldMonitor, $shouldMonitor) ;
    }

    /**
     * @return boolean
     */
    public function getShouldBackup()
    {
        return $this->_shouldBackup ;
    }
    
    /**
     * @param string $shouldBackup
     */
    public function setShouldBackup($shouldBackup)
    {
        $this->_setBooleanAssumeTrue($this->_shouldBackup, $shouldBackup) ;
    }
    
    /**
     * @return boolean
     */
    public function getRevenueImpacting()
    {
        return $this->_revenueImpacting ;
    }
    
    /**
     * @param string $revenueImpacting
     */
    public function setRevenueImpacting($revenueImpacting)
    {
        $this->_setBooleanAssumeTrue($this->_revenueImpacting, $revenueImpacting) ;
    }
    
    /**
     * @return boolean
     */
    public function getDecommissioned()
    {
        return $this->_decommissioned ;
    }
    
    /**
     * @param string $decommissioned
     */
    public function setDecommissioned($decommissioned)
    {
        $this->_setBooleanAssumeTrue($this->_decommissioned, $decommissioned) ;
    }
    
    /**
     * @return int
     */
    public function getAlertCritSecs()
    {
        return $this->_alertCritSecs ;
    }
    
    /**
     * @param int $alertCritSecs
     */
    public function setAlertCritSecs($alertCritSecs)
    {
        $this->_alertCritSecs = $alertCritSecs ;
    }
    
    /**
     * @return int
     */
    public function getAlertWarnSecs()
    {
        return $this->_alertWarnSecs ;
    }

    /**
     * @param int $alertWarnSecs
     */
    public function setAlertWarnSecs($alertWarnSecs)
    {
        $this->_alertWarnSecs = $alertWarnSecs ;
    }

    /**
     * @return int
     */
    public function getAlertInfoSecs()
    {
        return $this->_alertInfoSecs ;
    }

    /**
     * @param int $_alertInfoSecs
     */
    public function setAlertInfoSecs($alertInfoSecs)
    {
        $this->_alertInfoSecs = $alertInfoSecs ;
    }

    /**
     * @return int
     */
    public function getAlertLowSecs()
    {
        return $this->_alertLowSecs ;
    }

    /**
     * @param int $_alertLowSecs
     */
    public function setAlertLowSecs($alertLowSecs)
    {
        $this->_alertLowSecs = $alertLowSecs ;
    }

    /**
     * @return string
     */
    public function getCreated()
    {
        return $this->_created ;
    }

    /**
     * @param string $_created
     */
    public function setCreated($created)
    {
        $this->_created = $created ;
    }

    /**
     * @return string
     */
    public function getUpdated()
    {
        return $this->_updated ;
    }

    /**
     * @param string $_updated
     */
    public function setUpdated($updated)
    {
        $this->_updated = $updated ;
    }

    /**
     * @return string
     */
    public function getLastAudited()
    {
        return $this->_lastAudited ;
    }

    /**
     * @param string $_lastAudited
     */
    public function setLastAudited($lastAudited)
    {
        $this->_lastAudited = $lastAudited ;
    }
}
