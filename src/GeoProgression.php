<?php declare(strict_types=1);

namespace Przeslijmi\Sivalidator;

/**
 * Tools for counting geometrical progression.
 */
class GeoProgression
{

    /**
     * Calcs highest number of progression (1st, 2nd, 3rd, etc.).
     *
     * ## Usage example
     * ```
     * // this will return 7 - while 2^7 = 128
     * GeoProgression::highestNumber(129, 2);
     * GeoProgression::highestNumber(129);
     *
     * // this will return 6 - while 2^6 = 64
     * // 2^7 will not fit - maximum fitting number is 64 (2^6) not 128 (2^7)
     * GeoProgression::highestNumber(127);
     * ```
     *
     * @param integer $sum   Sum of progression.
     * @param integer $ratio Opt., 2. Radio of progression.
     *
     * @since  v1.0
     * @return integer
     */
    public static function highestNumber(int $sum, int $ratio = 2) : int
    {

        // Shortcut.
        if ($sum === 0) {
            return 0;
        }

        return (int) floor(log10($sum) / log10($ratio));
    }

    /**
     * Calcs value of highest number of progression (1st, 2nd, 3rd, etc.).
     *
     * ## Usage example
     * ```
     * // this will return 128 - while 2^7 = 128
     * GeoProgression::highestNumberValue(129, 2);
     * GeoProgression::highestNumberValue(129);
     *
     * // this will return 64 - while 2^6 = 64
     * // 2^7 will not fit - maximum fitting number i 64 (2^6) not 128 (2^7)
     * GeoProgression::highestNumberValue(127);
     * ```
     *
     * @param integer $sum   Sum of progression.
     * @param integer $ratio Opt., 2. Radio of progression.
     *
     * @since  v1.0
     * @return integer
     */
    public static function highestNumberValue(int $sum, int $ratio = 2) : int
    {

        // Shortcut.
        if ($sum === 0) {
            return 0;
        }

        return pow($ratio, self::highestNumber($sum, $ratio));
    }

    /**
     * Get numbers of progression that can fit in this progression.
     *
     * ## Usage example
     * ```
     * // this will return [ 1, 128 ]
     * GeoProgression::highestNumberValue(129, 2);
     *
     * // this will return [ 2, 8 ]
     * GeoProgression::highestNumberValue(10, 2);
     * ```
     *
     * @param integer $sum   Sum of progression.
     * @param integer $ratio Opt., 2. Radio of progression.
     *
     * @since  v1.0
     * @return array
     */
    public static function getProgression(int $sum, int $ratio = 2) : array
    {

        // Lvd.
        $result = [];

        // Scan.
        while ($sum > 0) {

            // Count highest component.
            $component = self::highestNumberValue($sum, $ratio);
            $sum      -= $component;

            // Save each component.
            $result[] = $component;
        }

        sort($result);

        return $result;
    }
}
