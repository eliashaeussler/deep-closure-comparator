<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/deep-closure-comparator".
 *
 * Copyright (C) 2025 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace EliasHaeussler\DeepClosureComparator\Tests;

use Closure;
use EliasHaeussler\DeepClosureComparator as Src;
use Generator;
use PHPUnit\Framework;
use stdClass;

/**
 * DeepClosureAssertTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\DeepClosureAssert::class)]
final class DeepClosureAssertTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('assertEqualsWithDeepClosureComparisonFailsIfClosuresAreNotEqualDataProvider')]
    public function assertEqualsWithDeepClosureComparisonFailsIfClosuresAreNotEqual(mixed $expected, mixed $actual): void
    {
        $this->expectException(Framework\AssertionFailedError::class);

        Src\DeepClosureAssert::assertEquals($expected, $actual);
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('assertEqualsWithDeepClosureComparisonDoesNotFailIfClosuresAreEqualDataProvider')]
    #[Framework\Attributes\DoesNotPerformAssertions]
    public function assertEqualsWithDeepClosureComparisonDoesNotFailIfClosuresAreEqual(mixed $expected, mixed $actual): void
    {
        Src\DeepClosureAssert::assertEquals($expected, $actual);
    }

    /**
     * @return Generator<string, array{mixed, mixed}>
     */
    public static function assertEqualsWithDeepClosureComparisonFailsIfClosuresAreNotEqualDataProvider(): Generator
    {
        $closureA = static fn () => 'foo';
        $closureB = static fn () => 'baz';
        $objectWithClosure = static function (Closure $closure) {
            $object = new stdClass();
            $object->foo = $closure;

            return $object;
        };

        yield 'non-equal closures' => [$closureA, $closureB];
        yield 'objects with non-equal closures as properties' => [
            $objectWithClosure($closureA),
            $objectWithClosure($closureB),
        ];
    }

    /**
     * @return Generator<string, array{mixed, mixed}>
     */
    public static function assertEqualsWithDeepClosureComparisonDoesNotFailIfClosuresAreEqualDataProvider(): Generator
    {
        $closureA = static fn () => 'foo';
        $closureB = static fn () => 'foo';
        $objectWithClosure = static function (Closure $closure) {
            $object = new stdClass();
            $object->foo = $closure;

            return $object;
        };

        yield 'equal closures' => [$closureA, $closureB];
        yield 'objects with equal closures as properties' => [
            $objectWithClosure($closureA),
            $objectWithClosure($closureB),
        ];
    }
}
