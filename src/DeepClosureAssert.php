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

namespace EliasHaeussler\DeepClosureComparator;

use PHPUnit\Framework;
use SebastianBergmann\Comparator;

/**
 * DeepClosureAssert.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
trait DeepClosureAssert
{
    public static function assertEqualsWithDeepClosureComparison(mixed $expected, mixed $actual): void
    {
        $comparatorFactory = Comparator\Factory::getInstance();
        $comparatorFactory->register(new DeepClosureComparator());

        try {
            $comparator = $comparatorFactory->getComparatorFor($expected, $actual);
            $comparator->assertEquals($expected, $actual);
        } catch (Comparator\ComparisonFailure $failure) {
            Framework\Assert::fail($failure->getMessage());
        }

        // Required to avoid "no assertions" warnings in PHPUnit
        Framework\Assert::assertTrue(true);
    }
}
