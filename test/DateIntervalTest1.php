<?php
/** @noinspection PhpUnnecessaryCurlyVarSyntaxInspection */

/**
 * iCalcreator, the PHP class package managing iCal (rfc2445/rfc5445) calendar information.
 *
 * This file is a part of iCalcreator.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2007-2022 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      https://kigkonsult.se
 * @license   Subject matter of licence is the software iCalcreator.
 *            The above copyright, link, package and version notices,
 *            this licence notice and the invariant [rfc5545] PRODID result use
 *            as implemented and invoked in iCalcreator shall be included in
 *            all copies or substantial portions of the iCalcreator.
 *
 *            iCalcreator is free software: you can redistribute it and/or modify
 *            it under the terms of the GNU Lesser General Public License as
 *            published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *            iCalcreator is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU Lesser General Public License for more details.
 *
 *            You should have received a copy of the GNU Lesser General Public License
 *            along with iCalcreator. If not, see <https://www.gnu.org/licenses/>.
 */

namespace Kigkonsult\Icalcreator;

use Exception;
use Kigkonsult\Icalcreator\Util\DateIntervalFactory;
use Kigkonsult\Icalcreator\Util\RecurFactory;
use Kigkonsult\Icalcreator\Util\StringFactory;
use Kigkonsult\Icalcreator\Util\Util;

/**
 * class DateIntervalTest1, testing REFRESH_INTERVAL, DURATION and TRIGGER, input DateInterval and string
 *
 * @since  2.29.05 - 2019-06-20
 */
class DateIntervalTest1 extends DtBase
{
    /**
     * set and restore local timezone from const
     */
    public static ?string $oldTimeZone = null;

    /**
     * @return void
     */
    public static function setUpBeforeClass() : void
    {
        self::$oldTimeZone = date_default_timezone_get();
        date_default_timezone_set( LTZ );
    }

    /**
     * @return void
     */
    public static function tearDownAfterClass() : void
    {
        date_default_timezone_set( self::$oldTimeZone );
    }

    /**
     * DateInterval123Provider Generator
     *
     * @param bool $inclYearMonth
     * @return mixed[]
     * @throws Exception
     * @static
     * @todo replace with DateInterval properties, remove durationArray2string()
     */
    public static function DateIntervalArrayGenerator( bool $inclYearMonth = true) : array
    {
        $base = [
            RecurFactory::$LCYEAR  => array_rand( array_flip( [ 1, 2 ] )),
            RecurFactory::$LCMONTH => array_rand( array_flip( [ 1, 12 ] )),
            RecurFactory::$LCDAY   => array_rand( array_flip( [ 1, 28 ] )),
            RecurFactory::$LCWEEK  => array_rand( array_flip( [ 1, 4 ] )),
            RecurFactory::$LCHOUR  => array_rand( array_flip( [ 1, 23 ] )),
            RecurFactory::$LCMIN   => array_rand( array_flip( [ 1, 59 ] )),
            RecurFactory::$LCSEC   => array_rand( array_flip( [ 1, 59 ] ))
        ];

        do {
            $random = [];
            $cnt = array_rand( array_flip( [ 1, 7 ] ));
            for( $x = 0; $x < $cnt; $x++ ) {
                foreach( array_slice( $base, array_rand( array_flip( [ 1, 7 ] )), 1, true ) as $k => $v ) {
                    $random[$k] = $v;
                }
            }
            if( 1 === array_rand( [ 1 => 1, 2 => 2 ] )) {
                unset( $random[RecurFactory::$LCWEEK] );
                $random = array_filter( $random );
            }
            if( ! $inclYearMonth ) {
                unset( $random[RecurFactory::$LCYEAR], $random[RecurFactory::$LCMONTH] );
                $random = array_filter( $random );
            }
        } while( 1 > count( $random ));
        if( isset( $random[RecurFactory::$LCWEEK] )) {
            $random = [ RecurFactory::$LCWEEK => $random[RecurFactory::$LCWEEK] ];
        }
        $random2 = [];
        foreach( array_keys( $base ) as $key ) {
            if( isset( $random[$key] )) {
                $random2[$key] = $random[$key];
            }
        }
        return $random2;
    }

