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

/**
 * Since parameters directly map to query options...
 *
 * @param string $optionName
 * @param string $columnName
 * @param string $default Value of 'any' tells this routine not to do anything in the event that the user doesn't supply a value.
 * @param string $limits
 */
function processParam( $optionName, $columnName, $default, &$limits ) {
    switch ( Tools::param( $optionName ) ) {
        case 'any':
            // Don't do anything here. This is a special case when it's much
            // easier to not specify the column at all.
            break ;
        case '1':
            $limits .= " AND $columnName = 1" ;
            break ;
        case '0':
            $limits .= " AND $columnName = 0" ;
            break ;
        default :
            if ( 'any' !== $default ) {
                $limits .= " AND $columnName = $default" ;
            }
            break ;
    }
}

/**
 * Process the query list for a given host
 *
 * @param  mixed   $js
 * @param  string  $hostname
 * @param  string  $baseUrl
 * @param  integer $alertCritSecs
 * @param  integer $alertWarnSecs
 * @param  integer $alertInfoSecs
 * @param  integer $alertLowSecs
 */
function processHost( &$js
                    , $hostname
                    , $baseUrl
                    , $alertCritSecs
                    , $alertWarnSecs
                    , $alertInfoSecs
                    , $alertLowSecs
                    ) {
    $prefix   = ( 0 !== $js[ 'Blocks' ] ) ? ',' : '' ;
    $blockNum = $js[ 'Blocks' ] ;
    $js[ 'Blocks' ] ++ ;
    $js[ 'WhenBlock'      ] .= "$prefix\$.getJSON( \"$baseUrl?hostname=$hostname"
                            .  "&alertCritSecs=$alertCritSecs"
                            .  "&alertWarnSecs=$alertWarnSecs"
                            .  "&alertInfoSecs=$alertInfoSecs"
                            .  "&alertLowSecs=$alertLowSecs\")"
                            ;
    $js[ 'ThenParamBlock' ] .= "$prefix res$blockNum" ;
    $js[ 'ThenCodeBlock'  ] .= "\$.each(res$blockNum, myCallback);" ;
}

/**
 * Get the radio choices users are given.
 *
 * @param string $label
 * @param string $name
 * @param string $defaultValue
 * @return string
 */
function getChoices( $label, $name, $defaultValue ) {
    $checkedValue = Tools::param( $name ) ;
    if ( ! ( isset( $checkedValue ) && ( $checkedValue !== '' ) ) ) {
        $checkedValue = $defaultValue ;
    }
    $yesChecked = ( '1' === $checkedValue ) ? 'checked="checked"' : '' ;
    $noChecked  = ( '0' === $checkedValue ) ? 'checked="checked"' : '' ;
    $anyChecked = ( 'any' === $checkedValue ) ? 'checked="checked"' : '' ;
    $yes = "<input type=\"radio\" name=\"$name\" value=\"1\" $yesChecked />" ;
    $no = "<input type=\"radio\" name=\"$name\" value=\"0\" $noChecked />" ;
    $any = "<input type=\"radio\" name=\"$name\" value=\"any\" $anyChecked />" ;
    return( "<tr><td>$label</td><td>$yes</td><td>$no</td><td>$any</td></tr>" ) ;
}

$headerFooterRow = <<<HTML
<tr>
      <th>Server</th>
      <th>Alert<br />Level</th>
      <th>ID</th>
      <th>User</th>
      <th>Host</th>
      <th>DB</th>
      <th>Command</th>
      <th>Time</th>
      <th>State</th>
      <th>Info</th>
    </tr>

HTML;
$page = new WebPage( 'Active Queries List' ) ;
$reloadSeconds = 60 ;
$limits = '' ;
processParam( 'decommissioned'  , 'decommissioned'   , '0'  , $limits ) ;
processParam( 'revenueImpacting', 'revenue_impacting', '1'  , $limits ) ;
processParam( 'shouldMonitor'   , 'should_monitor'   , '1'  , $limits ) ;
processParam( 'shouldBackup'    , 'should_backup'    , 'any', $limits ) ;
$choices = getChoices( 'Monitored Hosts'        , 'shouldMonitor'   , '1'   )
         . getChoices( 'Revenue Impacting Hosts', 'revenueImpacting', '1'   )
         . getChoices( 'Backed-Up Hosts'        , 'shouldBackup'    , 'any' )
         . getChoices( 'Decommissioned Hosts'   , 'decommissioned'  , '0'   )
         ;
