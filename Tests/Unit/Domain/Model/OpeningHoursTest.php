<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Tests\Unit\Domain\Model;

use Maispace\MaiLocations\Domain\Model\OpeningHours;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class OpeningHoursTest extends TestCase
{
    // ── Default values ──────────────────────────────────────────────────────

    #[Test]
    public function defaultDayOfWeekIsZero(): void
    {
        $hours = new OpeningHours();
        self::assertSame(0, $hours->getDayOfWeek());
    }

    #[Test]
    public function defaultTimeOpenIsEmptyString(): void
    {
        $hours = new OpeningHours();
        self::assertSame('', $hours->getTimeOpen());
    }

    #[Test]
    public function defaultTimeCloseIsEmptyString(): void
    {
        $hours = new OpeningHours();
        self::assertSame('', $hours->getTimeClose());
    }

    #[Test]
    public function defaultIsClosedIsFalse(): void
    {
        $hours = new OpeningHours();
        self::assertFalse($hours->isIsClosed());
    }

    #[Test]
    public function defaultNoteIsEmptyString(): void
    {
        $hours = new OpeningHours();
        self::assertSame('', $hours->getNote());
    }

    #[Test]
    public function defaultSpecialDateIsNull(): void
    {
        $hours = new OpeningHours();
        self::assertNull($hours->getSpecialDate());
    }

    // ── dayOfWeek getter / setter ────────────────────────────────────────────

    #[Test]
    public function setDayOfWeekStoresTheValue(): void
    {
        $hours = new OpeningHours();
        $hours->setDayOfWeek(3);
        self::assertSame(3, $hours->getDayOfWeek());
    }

    #[Test]
    public function setDayOfWeekOverwritesPreviousValue(): void
    {
        $hours = new OpeningHours();
        $hours->setDayOfWeek(1);
        $hours->setDayOfWeek(5);
        self::assertSame(5, $hours->getDayOfWeek());
    }

    // ── timeOpen getter / setter ─────────────────────────────────────────────

    #[Test]
    public function setTimeOpenStoresTheValue(): void
    {
        $hours = new OpeningHours();
        $hours->setTimeOpen('09:00');
        self::assertSame('09:00', $hours->getTimeOpen());
    }

    // ── timeClose getter / setter ────────────────────────────────────────────

    #[Test]
    public function setTimeCloseStoresTheValue(): void
    {
        $hours = new OpeningHours();
        $hours->setTimeClose('17:00');
        self::assertSame('17:00', $hours->getTimeClose());
    }

    // ── isClosed getter / setter ─────────────────────────────────────────────

    #[Test]
    public function setIsClosedToTrueWorks(): void
    {
        $hours = new OpeningHours();
        $hours->setIsClosed(true);
        self::assertTrue($hours->isIsClosed());
    }

    #[Test]
    public function setIsClosedToFalseWorks(): void
    {
        $hours = new OpeningHours();
        $hours->setIsClosed(true);
        $hours->setIsClosed(false);
        self::assertFalse($hours->isIsClosed());
    }

    // ── note getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setNoteStoresTheValue(): void
    {
        $hours = new OpeningHours();
        $hours->setNote('Holiday schedule');
        self::assertSame('Holiday schedule', $hours->getNote());
    }

    // ── specialDate getter / setter ──────────────────────────────────────────

    #[Test]
    public function setSpecialDateStoresTheDateTimeImmutable(): void
    {
        $hours = new OpeningHours();
        $date = new \DateTimeImmutable('2026-12-25');
        $hours->setSpecialDate($date);
        self::assertSame($date, $hours->getSpecialDate());
    }

    #[Test]
    public function setSpecialDateAcceptsNull(): void
    {
        $hours = new OpeningHours();
        $hours->setSpecialDate(new \DateTimeImmutable('2026-01-01'));
        $hours->setSpecialDate(null);
        self::assertNull($hours->getSpecialDate());
    }

    // ── isSpecialDay ─────────────────────────────────────────────────────────

    #[Test]
    public function isSpecialDayReturnsFalseWhenSpecialDateIsNull(): void
    {
        $hours = new OpeningHours();
        self::assertFalse($hours->isSpecialDay());
    }

    #[Test]
    public function isSpecialDayReturnsTrueWhenSpecialDateIsSet(): void
    {
        $hours = new OpeningHours();
        $hours->setSpecialDate(new \DateTimeImmutable('2026-12-25'));
        self::assertTrue($hours->isSpecialDay());
    }
}
