<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/deep-closure-comparator".
 *
 * Copyright (C) 2025-2026 Elias Häußler <elias@haeussler.dev>
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

use EliasHaeussler\DeepClosureComparator as Src;
use Generator;
use PHPUnit\Framework;
use SebastianBergmann\Comparator;

/**
 * DeepClosureComparatorTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\DeepClosureComparator::class)]
final class DeepClosureComparatorTest extends Framework\TestCase
{
    private Src\DeepClosureComparator $subject;

    public function setUp(): void
    {
        $this->subject = new Src\DeepClosureComparator();
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('acceptsReturnsTrueIfExpectedAndActualAreClosuresDataProvider')]
    public function acceptsReturnsTrueIfExpectedAndActualAreClosures(
        mixed $expected,
        mixed $actual,
        bool $expectedResult,
    ): void {
        self::assertSame($expectedResult, $this->subject->accepts($expected, $actual));
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DoesNotPerformAssertions]
    public function assertEqualsDoesNotThrowExceptionIfSerializedValuesOfClosuresAreEqual(): void
    {
        $closureA = static fn () => 'foo';
        $closureB = static fn () => 'foo';

        $this->subject->assertEquals($closureA, $closureB);
    }

    #[Framework\Attributes\Test]
    public function assertEqualsDoesThrowsExceptionIfSerializedValuesOfClosuresAreNotEqual(): void
    {
        $closureA = static fn () => 'foo';
        $closureB = static fn () => 'baz';

        $this->expectException(Comparator\ComparisonFailure::class);

        $this->subject->assertEquals($closureA, $closureB);
    }

    /**
     * @return Generator<string, array{mixed, mixed, bool}>
     */
    public static function acceptsReturnsTrueIfExpectedAndActualAreClosuresDataProvider(): Generator
    {
        $closure = static fn () => 'foo';

        yield 'no closures' => ['foo', 'baz', false];
        yield 'closure for expected only' => [$closure, 'baz', false];
        yield 'closure for actual only' => ['foo', $closure, false];
        yield 'closure for expected & actual' => [$closure, $closure, true];
    }
}
