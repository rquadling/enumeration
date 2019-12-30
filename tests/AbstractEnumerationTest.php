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

namespace RQuadlingTests\Enumeration;

use Eloquent\Enumeration\Exception\UndefinedMemberException;
use PHPUnit\Framework\TestCase;
use RQuadling\Enumeration\AbstractEnumeration;
use RQuadlingTests\Enumeration\Fixtures\DuplicateValues;
use RQuadlingTests\Enumeration\Fixtures\NamespacedKeys;
use RQuadlingTests\Enumeration\Fixtures\NoDuplicateValues;

class AbstractEnumerationTest extends TestCase
{
    /**
     * @dataProvider provideForMembersByValues
     */
    public function testMembersByValues(string $enumClass, array $values, array $expectedKeys)
    {
        $this->assertEquals($expectedKeys, $enumClass::membersByValues($values));
    }

    public function provideForMembersByValues()
    {
        return [
            'NoDuplicateValues EmptyRequest' => [NoDuplicateValues::class, [], []],
            'NoDuplicateValues SingleRequest' => [
                NoDuplicateValues::class,
                [1],
                [
                    'ONE' => NoDuplicateValues::ONE(),
                ],
            ],
            'NoDuplicateValues MultipleRequests' => [
                NoDuplicateValues::class,
                [1, 2, 3],
                [
                    'ONE' => NoDuplicateValues::ONE(),
                    'TWO' => NoDuplicateValues::TWO(),
                    'THREE' => NoDuplicateValues::THREE(),
                ],
            ],
            'DuplicateValues EmptyRequest' => [DuplicateValues::class, [], []],
            'DuplicateValues SingleRequest' => [
                DuplicateValues::class,
                [1],
                [
                    'ONE' => DuplicateValues::ONE(),
                    'FIRST' => DuplicateValues::FIRST(),
                    'REFERENCED_ONE' => DuplicateValues::REFERENCED_ONE(),
                    'REFERENCED_FIRST' => DuplicateValues::REFERENCED_FIRST(),
                ],
            ],
            'DuplicateValues MultipleRequests' => [
                DuplicateValues::class,
                [1, 2, 3],
                [
                    'ONE' => DuplicateValues::ONE(),
                    'FIRST' => DuplicateValues::FIRST(),
                    'TWO' => DuplicateValues::TWO(),
                    'SECOND' => DuplicateValues::SECOND(),
                    'THREE' => DuplicateValues::THREE(),
                    'THIRD' => DuplicateValues::THIRD(),
                    'REFERENCED_ONE' => DuplicateValues::REFERENCED_ONE(),
                    'REFERENCED_FIRST' => DuplicateValues::REFERENCED_FIRST(),
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideNamespacedEvents
     */
    public function testNamespacedKey(AbstractEnumeration $enum, string $expectedKey)
    {
        $this->assertEquals($expectedKey, $enum->namespacedKey());
    }

    public function provideNamespacedEvents()
    {
        return [
            [NamespacedKeys::SIMPLE(), 'simple'],
            [NamespacedKeys::WITH_UNDERSCORE(), 'with_underscore'],
            [NamespacedKeys::NAMESPACED__ACTION(), 'namespaced.action'],
            [NamespacedKeys::NAME_SPACED__WITH_UNDERSCORES(), 'name_spaced.with_underscores'],
        ];
    }

    /**
     * @dataProvider provideNamespacedKeys
     */
    public function testMemberByNamespacedKey(AbstractEnumeration $enum, string $namespacedKey)
    {
        $member = NamespacedKeys::memberByNamespacedKey($namespacedKey);

        $this->assertInstanceOf(NamespacedKeys::class, $member);
        $this->assertEquals($member->key(), $enum->key());
    }

    public function provideNamespacedKeys()
    {
        return [
            [NamespacedKeys::SIMPLE(), 'simple'],
            [NamespacedKeys::WITH_UNDERSCORE(), 'with_underscore'],
            [NamespacedKeys::NAMESPACED__ACTION(), 'namespaced.action'],
            [NamespacedKeys::NAME_SPACED__WITH_UNDERSCORES(), 'name_spaced.with_underscores'],
        ];
    }

    public function testValues()
    {
        $this->assertEquals(
            [
                'ONE' => 1,
                'TWO' => 2,
                'THREE' => 3,
                'FOUR' => 4,
                'FIVE' => 5,
            ],
            NoDuplicateValues::values()
        );
    }

    public function testKeys()
    {
        $this->assertEquals(
            [
                'ONE',
                'TWO',
                'THREE',
                'FOUR',
                'FIVE',
            ],
            NoDuplicateValues::keys()
        );
    }

    public function testFriendlyKeys()
    {
        $this->assertEquals(
            [
                'Simple',
                'With Underscore',
                'Namespaced  Action',
                'Name Spaced  With Underscores',
            ],
            NamespacedKeys::friendlyKeys()
        );
    }

    /**
     * @dataProvider provideJsonSerialization
     */
    public function testJsonSerialization(array $members, string $expectedSerialization)
    {
        $this->assertEquals($expectedSerialization, \json_encode($members));
    }

    public function provideJsonSerialization()
    {
        return
            [
                'DuplicateValues' => [
                    DuplicateValues::members(),
                    '{"ONE":1,"FIRST":1,"TWO":2,"SECOND":2,"THREE":3,"THIRD":3,"FOUR":4,"FOURTH":4,"FIVE":5,"FIFTH":5,"MINUS_ONE":-1,"MINUS_FIRST":-1,"REFERENCED_ONE":1,"REFERENCED_FIRST":1,"ARRAY_ONE":[1],"ARRAY_FIRST":[1],"CLASS_ONE":"RQuadlingTests\\\\Enumeration\\\\Fixtures\\\\DuplicateValues","CLASS_FIRST":"RQuadlingTests\\\\Enumeration\\\\Fixtures\\\\DuplicateValues","CONSTANT_ONE":4294967295,"CONSTANT_FIRST":4294967295}',
                ],
                'NamespacedKeys' => [
                    NamespacedKeys::members(),
                    '{"SIMPLE":1,"WITH_UNDERSCORE":2,"NAMESPACED__ACTION":3,"NAME_SPACED__WITH_UNDERSCORES":4}',
                ],
                'NoDuplicateValues' => [
                    NoDuplicateValues::members(),
                    '{"ONE":1,"TWO":2,"THREE":3,"FOUR":4,"FIVE":5}',
                ],
            ];
    }

    public function testKeyByValuePass()
    {
        $this->assertEquals('SIMPLE', NamespacedKeys::keyByValue(NamespacedKeys::SIMPLE));
    }

    public function testKeyByValueFail()
    {
        $this->expectException(UndefinedMemberException::class);
        $this->expectExceptionMessage("No member with value equal to -1 defined in class 'RQuadlingTests\\\\Enumeration\\\\Fixtures\\\\NamespacedKeys'");

        NamespacedKeys::keyByValue(-1);
    }

    public function testFriendlyKeyByValuePass()
    {
        $this->assertEquals('Name Spaced  With Underscores', NamespacedKeys::friendlyKeyByValue(NamespacedKeys::NAME_SPACED__WITH_UNDERSCORES));
    }

    public function testFriendlyKeyByValueFail()
    {
        $this->expectException(UndefinedMemberException::class);
        $this->expectExceptionMessage("No member with value equal to -1 defined in class 'RQuadlingTests\\\\Enumeration\\\\Fixtures\\\\NamespacedKeys'");

        NamespacedKeys::friendlyKeyByValue(-1);
    }

    /**
     * @dataProvider provideForKeysByValues
     */
    public function testKeysByValues(string $enumClass, array $values, array $expectedKeys)
    {
        $this->assertEquals($expectedKeys, $enumClass::keysByValues($values));
    }

    public function provideForKeysByValues()
    {
        return [
            'NoDuplicateValues EmptyRequest' => [NoDuplicateValues::class, [], []],
            'NoDuplicateValues SingleRequest' => [
                NoDuplicateValues::class,
                [1],
                [
                    'ONE',
                ],
            ],
            'NoDuplicateValues MultipleRequests' => [
                NoDuplicateValues::class,
                [1, 2, 3],
                [
                    'ONE',
                    'TWO',
                    'THREE',
                ],
            ],
            'DuplicateValues EmptyRequest' => [DuplicateValues::class, [], []],
            'DuplicateValues SingleRequest' => [
                DuplicateValues::class,
                [1],
                [
                    'ONE',
                    'FIRST',
                    'REFERENCED_ONE',
                    'REFERENCED_FIRST',
                ],
            ],
            'DuplicateValues MultipleRequests' => [
                DuplicateValues::class,
                [1, 2, 3],
                [
                    'ONE',
                    'FIRST',
                    'TWO',
                    'SECOND',
                    'THREE',
                    'THIRD',
                    'REFERENCED_ONE',
                    'REFERENCED_FIRST',
                ],
            ],
        ];
    }

    /**
     * @dataProvider provideForFriendlyKeysByValues
     */
    public function testFriendlyKeysByValues(string $enumClass, array $values, array $expectedKeys)
    {
        $this->assertEquals($expectedKeys, $enumClass::friendlyKeysByValues($values));
    }

    public function provideForFriendlyKeysByValues()
    {
        return [
            'NoDuplicateValues EmptyRequest' => [NoDuplicateValues::class, [], []],
            'NoDuplicateValues SingleRequest' => [
                NoDuplicateValues::class,
                [1],
                [
                    'One',
                ],
            ],
            'NoDuplicateValues MultipleRequests' => [
                NoDuplicateValues::class,
                [1, 2, 3],
                [
                    'One',
                    'Two',
                    'Three',
                ],
            ],
            'DuplicateValues EmptyRequest' => [DuplicateValues::class, [], []],
            'DuplicateValues SingleRequest' => [
                DuplicateValues::class,
                [1],
                [
                    'One',
                    'First',
                    'Referenced One',
                    'Referenced First',
                ],
            ],
            'DuplicateValues MultipleRequests' => [
                DuplicateValues::class,
                [1, 2, 3],
                [
                    'One',
                    'First',
                    'Two',
                    'Second',
                    'Three',
                    'Third',
                    'Referenced One',
                    'Referenced First',
                ],
            ],
            'Namespaced Keys' => [NamespacedKeys::class, [], []],
            'Namespaced Keys Single Request' => [
                NamespacedKeys::class,
                [1],
                [
                    'Simple',
                ],
            ],
            'Namespaced Keys Multiple Requests' => [
                NamespacedKeys::class,
                [2, 3, 4],
                [
                    'With Underscore',
                    'Namespaced  Action',
                    'Name Spaced  With Underscores',
                ],
            ],
        ];
    }
}
