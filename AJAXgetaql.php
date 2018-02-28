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

require_once( 'Libs/autoload.php' ) ;

header( 'Content-type: application/json' ) ;
header( 'Access-Control-Allow-Origin: *' ) ;

try {
    $hostname      = Tools::param( 'hostname' ) ;
    $alertCritSecs = Tools::param( 'alertCritSecs' ) ;
    $alertWarnSecs = Tools::param( 'alertWarnSecs' ) ;
    $alertInfoSecs = Tools::param( 'alertInfoSecs' ) ;
    $alertLowSecs  = Tools::param( 'alertLowSecs' ) ;
    $dbc           = new DBConnection( 'process', $hostname ) ;
    $dbh           = $dbc->getConnection() ;
    $processQuery  = <<<SQL
SELECT id
     , user
     , host
     , db
     , command
     , time
     , state
     , info
  FROM INFORMATION_SCHEMA.PROCESSLIST
 WHERE command NOT IN ( 'Sleep', 'Daemon' )
--   AND id <> connection_id()
 ORDER BY time DESC
 
SQL;
    $outputList = [] ;
    $result = $dbh->query( $processQuery ) ;
    while ( $row = $result->fetch_row() ) {
        $time = $row[ 5 ] ;
        switch (true) {
            case $time >= $alertCritSecs: $level = 4 ; break ;
            case $time >= $alertWarnSecs: $level = 3 ; break ;
            case $time >= $alertInfoSecs: $level = 2 ; break ;
            case $time <= $alertLowSecs : $level = 0 ; break ;
            default                       : $level = 1 ;
        }
        $outputItem = [] ;
        $outputItem[ 'level'   ] = $level ;
        $outputItem[ 'time'    ] = $time ;
        $outputItem[ 'server'  ] = $hostname ;
        $outputItem[ 'id'      ] = $row[ 0 ] ;
        $outputItem[ 'user'    ] = $row[ 1 ] ;
        $outputItem[ 'host'    ] = $row[ 2 ] ;
        $outputItem[ 'db'      ] = $row[ 3 ] ;
        $outputItem[ 'command' ] = $row[ 4 ] ;
        $outputItem[ 'state'   ] = $row[ 6 ] ;
        $outputItem[ 'info'    ] = Tools::makeQuotedStringPIISafe( $row[ 7 ] ) ;
        $outputItem[ 'actions' ] = "<button type=\"button\">Kill Thread</button> <button type=\"button\">File Issue</button>" ;
        $outputList[] = $outputItem ;
    }
}
catch ( Exception $e ) {
    echo json_encode( array( 'hostname' => $hostname, 'error_output' => $e->getMessage() ) ) ;
    exit( 1 ) ;
}
echo json_encode( array( 'hostname' => $hostname, 'result' => $outputList ) ) . "\n" ;
