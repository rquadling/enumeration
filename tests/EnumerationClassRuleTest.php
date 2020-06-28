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

use PHPStan\Testing\RuleTestCase;
use RQuadling\Enumeration\PHPStan\Rules\EnumerationClassRule;

/**
 * @extends RuleTestCase<EnumerationClassRule>
 */
class EnumerationClassRuleTest extends RuleTestCase
{
    protected function getRule(): EnumerationClassRule
    {
        return new EnumerationClassRule();
    }

    public function testDuplicateValueRules(): void
    {
        require_once __DIR__.'/Fixtures/DuplicateValues.php';
        $this->analyse(
            [__DIR__.'/Fixtures/DuplicateValues.php'],
            [
                ['Multiple names exist for the value "1" in DuplicateValues', 53],
                ['Multiple names exist for the value "2" in DuplicateValues', 53],
                ['Multiple names exist for the value "3" in DuplicateValues', 53],
                ['Multiple names exist for the value "4" in DuplicateValues', 53],
                ['Multiple names exist for the value "5" in DuplicateValues', 53],
                ['Multiple names exist for the value "-1" in DuplicateValues', 53],
                ['Multiple names exist for the value "1" in DuplicateValues', 53],
                ['Multiple names exist for the value "1" in DuplicateValues', 53],
                ['Multiple names exist for the value "RQuadlingTests\Enumeration\Fixtures\DuplicateValues::class" in DuplicateValues', 53],
                ['Multiple names exist for the value "4294967295" in DuplicateValues', 53],
            ]
        );
    }

    public function testMissingDocBlockRules(): void
    {
        require_once __DIR__.'/Fixtures/MissingDocblocks.php';
        $this->analyse(
            [__DIR__.'/Fixtures/MissingDocblocks.php'],
            [
                ['Missing "* @method static MissingDocblocks TWO()" docblock entry', 36],
                ['Missing "* @method static MissingDocblocks FOUR()" docblock entry', 36],
            ]
        );
    }

    public function testWrongExtensionRule(): void
    {
        require_once __DIR__.'/Fixtures/WrongEnumerationBaseClass.php';
        $this->analyse(
            [__DIR__.'/Fixtures/WrongEnumerationBaseClass.php'],
            [
                ['WrongEnumerationBaseClass should extend RQuadling\Enumeration\AbstractEnumeration, not Eloquent\Enumeration\AbstractEnumeration', 31],
            ]
        );
    }
}
