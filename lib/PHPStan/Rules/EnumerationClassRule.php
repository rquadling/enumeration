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

namespace RQuadling\Enumeration\PHPStan\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\UnaryMinus;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassConst;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use RQuadling\Enumeration\AbstractEnumeration;

class EnumerationClassRule implements Rule
{
    /**
     * @return string Class implementing \PhpParser\Node
     */
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @param Class_ $node
     *
     * @return string[] errors
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $result = [];

        // If this is a class that extends \Eloquent\Enumeration\AbstractEnumeration then this
        // should extend \RQuadling\Enumeration\AbstractEnumeration
        if (
            $node->extends &&
            $node->extends->toString() === \Eloquent\Enumeration\AbstractEnumeration::class &&
            $node->namespacedName &&
            $node->namespacedName->toString() !== AbstractEnumeration::class
        ) {
            $result[] = \sprintf(
                '%s should extend %s, not %s',
                $node->name,
                AbstractEnumeration::class,
                \Eloquent\Enumeration\AbstractEnumeration::class
            );
        }

        if (
            $node->extends &&
            \in_array(
                $node->extends->toString(),
                [
                    \Eloquent\Enumeration\AbstractEnumeration::class,
                    AbstractEnumeration::class,
                ]
            )
        ) {
            // If the enum class has duplicate values, then this may be a problem.
            $rawConstants = \array_filter(
                $node->stmts,
                function (\PhpParser\Node\Stmt $stmt) {
                    return $stmt instanceof ClassConst;
                }
            );
            $constants = \array_combine(
                \array_map(
                    function (ClassConst $stmt) {
                        return $stmt->consts[0]->name;
                    },
                    $rawConstants
                ),
                \array_map(
                    function (ClassConst $stmt) {
                        return $this->getValue($stmt->consts[0]->value);
                    },
                    $rawConstants
                )
            );
            if (\count($constants) !== \count(\array_unique($constants, SORT_REGULAR))) {
                foreach (\array_diff_assoc($constants, \array_unique($constants)) as $value) {
                    $result[] = \sprintf('Multiple names exist for the value "%s" in %s', $value, $node->name);
                }
            }

            // If there is no @method entry, report it.
            $docBlock = $node->getAttribute('comments') ? $node->getAttribute('comments')[0]->getText() : '';
            foreach ($node->stmts as $stmt) {
                if ($stmt instanceof ClassConst) {
                    $method = \sprintf(
                        '* @method static %s %s()',
                        $node->name,
                        $stmt->consts[0]->name
                    );
                    if (false === \strpos($docBlock, $method)) {
                        $result[] = \sprintf(
                            'Missing "%s" docblock entry',
                            $method
                        );
                    }
                }
            }
        }

        return $result;
    }

    private function getValue(Expr $rawValue)
    {
        switch (\get_class($rawValue)) {
            case Array_::class:
                // We're cheating here and making a LOT of assumptions about the array for the constant.
                // Ideally, we should be iterating the set and expanding the values and then serializing
                // them to get the hash value.
                // If an array has two sets of the same values, but in a different order, then they are
                // 'different' under the current scheme.
                // But, currently, this is OK as we don't have many array constants.
                $value = \md5(\serialize($rawValue));
                break;
            case ClassConstFetch::class:
                // A 'SomeClass::class' is not the same as 'SomeClass::constant'.
                $constant = \sprintf(
                    '%s::%s',
                    $rawValue->class,
                    $rawValue->name
                );
                switch ($rawValue->name) {
                    case 'class':
                        $value = $constant;
                        break;
                    default:
                        $value = \constant($constant);
                }
                break;
            case ConstFetch::class:
                $value = \constant($rawValue->name);
                break;
            case DNumber::class:
            case LNumber::class:
            case String_::class:
                $value = $rawValue->value;
                break;
            case UnaryMinus::class:
                $value = -($this->getValue($rawValue->expr));
                break;
            default:
                // @codeCoverageIgnoreStart
                die(\sprintf('Unknown type of %s present in %s', \get_class($rawValue), __METHOD__));
                // @codeCoverageIgnoreEnd
        }

        return $value;
    }
}
