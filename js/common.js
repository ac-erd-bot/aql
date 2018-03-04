
/**
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

/**
 * Make sure that the passed value is valid for the proposed condition. If
 * isRequired is true, dateString must not be blank or null as well as being
 * a valid date string. If isRequired is false, dateString may be blank or null,
 * but when it's not, it must be a valid date string. A valid date string looks
 * like YYYY-MM-DD
 *
 * @param dateString {String}
 * @param isRequired {Boolean}
 * @returns {Boolean}
 */
function isDateValid( dateString, isRequired ) {
    var regex = /^\d\d\d\d-\d\d-\d\d$/ ;
    var retVal = true ;

    if ( ! isRequired ) {
        if ( ( null == dateString ) || ( '' == dateString ) ) {
            return true ;
        }
    }
    else {
        retVal = ( ( null !== dateString ) && ( '' !== dateString ) ) ;
    }
    retVal = ( retVal && ( null !== dateString.match( regex ) ) ) ;
    if ( retVal ) {
        var daysInMonths = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ] ;
        var yr = parseInt( dateString.substring( 0, 4 ) ) ;
        var mo = parseInt( dateString.substring( 5, 7 ) ) ;
        var da = parseInt( dateString.substring( 8, 10 ) ) ;
        if ( ( yr % 4 ) && ( ( yr % 400 ) || ! ( yr % 100 ) ) ) {
                daysInMonths[ 1 ]++ ; // Leap day!
        }
        if  ( ( yr < 2000 ) || ( yr > 2038 )
           || ( mo < 1 ) || ( mo > 12 )
           || ( da < 1 ) || ( da > daysInMonths[ mo ] )
            ) {
            retVal = false ;
        }
    }
    return ( retVal ) ;
} 

/**
 * Make sure that the passed value is valid for the proposed condition. If
 * isRequired is true, dateTimeString must not be blank or null as well as being
 * a valid date and time string. If isRequired is false, dateTimeString may be
 * blank or null, but when it's not, it must be a valid date and time string. A
 * valid date and time string looks like 'YYYY-MM-DD hh:mm:ss'
 *
 * @param dateTimeString {String}
 * @param isRequired {Boolean}
 * @returns {Boolean}
 */
function isDateTimeValid( dateTimeString, isRequired ) {
    var regex = /^\d\d\d\d-\d\d-\d\d\s\d\d:\d\d:\d\d$/ ;
    var retVal = true ;
    if ( ! isRequired ) {
        if ( ( null == dateTimeString ) || ( '' == dateTimeString ) ) {
            return true ;
        }
    }
    else {
        retVal = ( ( null !== dateTimeString ) && ( '' !== dateTimeString ) ) ;
    }
    retVal = ( retVal && ( null !== dateTimeString.match( regex ) ) ) ;
    if ( retVal ) {
        var daysInMonths = [ 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31 ] ;
        var yr = parseInt( dateTimeString.substring( 0, 4 ) ) ;
        var mo = parseInt( dateTimeString.substring( 5, 7 ) ) ;
        var da = parseInt( dateTimeString.substring( 8, 10 ) ) ;
        var hr = parseInt( dateTimeString.substring( 11, 13 ) ) ;
        var mi = parseInt( dateTimeString.substring( 14, 16 ) ) ;
        var se = parseInt( dateTimeString.substring( 17, 19 ) ) ;
        if ( ( yr % 4 ) && ( ( yr % 400 ) || ! ( yr % 100 ) ) ) {
            daysInMonths[ 1 ]++ ; // Leap day!
        }
        if  ( ( yr < 2000 ) || ( yr > 2038 )
           || ( mo < 1 ) || ( mo > 12 )
           || ( da < 1 ) || ( da > daysInMonths[ mo ] )
           || ( hr < 0 ) || ( hr > 23 )
           || ( mi < 0 ) || ( mi > 59 )
           || ( se < 0 ) || ( se > 59 )
            ) {
            retVal = false ;
        }
    }
    return ( retVal ) ;
}

function isNumeric( n ) {
    return ! isNaN( parseFloat( n ) ) && isFinite( n ) ;
}

/**
 * Load the results of an AJAX call into the target ID
 *
 * @param uri        URI
 * @param data        Data in URL-encoded format
 * @param targetId    The response will be loaded here.
 * @param isAsync    Load the response asynchronously.
 * @param callback    A user-defined routine to handle the results.
 */
function doLoadAjaxJsonResultWithCallback( uri, data, targetId, isAsync, callback ) {
    var xhttp = new XMLHttpRequest() ;
    xhttp.onreadystatechange = function() {
        if ( xhttp.readyState == 4 && xhttp.status == 200 ) {
            callback( xhttp, targetId ) ;
        }
    } ;
    xhttp.open( "POST", uri, isAsync ) ;
    xhttp.setRequestHeader( "Content-type", "application/x-www-form-urlencoded" ) ;
    xhttp.send( data ) ;
}

///////////////////////////////////////////////////////////////////////////////

/**
 * Dynamically remove a row that was created.
 *
 * @param rowId
 * @returns {Boolean}
 */
function deleteRow( rowId ) {
    var row = document.getElementById( rowId ) ;
    row.parentNode.removeChild( row ) ;
    return false ;
}

///////////////////////////////////////////////////////////////////////////////

function flipFlop() {
    if ( $(this).hasClass( "less" ) ) {
        $(this).removeClass( "less" );
        $(this).html( "more" ) ;
    }
    else {
        $(this).addClass( "less" ) ;
        $(this).html( "less" ) ;
    }
    $(this).parent().prev().toggle() ;
    $(this).prev().toggle() ;
    return false ;
}

///////////////////////////////////////////////////////////////////////////////

function myCallback( i, item ) {
    var showChars = 40 ;
    if ( typeof item[ 'result' ] !== 'undefined' ) {
        var level = item[ 'result' ][0][ 'level' ] ;
        var info  = item[ 'result' ][0][ 'info' ] ;
        if ( info.length > showChars + 8 ) {
            var first = info.substr( 0, showChars ) ;
            var last  = info.substr( showChars - 1, info.length - showChars + 1 ) ;
            info      = first
                      + '<span class="moreelipses">...</span>&nbsp;'
                      + '<span class="morecontent"><span>'
                      + last
                      + '</span>&nbsp;&nbsp;<a href="" class="morelink">'
                      + 'more</a></span>' ;
        }
        var myRow = $("<tr class=\"level" + level + "\"><td>"
                     + item['result'][0]['server']
                     + "</td><td>" + level
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'id'      ]
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'user'    ]
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'host'    ]
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'db'      ]
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'command' ]
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'time'    ]
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'state'   ]
                     + "</td><td class=\"comment more\">" + info
                     + "</td><td>" + item[ 'result' ][ 0 ][ 'actions' ]
                     + "</td></tr>") ;
        myRow.appendTo( "#tbodyid" ) ;
    }
    else if ( typeof item[ 'error_output' ] !== 'undefined' ) {
        var myRow = $("<tr class=\"error\"><td>" + item[ 'hostname' ]
                     + "</td><td colspan=\"10\"><center>" + item[ 'error_output' ]
                     + "</center></td></tr>") ;
        myRow.prependTo( "#tbodyid" ) ;
    }
}

///////////////////////////////////////////////////////////////////////////////