try {
    $js[ 'Blocks'         ] = 0 ;
    $js[ 'WhenBlock'      ] = '' ;
    $js[ 'ThenParamBlock' ] = '' ;
    $js[ 'ThenCodeBlock'  ] = '' ;
    $config                 = new Config() ;
    $dbc                    = new DBConnection() ;
    $dbh                    = $dbc->getConnection() ;
    $hostQuery = <<<SQL
SELECT hostname
     , alert_crit_secs
     , alert_warn_secs
     , alert_info_secs
     , alert_low_secs
  FROM aql_db.host
 WHERE 1 = 1 $limits
 
SQL;
    $hostList = '' ;
    $result = $dbh->query( $hostQuery ) ;
    while ( $row = $result->fetch_row() ) {
        $serverName = htmlentities( $row[ 0 ] ) ;
        $hostList .= "<option value=\"$serverName\">$serverName</option>" ;
        processHost( $js
                   , $row[ 0 ]
                   , $config->getBaseUrl()
                   , $row[ 1 ]
                   , $row[ 2 ]
                   , $row[ 3 ]
                   , $row[ 4 ]
                   ) ;
    }
    $groupList = '<option value="all">All</option>' ;
    $groupQuery = 'SELECT tag FROM aql_db.host_group' ;
    $result = $dbh->query( $groupQuery ) ;
    while ( $row = $result->fetch_row() ) {
        $groupName = $row[ 0 ] ;
        $groupList .= "<option value=\"$groupName\">$groupName</option>" ;
    }
    $whenBlock      = $js[ 'WhenBlock'  ] ;
    $thenParamBlock = $js[ 'ThenParamBlock' ] ;
    $thenCodeBlock  = $js[ 'ThenCodeBlock' ] ;
    $page->setBottom( <<<JS
<script>

function myCallback( i, item ) {
    if ( typeof item[ 'result' ] !== 'undefined' ) {
        var level = item['result'][0]['level'] ;
        var myRow = $("<tr class=\"level" + level + "\"><td>"
                     + item['result'][0]['server']
                     + "</td><td>" + level
                     + "</td><td>" + item['result'][0]['id']
                     + "</td><td>" + item['result'][0]['user']
                     + "</td><td>" + item['result'][0]['host']
                     + "</td><td>" + item['result'][0]['db']
                     + "</td><td>" + item['result'][0]['command']
                     + "</td><td>" + item['result'][0]['time']
                     + "</td><td>" + item['result'][0]['state']
                     + "</td><td>" + item['result'][0]['info']
                     + "</td></tr>") ;
        myRow.appendTo( "#tbodyid" ) ;
    }
    else if ( typeof item[ 'error_output' ] !== 'undefined' ) {
        var myRow = $("<tr class=\"error\"><td>" + item['hostname']
                     + "</td><td colspan=\"9\"><center>" + item['error_output']
                     + "</center></td></tr>") ;
        myRow.prependTo( "#tbodyid" ) ;
    }
}

function loadPage() {
    \$("#tbodyid").html( '<tr id="figment"><td colspan="10"><center>Data loading</center></td></tr>' ) ;
    \$.when($whenBlock).then(
        function ( $thenParamBlock ) {
            $thenCodeBlock
            \$("#figment").remove() ;
        }
    );
}

\$(document).ready( loadPage );
setInterval(loadPage, $reloadSeconds*1000);
</script>

JS
  ) ;
    $page->setBody( <<<HTML
<h1>Active Queries List</h1>
<form method=POST>
  <table border=0 cellspacing="0" cellpadding="2" width="100%">
    <tr>
      <td>
        <table border=0 cellspacing="0" cellpadding="2" id="choices">
          <tr><th>Limit To</th><th>Yes</th><th>No</th><th>Either</th></tr>
$choices
        </table>
      </td>
      <td>
        <table border=0 cellspacing="0" cellpadding="2" id="andor1">
          <tr>
            <td><input type="radio" value="and" $andor1and /> And</td>
          </tr>
          <tr>
            <td><input type="radio" value="and" $andor1or /> Or</td>
          </tr>
        </table>
      </td>
      <td>
        <table border=0 cellspacing="0" cellpadding="2" id="hostlist">
          <tr>
            <th>Hosts</th>
          </tr>
          <tr>
                <td><select size="7" name="hosts" multiple="multiple">$hostList</select></td>
          </tr>
        </table>
      </td>
      <td>
        <table border=0 cellspacing="0" cellpadding="2" id="andor2">
          <tr>
            <td><input type="radio" value="and" $andor2and /> And</td>
          </tr>
          <tr>
            <td><input type="radio" value="and" $andor2or /> Or</td>
          </tr>
        </table>
      </td>
      <td>
        <table border=0 cellspacing="0" cellpadding="2" id="grouplist">
          <tr>
            <th>Groups</th>
          </tr>
          <tr>
                <td><select size="7" name="groups" multiple="multiple">$groupList</select></td>
          </tr>
        </table>
      </td>
      <td>
        <table border=0 cellspacing="0" cellpadding="2" id="grouplist">
          <tr>
            <td>
              Refresh Every
              <input type="text" size="5" width="5" value="$reloadSeconds" />
              Seconds
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr><td colspan="6"><center><input type="submit" value="Redisplay with these choices" /></center></td></tr>
  </table>
</form>
        
<table border=1 cellspacing=0 cellpadding=2 id="dataTable" width="100%">
  <thead>
    $headerFooterRow
  </thead>
  <tbody id="tbodyid"><tr id="figment"><td colspan="10"><center>Data loading</center></td></tr></tbody>
  <tfoot>
    $headerFooterRow
  </tfoot>
</table>
<p />
<table border=1 cellspacing=0 cellpadding=2 id="legend" width="100%">
  <caption>Legend</caption>
  <tr><th>Level</th><th>Description</th></tr>
  <tr class="error" ><td>-</td><td>An error has occurred while communicating with the host described.</td></tr>
  <tr class="level4"><td>4</td><td>The shown query has reached a critical alert level and should be investigated.</td></tr>
  <tr class="level3"><td>3</td><td>The shown query has reached a warning alert level.</td></tr>
  <tr class="level2"><td>2</td><td>The shown query is running longer than expected.</td></tr>
  <tr class="level1"><td>1</td><td>The shown query is running within normal time parameters.</td></tr>
  <tr class="level0"><td>0</td><td>The shown query has run for less time than expected so far.</td></tr>
</table>
        
HTML
      ) ;
}
catch ( DaoException $e ) {
    $page->appendBody( "<pre>Error interacting with the database\n\n"
                  . $e->getMessage() . "\n</pre>\n"
                  ) ;
}
$page->displayPage() ;
