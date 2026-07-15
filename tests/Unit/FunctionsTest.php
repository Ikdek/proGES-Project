<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

/**
 * Tests des fonctions métier pures de src/functions.php.
 */
final class FunctionsTest extends TestCase
{
    public function testHashLoginReturnsSha256Hexadecimal(): void
    {
        $hash = hashLogin('admin');

        self::assertSame(
            '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',
            $hash
        );
        self::assertSame(64, strlen($hash));
    }

    public function testHashLoginIsDeterministic(): void
    {
        self::assertSame(hashLogin('user'), hashLogin('user'));
    }

    public function testHashLoginIsCaseSensitive(): void
    {
        self::assertNotSame(hashLogin('Admin'), hashLogin('admin'));
    }

    public function testSanitizeSortOrderAcceptsAllowedColumn(): void
    {
        $allowed = ['className', 'firstName', 'lastName', 'mark'];

        self::assertSame('lastName', sanitizeSortOrder('lastName', $allowed, 'className'));
    }

    public function testSanitizeSortOrderRejectsSqlInjectionPayload(): void
    {
        $allowed = ['className', 'firstName', 'lastName', 'mark'];

        self::assertSame(
            'className',
            sanitizeSortOrder('mark; DROP TABLE users--', $allowed, 'className')
        );
    }

    public function testSanitizeSortOrderFallsBackToDefaultWhenNull(): void
    {
        self::assertSame('className', sanitizeSortOrder(null, ['className'], 'className'));
    }

    public function testSanitizeSortOrderIsStrictAboutType(): void
    {
        // in_array() non strict ferait passer '0' pour n'importe quelle chaîne.
        self::assertSame('className', sanitizeSortOrder('0', ['className'], 'className'));
    }

    public function testWeeksInYearReturns53ForLongYears(): void
    {
        // 2020 et 2026 comptent 53 semaines ISO ; 2025 en compte 52.
        self::assertSame(53, weeksInYear(2020));
        self::assertSame(53, weeksInYear(2026));
        self::assertSame(52, weeksInYear(2025));
    }

    public function testPreviousWeekStaysInSameYear(): void
    {
        self::assertSame([28, 2026], previousWeek(29, 2026));
    }

    public function testPreviousWeekRollsBackToLastWeekOfPreviousYear(): void
    {
        // 2025 compte 52 semaines : la semaine avant la 1re de 2026 est la 52e de 2025.
        self::assertSame([52, 2025], previousWeek(1, 2026));
    }

    public function testNextWeekStaysInSameYear(): void
    {
        self::assertSame([30, 2026], nextWeek(29, 2026));
    }

    public function testNextWeekRollsOverToFirstWeekOfNextYear(): void
    {
        // 2026 compte 53 semaines : après la 53e vient la 1re de 2027.
        self::assertSame([1, 2027], nextWeek(53, 2026));
    }

    public function testNextWeekDoesNotSkipWeek53OnLongYears(): void
    {
        // Le code d'origine codait 52 en dur et sautait la semaine 53.
        self::assertSame([53, 2026], nextWeek(52, 2026));
    }

    public function testIsValidMarkAcceptsBoundsAndDecimals(): void
    {
        self::assertTrue(isValidMark(0));
        self::assertTrue(isValidMark(20));
        self::assertTrue(isValidMark(12.5));
        self::assertTrue(isValidMark('15'));
    }

    public function testIsValidMarkRejectsOutOfRangeAndNonNumeric(): void
    {
        self::assertFalse(isValidMark(-1));
        self::assertFalse(isValidMark(20.1));
        self::assertFalse(isValidMark('abc'));
        self::assertFalse(isValidMark(''));
    }
}
