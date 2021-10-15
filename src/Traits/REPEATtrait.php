<?php
/**
 * iCalcreator, the PHP class package managing iCal (rfc2445/rfc5445) calendar information.
 *
 * This file is a part of iCalcreator.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2007-2021 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
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

use function is_numeric;

/**
 * REPEAT property functions
 *
 * @since 2.27.3 2018-12-22
 */
trait REPEATtrait
{
    /**
     * @var null|array component property REPEAT value
     */
    protected ?array $repeat = null;

    /**
     * Return formatted output for calendar component property repeat
     *
     * @return string
     */
    public function createRepeat() : string
    {
        if( empty( $this->repeat )) {
            return Util::$SP0;
        }
        if( ! isset( $this->repeat[Util::$LCvalue] ) ||
            ( empty( $this->repeat[Util::$LCvalue] ) &&
                ! is_numeric( $this->repeat[Util::$LCvalue] ))) {
            return $this->getConfig( self::ALLOWEMPTY )
                ? StringFactory::createElement( self::REPEAT )
                : Util::$SP0;
        }
        return StringFactory::createElement(
            self::REPEAT,
            ParameterFactory::createParams( $this->repeat[Util::$LCparams] ),
            (string) $this->repeat[Util::$LCvalue]
        );
    }

    /**
     * Delete calendar component property repeat
     *
     * @return bool
     * @since  2.27.1 - 2018-12-15
     */
    public function deleteRepeat() : bool
    {
        $this->repeat = null;
        return true;
    }

    /**
     * Get calendar component property repeat
     *
     * @param bool   $inclParam
     * @return bool|int|string|array
     * @since  2.27.1 - 2018-12-13
     */
    public function getRepeat( bool $inclParam = false ) : array | bool | int | string
    {
        if( empty( $this->repeat )) {
            return false;
        }
        if( null === $this->repeat[Util::$LCvalue] ) {
            $this->repeat[Util::$LCvalue] = Util::$SP0;
        }
        return ( $inclParam )
            ? $this->repeat
            : $this->repeat[Util::$LCvalue];
    }

    /**
     * Set calendar component property repeat
     *
     * @param null|int|string $value
     * @param null|string[]      $params
     * @return self
     * @since 2.27.3 2018-12-22
     */
    public function setRepeat( mixed $value = null, ? array $params = [] ) : self
    {
        if(( $value === null ) || ( $value === Util::$SP0 )) {
            $this->assertEmptyValue( $value, self::REPEAT );
            $value  = null;
            $params = [];
        }
        else {
            Util::assertInteger( $value, self::REPEAT );
            $value = (int) $value;
        }
        $this->repeat = [
            Util::$LCvalue  => $value,
            Util::$LCparams => ParameterFactory::setParams( $params ?? [] ),
        ];
        return $this;
    }
}
