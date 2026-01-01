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

namespace EliasHaeussler\DeepClosureComparator;

use Opis\Closure;
use ReflectionFunction;
use SebastianBergmann\Comparator;

/**
 * DeepClosureComparator.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class DeepClosureComparator extends Comparator\Comparator
{
    public function accepts(mixed $expected, mixed $actual): bool
    {
        return $expected instanceof \Closure && $actual instanceof \Closure;
    }

    /**
     * @param \Closure $expected
     * @param \Closure $actual
     */
    public function assertEquals(
        mixed $expected,
        mixed $actual,
        float $delta = 0.0,
        bool $canonicalize = false,
        bool $ignoreCase = false,
    ): void {
        $expectedSerializedValue = Closure\serialize($expected);
        $actualSerializedValue = Closure\serialize($actual);

        if ($expectedSerializedValue === $actualSerializedValue) {
            return;
        }

        $expectedReflector = new ReflectionFunction($expected);
        $actualReflector = new ReflectionFunction($actual);
        $message = sprintf(
            'Failed asserting that closure declared at %s:%d is equal to closure declared at %s:%d.',
            (string) $expectedReflector->getFileName(),
            (int) $expectedReflector->getStartLine(),
            (string) $actualReflector->getFileName(),
            (int) $actualReflector->getStartLine(),
        );

        throw new Comparator\ComparisonFailure($expected, $actual, '', '', $message);
    }
}
