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

namespace RQuadlingTests\Enumeration\Fixtures;

use RQuadling\Enumeration\AbstractEnumeration;

/**
 * @method static DuplicateValues ONE()
 * @method static DuplicateValues FIRST()
 * @method static DuplicateValues TWO()
 * @method static DuplicateValues SECOND()
 * @method static DuplicateValues THREE()
 * @method static DuplicateValues THIRD()
 * @method static DuplicateValues FOUR()
 * @method static DuplicateValues FOURTH()
 * @method static DuplicateValues FIVE()
 * @method static DuplicateValues FIFTH()
 * @method static DuplicateValues REFERENCED_ONE()
 * @method static DuplicateValues REFERENCED_FIRST()
 * @method static DuplicateValues ARRAY_ONE()
 * @method static DuplicateValues ARRAY_FIRST()
 */
class DuplicateValues extends AbstractEnumeration
{
    const ONE = 1;
    const FIRST = 1;
    const TWO = 2;
    const SECOND = 2;
    const THREE = 3;
    const THIRD = 3;
    const FOUR = 4;
    const FOURTH = 4;
    const FIVE = 5;
    const FIFTH = 5;

    const MINUS_ONE = -1;
    const MINUS_FIRST = -1;

    const REFERENCED_ONE = DuplicateValues::ONE;
    const REFERENCED_FIRST = DuplicateValues::FIRST;

    const ARRAY_ONE = [self::ONE];
    const ARRAY_FIRST = [self::FIRST];

    const CLASS_ONE = DuplicateValues::class;
    const CLASS_FIRST = DuplicateValues::class;

    const CONSTANT_ONE = M_PI;
    const CONSTANT_FIRST = M_PI;
}
