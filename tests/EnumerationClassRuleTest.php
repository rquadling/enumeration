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

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use RQuadling\Enumeration\PHPStan\Rules\EnumerationClassRule;

class EnumerationClassRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new EnumerationClassRule();
    }

    public function testEnumerationClassRule()
    {
        require_once __DIR__.'/Fixtures/DuplicateValues.php';
        $this->analyse(
            [__DIR__.'/Fixtures/DuplicateValues.php'],
            [
                ['Multiple names exist for the value "1" in DuplicateValues', 47],
                ['Multiple names exist for the value "2" in DuplicateValues', 47],
                ['Multiple names exist for the value "3" in DuplicateValues', 47],
                ['Multiple names exist for the value "4" in DuplicateValues', 47],
                ['Multiple names exist for the value "5" in DuplicateValues', 47],
                ['Multiple names exist for the value "-1" in DuplicateValues', 47],
                ['Multiple names exist for the value "1" in DuplicateValues', 47],
                ['Multiple names exist for the value "1" in DuplicateValues', 47],
                ['Multiple names exist for the value "RQuadlingTests\Enumeration\Fixtures\DuplicateValues::class" in DuplicateValues', 47],
                ['Multiple names exist for the value "3.1415926535898" in DuplicateValues', 47],
                ['Missing "* @method static DuplicateValues MINUS_ONE()" docblock entry', 47],
                ['Missing "* @method static DuplicateValues MINUS_FIRST()" docblock entry', 47],
                ['Missing "* @method static DuplicateValues CLASS_ONE()" docblock entry', 47],
                ['Missing "* @method static DuplicateValues CLASS_FIRST()" docblock entry', 47],
                ['Missing "* @method static DuplicateValues CONSTANT_ONE()" docblock entry', 47],
                ['Missing "* @method static DuplicateValues CONSTANT_FIRST()" docblock entry', 47],
            ]
        );
    }

    public function testEnumerationExtensionClassRule()
    {
        require_once __DIR__.'/Fixtures/WrongEnumerationBaseClass.php';
        $this->analyse(
            [__DIR__.'/Fixtures/WrongEnumerationBaseClass.php'],
            [
                ['WrongEnumerationBaseClass should extend RQuadling\Enumeration\AbstractEnumeration, not Eloquent\Enumeration\AbstractEnumeration', 7],
            ]
        );
    }
}