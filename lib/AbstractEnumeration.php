<?php

/**
 * RQuadling/Enumeration
 *
 * LICENSE
 *
 * This is free and unencumbered software released into the public domain.
 *
 * Anyone is free to copy, modify, publish, use, compile, sell, or distribute this software, either in source code form or
 * as a compiled binary, for any purpose, commercial or non-commercial, and by any means.
 *
 * In jurisdictions that recognize copyright laws, the author or authors of this software dedicate any and all copyright
 * interest in the software to the public domain. We make this dedication for the benefit of the public at large and to the
 * detriment of our heirs and successors. We intend this dedication to be an overt act of relinquishment in perpetuity of
 * all present and future rights to this software under copyright law.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT
 * OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * For more information, please refer to <https://unlicense.org>
 *
 */

namespace RQuadling\Enumeration;

use Eloquent\Enumeration\AbstractValueMultiton;
use Eloquent\Enumeration\Exception\UndefinedMemberExceptionInterface;
use JsonSerializable;

/**
 * Improved version of AbstractEnumeration class which provides values() method.
 */
abstract class AbstractEnumeration extends \Eloquent\Enumeration\AbstractEnumeration implements JsonSerializable
{
    /**
     * Get the values of all the members.
     *
     * @return mixed[]
     */
    public static function values(): array
    {
        return \array_map(
            function (AbstractEnumeration $element) {
                return $element->value();
            },
            self::members()
        );
    }

    /**
     * Get the keys of all the members.
     *
     * @return string[]
     */
    public static function keys(): array
    {
        return \array_keys(self::members());
    }

    /**
     * Get the friendly keys of all the members.
     *
     * @return string[]
     */
    public static function friendlyKeys(): array
    {
        return \array_values(
            \array_map(
                function (AbstractEnumeration $element) {
                    return $element->friendlyKey();
                },
                self::members()
            )
        );
    }

    public function friendlyKey(): string
    {
        return \ucwords(\str_replace('_', ' ', \strtolower($this->key())));
    }

    /**
     * Convert a dot-separated version of the key (converts double-underscores to dots) to an enum instance
     * eg. name_spaced.action_name -> NAME_SPACED__ACTION_NAME().
     *
     * @return static
     */
    public static function memberByNamespacedKey(string $namespacedKey)
    {
        $key = \str_replace('.', '__', \strtoupper($namespacedKey));

        return static::memberByKey($key);
    }

    /**
     * Get a dot-separated version of the key (converts double-underscores to dots).
     * eg. NAME_SPACED__ACTION_NAME -> name_spaced.action_name.
     */
    public function namespacedKey(): string
    {
        return \str_replace('__', '.', \strtolower($this->key()));
    }

    /**
     * @return static[]
     */
    public static function membersByValues(array $values): array
    {
        return \array_filter(
            self::members(),
            function (AbstractValueMultiton $element) use ($values) {
                return \in_array($element->value(), $values);
            }
        );
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->value();
    }

    /**
     * @param mixed $value
     *
     * @throws UndefinedMemberExceptionInterface
     */
    public static function keyByValue($value): string
    {
        return static::memberByValue($value)->key();
    }

    public static function friendlyKeyByValue($value): string
    {
        return static::memberByValue($value)->friendlyKey();
    }

    /**
     * @return string[]
     */
    public static function keysByValues(array $values): array
    {
        return \array_keys(static::membersByValues($values));
    }

    /**
     * @return string[]
     */
    public static function friendlyKeysByValues(array $values): array
    {
        return \array_values(
            \array_map(
                function ($element) {
                    return $element->friendlyKey();
                },
                static::membersByValues($values)
            )
        );
    }
}
