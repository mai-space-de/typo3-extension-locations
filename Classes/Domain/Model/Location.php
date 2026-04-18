<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Location extends AbstractEntity
{
    protected string $name = '';

    protected string $street = '';

    protected string $zip = '';

    protected string $city = '';

    protected string $country = '';

    protected string $phone = '';

    protected string $email = '';

    protected float $latitude = 0.0;

    protected float $longitude = 0.0;

    protected string $description = '';

    /**
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $image;

    /**
     * @var ObjectStorage<OpeningHours>
     */
    protected ObjectStorage $openingHours;

    public function __construct()
    {
        $this->image = new ObjectStorage();
        $this->openingHours = new ObjectStorage();
    }

    public function initializeObject(): void
    {
        $this->image = $this->image instanceof LazyLoadingProxy
            ? $this->image->_loadRealInstance()
            : ($this->image ?? new ObjectStorage());

        $this->openingHours = $this->openingHours instanceof LazyLoadingProxy
            ? $this->openingHours->_loadRealInstance()
            : ($this->openingHours ?? new ObjectStorage());
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    public function getZip(): string
    {
        return $this->zip;
    }

    public function setZip(string $zip): void
    {
        $this->zip = $zip;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): void
    {
        $this->country = $country;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getImage(): ObjectStorage
    {
        return $this->image;
    }

    /**
     * @param ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $image
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    public function getCoverImage(): ?\TYPO3\CMS\Extbase\Domain\Model\FileReference
    {
        $images = $this->getImage();
        if ($images->count() === 0) {
            return null;
        }
        $images->rewind();
        return $images->current();
    }

    public function hasCoordinates(): bool
    {
        return $this->latitude !== 0.0 || $this->longitude !== 0.0;
    }

    /**
     * @return ObjectStorage<OpeningHours>
     */
    public function getOpeningHours(): ObjectStorage
    {
        return $this->openingHours;
    }

    /**
     * @param ObjectStorage<OpeningHours> $openingHours
     */
    public function setOpeningHours(ObjectStorage $openingHours): void
    {
        $this->openingHours = $openingHours;
    }
}
