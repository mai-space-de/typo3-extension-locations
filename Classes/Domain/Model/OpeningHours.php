<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class OpeningHours extends AbstractEntity
{
    protected int $dayOfWeek = 0;

    protected string $timeOpen = '';

    protected string $timeClose = '';

    protected bool $isClosed = false;

    protected string $note = '';

    protected ?\DateTimeImmutable $specialDate = null;

    public function getDayOfWeek(): int
    {
        return $this->dayOfWeek;
    }

    public function setDayOfWeek(int $dayOfWeek): void
    {
        $this->dayOfWeek = $dayOfWeek;
    }

    public function getTimeOpen(): string
    {
        return $this->timeOpen;
    }

    public function setTimeOpen(string $timeOpen): void
    {
        $this->timeOpen = $timeOpen;
    }

    public function getTimeClose(): string
    {
        return $this->timeClose;
    }

    public function setTimeClose(string $timeClose): void
    {
        $this->timeClose = $timeClose;
    }

    public function isIsClosed(): bool
    {
        return $this->isClosed;
    }

    public function setIsClosed(bool $isClosed): void
    {
        $this->isClosed = $isClosed;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): void
    {
        $this->note = $note;
    }

    public function getSpecialDate(): ?\DateTimeImmutable
    {
        return $this->specialDate;
    }

    public function setSpecialDate(?\DateTimeImmutable $specialDate): void
    {
        $this->specialDate = $specialDate;
    }

    public function isSpecialDay(): bool
    {
        return $this->specialDate !== null;
    }
}