    /**
     * Return an iCal formatted string from (internal array) duration
     *
     * @param mixed[] $duration , array( year, month, day, week, day, hour, min, sec )
     * @return string
     * @static
     * @since  2.26.14 - 2019-02-12
     */
    public static function durationArray2string( array $duration ) : string
    {
        static $PT0H0M0S = 'PT0H0M0S';
        static $Y = 'Y';
        static $T = 'T';
        static $W = 'W';
        static $D = 'D';
        static $H = 'H';
        static $M = 'M';
        static $S = 'S';
        if( ! isset( $duration[RecurFactory::$LCYEAR] )  &&
            ! isset( $duration[RecurFactory::$LCMONTH] ) &&
            ! isset( $duration[RecurFactory::$LCDAY] )   &&
            ! isset( $duration[RecurFactory::$LCWEEK] )  &&
            ! isset( $duration[RecurFactory::$LCHOUR] )  &&
            ! isset( $duration[RecurFactory::$LCMIN] )   &&
            ! isset( $duration[RecurFactory::$LCSEC] )) {
            return Util::$SP0;
        }
        if( Util::issetAndNotEmpty( $duration, RecurFactory::$LCWEEK )) {
            return DateIntervalFactory::$P . $duration[RecurFactory::$LCWEEK] . $W;
        }
        $result = DateIntervalFactory::$P;
        if( Util::issetAndNotEmpty( $duration, RecurFactory::$LCYEAR )) {
            $result .= $duration[RecurFactory::$LCYEAR] . $Y;
        }
        if( Util::issetAndNotEmpty( $duration, RecurFactory::$LCMONTH )) {
            $result .= $duration[RecurFactory::$LCMONTH] . $M;
        }
        if( Util::issetAndNotEmpty( $duration, RecurFactory::$LCDAY )) {
            $result .= $duration[RecurFactory::$LCDAY] . $D;
        }
        $hourIsSet = ( Util::issetAndNotEmpty( $duration, RecurFactory::$LCHOUR ));
        $minIsSet  = ( Util::issetAndNotEmpty( $duration, RecurFactory::$LCMIN ));
        $secIsSet  = ( Util::issetAndNotEmpty( $duration, RecurFactory::$LCSEC ));
        if( $hourIsSet || $minIsSet || $secIsSet ) {
            $result .= $T;
        }
        if( $hourIsSet ) {
            $result .= $duration[RecurFactory::$LCHOUR] . $H;
        }
        if( $minIsSet ) {
            $result .= $duration[RecurFactory::$LCMIN] . $M;
        }
        if( $secIsSet ) {
            $result .= $duration[RecurFactory::$LCSEC] . $S;
        }
        if( DateIntervalFactory::$P === $result ) {
            $result = $PT0H0M0S;
        }
        return $result;
    }

    /**
     * DateInterval123ProviderDateInterval sub-provider
     *
     * @param mixed[] $input
     * @param int     $cnt
     * @return mixed[]
     * @throws Exception
     */
    public static function DateInterval123ProviderDateInterval( array $input, int $cnt ) : array
    {
        $dateIntervalArray = $input;
        $dateInterval = (array) DateIntervalFactory::factory(
            self::durationArray2string( $dateIntervalArray )
        );
        $getValue = DateIntervalFactory::DateIntervalArr2DateInterval( $dateInterval );
        return [
            1000 + $cnt,
            DateIntervalFactory::DateIntervalArr2DateInterval( $dateInterval ),
            [
                Util::$LCvalue  => $getValue,
                Util::$LCparams => []
            ],
            ':' . DateIntervalFactory::dateInterval2String(
                DateIntervalFactory::conformDateInterval(
                    DateIntervalFactory::DateIntervalArr2DateInterval( $dateInterval )
                )
            )
        ];
    }

    /**
     * DateInterval123Provider DateIntervalString sub-provider
     *
     * @param mixed[] $input
     * @param int     $cnt
     * @return mixed[]
     * @throws Exception
     */
    public static function DateInterval123ProviderDateIntervalString( array $input, int $cnt ) : array
    {
        $dateIntervalArray = $input;
        $getValue          = DateIntervalFactory::factory(
            self::durationArray2string( $dateIntervalArray )
        );
        return [
            3000 + $cnt,
            self::durationArray2string( $dateIntervalArray ),
            [
                Util::$LCvalue  => $getValue,
                Util::$LCparams => [],
            ],
            ':' . DateIntervalFactory::dateInterval2String(
                DateIntervalFactory::conformDateInterval(
                    DateIntervalFactory::factory(
                        self::durationArray2string( $dateIntervalArray )
                    )
                )
            ),
        ];
    }

    /**
     * testDateInterval123 provider
     *
     * @return mixed[]
     * @throws Exception
     */
    public function DateInterval123Provider() : array
    {
        $zeroInput = [
            RecurFactory::$LCHOUR => 0,
            RecurFactory::$LCMIN  => 0,
            RecurFactory::$LCSEC  => 0
        ];

        $dataArr = [];

        // DateInterval zero input
        $cnt = 1;
        $dataArr[] = self::DateInterval123ProviderDateInterval( $zeroInput, $cnt );

        // DateInterval non-zero input
        while( 300 > $cnt ) {
            ++$cnt;
            $dataArr[] = self::DateInterval123ProviderDateInterval( self::DateIntervalArrayGenerator(), $cnt );
        }

        // string zero input
        $cnt = 1;
        $dataArr[] = self::DateInterval123ProviderDateIntervalString( $zeroInput, $cnt );

        // string non-zero input
        while( 300 > $cnt ) {
            ++$cnt;
            $dataArr[] = self::DateInterval123ProviderDateIntervalString( self::DateIntervalArrayGenerator(), $cnt );
        }

        return $dataArr;
    }

