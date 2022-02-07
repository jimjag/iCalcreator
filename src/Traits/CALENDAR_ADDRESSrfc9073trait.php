<?php
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
declare( strict_types = 1 );
namespace Kigkonsult\Icalcreator\Traits;

use Kigkonsult\Icalcreator\Util\StringFactory;
use Kigkonsult\Icalcreator\Util\Util;
use Kigkonsult\Icalcreator\Util\ParameterFactory;
use Kigkonsult\Icalcreator\Util\CalAddressFactory;
use InvalidArgumentException;

/**
 * CALENDAR-ADDRESS property functions
 *
 * @since  2.41.4 - 2022-01-15
 */
trait CALENDAR_ADDRESSrfc9073trait
{
    /**
     * @var null|mixed[] component property CALENDAR-ADDRESS value
     */
    protected ? array $calendaraddress = null;

    /**
     * Return formatted output for calendar component property calendaraddress
     *
     * @return string
     */
    public function createCalendaraddress() : string
    {
        if( empty( $this->calendaraddress )) {
            return Util::$SP0;
        }
        if( empty( $this->calendaraddress[Util::$LCvalue] )) {
            return $this->getConfig( self::ALLOWEMPTY )
                ? StringFactory::createElement( self::CALENDAR_ADDRESS )
                : Util::$SP0;
        }
        return StringFactory::createElement(
            self::CALENDAR_ADDRESS,
            ParameterFactory::createParams( $this->calendaraddress[Util::$LCparams] ),
            $this->calendaraddress[Util::$LCvalue]
        );
    }

    /**
     * Delete calendar component property calendaraddress
     *
     * @return bool
     */
    public function deleteCalendaraddress() : bool
    {
        $this->calendaraddress = null;
        return true;
    }

    /**
     * Get calendar component property calendaraddress
     *
     * @param null|bool   $inclParam
     * @return bool|string|mixed[]
     */
    public function getCalendaraddress( ? bool $inclParam = false ) : array | bool | string
    {
        if( empty( $this->calendaraddress )) {
            return false;
        }
        return $inclParam ? $this->calendaraddress : $this->calendaraddress[Util::$LCvalue];
    }

    /**
     * Set calendar component property calendaraddress
     *
     * @param null|string   $value
     * @param null|mixed[]  $params
     * @return static
     * @throws InvalidArgumentException
     */
    public function setCalendaraddress( ? string $value = null, ? array $params = [] ) : static
    {
        if( empty( $value )) {
            $this->assertEmptyValue( $value, self::CALENDAR_ADDRESS );
            $value  = Util::$SP0;
            $params = [];
        }
        $value = CalAddressFactory::conformCalAddress( $value, true );
        if( ! empty( $value )) {
            CalAddressFactory::assertCalAddress( $value );
        }
        $this->calendaraddress = [
            Util::$LCvalue  => $value,
            Util::$LCparams => ParameterFactory::setParams( $params ),
        ];
        return $this;
    }
}
