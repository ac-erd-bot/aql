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

/**
 * Host-Group-Map Model
 */
class HostGroupMapModel extends ModelBase
{
    private $_hostGroupId ;
    private $_hostId ;
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
        return  ((! Tools::isNullOrEmptyString(Tools::param('hostGroupId')))
               && (Tools::isNumeric(Tools::param('hostGroupId')))
               && (! Tools::isNullOrEmptyString(Tools::param('hostId')))
               && (Tools::isNumeric(Tools::param('hostIdd')))
               && (
                   (
                   Tools::isNullOrEmptyString('lastAudited')
                    || $this->validateDate(Tools::param('lastAudited'))
                    || $this->validateTimestamp(Tools::param('lastAudited'))
                     )
                  )
                ) ;
    }

    /**
     * Validate model for update
     *
     * @return boolean
     */
    public function validateForUpdate()
    {
        return  $this->validateForAdd() ;
    }

    /**
     * Populate model from expected form data.
     */
    public function populateFromForm()
    {
        $this->setHostGroupId(Tools::param('hostGroupId')) ;
        $this->setHostId(Tools::param('hostId')) ;
        $this->setCreated(Tools::param('created')) ;
        $this->setUpdated(Tools::param('updated')) ;
        $this->setLastAudited(Tools::param('lastAudited')) ;
    }

    /**
     * @return integer
     */
    public function getHostGroupId()
    {
        return $this->_hostGroupId ;
    }

    /**
     * @param integer $hostGroupId
     */
    public function setHostGroupId($hostGroupId)
    {
        $this->_hostGroupId = $hostGroupId ;
    }

    /**
     * @return integer
     */
    public function getHostId()
    {
        return $this->_hostId ;
    }

    /**
     * @param integer $hostId
     */
    public function setHostId($hostId)
    {
        $this->_hostId = $hostId ;
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
     * @return stringupdated
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