    /**
     * Testing DateInterval for REFRESH_INTERVAL, DURATION and TRIGGER, input DateInterval and string
     *
     * @test
     * @dataProvider DateInterval123Provider
     * @param int|string $case
     * @param mixed   $value
     * @param mixed[] $expectedGet
     * @param string  $expectedString
     * @throws Exception
     */
    public function testDateInterval123( int | string $case, mixed $value, array $expectedGet, string $expectedString ) : void
    {
        static $compProp = [
            IcalInterface::VEVENT        => [ IcalInterface::DURATION ],
            IcalInterface::VTODO         => [ IcalInterface::DURATION ],
            IcalInterface::VFREEBUSY     => [ IcalInterface::DURATION ],
            IcalInterface::VALARM        => [ IcalInterface::DURATION, IcalInterface::TRIGGER ],
            IcalInterface::AVAILABLE     => [ IcalInterface::DURATION ],
            IcalInterface::VAVAILABILITY => [ IcalInterface::DURATION ]
        ];
        $c        = new Vcalendar();
        $propName = IcalInterface::REFRESH_INTERVAL;
        $getMethod    = StringFactory::getGetMethodName( $propName );
        $createMethod = StringFactory::getCreateMethodName( $propName );
        $deleteMethod = StringFactory::getDeleteMethodName( $propName );
        $setMethod    = StringFactory::getSetMethodName( $propName );
        $c->{$setMethod}( $value );

        $getValue = $c->{$getMethod}( true );
        $expGet   = $expectedGet;
        $expGet[Util::$LCparams] += [ IcalInterface::VALUE => IcalInterface::DURATION ];
        $this->assertEquals(
            $expGet,
            $getValue,
            "get error in case #{$case}-cal1, Vcalendar::{$getMethod}"
        );
        $this->assertEquals(
            strtoupper( $propName ) . ';VALUE=DURATION' . $expectedString,
            trim( $c->{$createMethod}()),
            "create error in case #{$case}-cal2, Vcalendar::{$createMethod}"
        );
        $c->{$deleteMethod}();
        $this->assertFalse(
            $c->{$getMethod}( true ),
            "get (after delete) error in case #{$case}-cal3, Vcalendar::{$deleteMethod}"
        );
        $c->{$setMethod}( $value ); // test ###

        foreach( $compProp as $theComp => $props ) {
            $newMethod = 'new' . $theComp;
            switch( true ) {
                case ( IcalInterface::AVAILABLE === $theComp ) :
                    $comp   = $c->newVavailability()->{$newMethod}();
                    break;
                case ( IcalInterface::VALARM === $theComp ) :
                    $comp   = $c->newVevent()->{$newMethod}();
                    break;
                default :
                    $comp   = $c->{$newMethod}();
                    break;
            }
            foreach( $props as $propName ) {
                $getMethod    = StringFactory::getGetMethodName( $propName );
                $createMethod = StringFactory::getCreateMethodName( $propName );
                $deleteMethod = StringFactory::getDeleteMethodName( $propName );
                $setMethod    = StringFactory::getSetMethodName( $propName );
                // error_log( __FUNCTION__ . ' #' . $case . ' in ' . var_export( $value, true )); // test ###
                $comp->{$setMethod}( $value );

                $getValue = $comp->{$getMethod}( true );
                // error_log( __FUNCTION__ . ' #' . $case . ' get ' . var_export( $getValue, true )); // test ###
                /*
                if( Vcalendar::TRIGGER == $propName ) {
                    $expectedGet[Util::$LCvalue]['relatedStart'] = true;
                    unset( $expectedGet[Util::$LCvalue]['before'], $getValue[Util::$LCvalue]['before'] );
                }
                */
                $this->assertEquals(
                    $expectedGet,
                    $getValue,
                    "get error in case #{$case}-comp1, <{$theComp}>->{$createMethod}"
                );

                $this->assertEquals(
                    strtoupper( $propName ) . $expectedString,
                    trim( $comp->{$createMethod}()),
                    "create error in case #{$case}-comp2, <{$theComp}>->{$createMethod}"
                );
                $comp->{$deleteMethod}();
                $this->assertFalse(
                    $comp->{$getMethod}( true ),
                    "get (after delete) error in case #{$case}-comp3, <{$theComp}>->{$createMethod}"
                );
                $comp->{$setMethod}( $value ); // test ###
            } // end foreach
            if( IcalInterface::VALARM !== $theComp ) {
                $comp->setDtstart( '20190101T080000 UTC' );
                $this->assertGreaterThanOrEqual(
                    '20190101080000',
                    $comp->getDuration( false, true )->format( 'YmdHis'),
                    "error in case #{$case}-comp5, <{$theComp}>->getDuration()"
                );
            }
        } // end foreach

        $this->parseCalendarTest( $case, $c, $expectedString );
    }
}
