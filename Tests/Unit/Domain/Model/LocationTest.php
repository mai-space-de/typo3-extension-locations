<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Tests\Unit\Domain\Model;

use Maispace\MaiLocations\Domain\Model\Location;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class LocationTest extends TestCase
{
    // ── Default values ──────────────────────────────────────────────────────

    #[Test]
    public function defaultNameIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getName());
    }

    #[Test]
    public function defaultStreetIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getStreet());
    }

    #[Test]
    public function defaultZipIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getZip());
    }

    #[Test]
    public function defaultCityIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getCity());
    }

    #[Test]
    public function defaultCountryIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getCountry());
    }

    #[Test]
    public function defaultPhoneIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getPhone());
    }

    #[Test]
    public function defaultEmailIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getEmail());
    }

    #[Test]
    public function defaultLatitudeIsZero(): void
    {
        $location = new Location();
        self::assertSame(0.0, $location->getLatitude());
    }

    #[Test]
    public function defaultLongitudeIsZero(): void
    {
        $location = new Location();
        self::assertSame(0.0, $location->getLongitude());
    }

    #[Test]
    public function defaultDescriptionIsEmptyString(): void
    {
        $location = new Location();
        self::assertSame('', $location->getDescription());
    }

    #[Test]
    public function constructorInitializesImageAsObjectStorage(): void
    {
        $location = new Location();
        self::assertInstanceOf(ObjectStorage::class, $location->getImage());
    }

    #[Test]
    public function constructorInitializesOpeningHoursAsObjectStorage(): void
    {
        $location = new Location();
        self::assertInstanceOf(ObjectStorage::class, $location->getOpeningHours());
    }

    #[Test]
    public function constructorCreatesFreshEmptyImageStorage(): void
    {
        $location = new Location();
        self::assertCount(0, $location->getImage());
    }

    #[Test]
    public function constructorCreatesFreshEmptyOpeningHoursStorage(): void
    {
        $location = new Location();
        self::assertCount(0, $location->getOpeningHours());
    }

    // ── initializeObject ────────────────────────────────────────────────────

    #[Test]
    public function initializeObjectCreatesFreshImageStorage(): void
    {
        $location = new Location();
        $original = $location->getImage();
        $location->initializeObject();
        self::assertInstanceOf(ObjectStorage::class, $location->getImage());
        self::assertNotSame($original, $location->getImage());
    }

    #[Test]
    public function initializeObjectCreatesFreshOpeningHoursStorage(): void
    {
        $location = new Location();
        $original = $location->getOpeningHours();
        $location->initializeObject();
        self::assertInstanceOf(ObjectStorage::class, $location->getOpeningHours());
        self::assertNotSame($original, $location->getOpeningHours());
    }

    // ── name getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setNameStoresTheValue(): void
    {
        $location = new Location();
        $location->setName('Pulheim Town Hall');
        self::assertSame('Pulheim Town Hall', $location->getName());
    }

    // ── street getter / setter ───────────────────────────────────────────────

    #[Test]
    public function setStreetStoresTheValue(): void
    {
        $location = new Location();
        $location->setStreet('Hauptstraße 1');
        self::assertSame('Hauptstraße 1', $location->getStreet());
    }

    // ── zip getter / setter ──────────────────────────────────────────────────

    #[Test]
    public function setZipStoresTheValue(): void
    {
        $location = new Location();
        $location->setZip('50259');
        self::assertSame('50259', $location->getZip());
    }

    // ── city getter / setter ─────────────────────────────────────────────────

    #[Test]
    public function setCityStoresTheValue(): void
    {
        $location = new Location();
        $location->setCity('Pulheim');
        self::assertSame('Pulheim', $location->getCity());
    }

    // ── country getter / setter ──────────────────────────────────────────────

    #[Test]
    public function setCountryStoresTheValue(): void
    {
        $location = new Location();
        $location->setCountry('Germany');
        self::assertSame('Germany', $location->getCountry());
    }

    // ── phone getter / setter ────────────────────────────────────────────────

    #[Test]
    public function setPhoneStoresTheValue(): void
    {
        $location = new Location();
        $location->setPhone('+49 2238 123456');
        self::assertSame('+49 2238 123456', $location->getPhone());
    }

    // ── email getter / setter ────────────────────────────────────────────────

    #[Test]
    public function setEmailStoresTheValue(): void
    {
        $location = new Location();
        $location->setEmail('info@bgm-pulheim.org');
        self::assertSame('info@bgm-pulheim.org', $location->getEmail());
    }

    // ── latitude getter / setter ─────────────────────────────────────────────

    #[Test]
    public function setLatitudeStoresTheValue(): void
    {
        $location = new Location();
        $location->setLatitude(51.0);
        self::assertSame(51.0, $location->getLatitude());
    }

    // ── longitude getter / setter ────────────────────────────────────────────

    #[Test]
    public function setLongitudeStoresTheValue(): void
    {
        $location = new Location();
        $location->setLongitude(6.8);
        self::assertSame(6.8, $location->getLongitude());
    }

    // ── description getter / setter ──────────────────────────────────────────

    #[Test]
    public function setDescriptionStoresTheValue(): void
    {
        $location = new Location();
        $location->setDescription('The central meeting place.');
        self::assertSame('The central meeting place.', $location->getDescription());
    }

    // ── image getter / setter ────────────────────────────────────────────────

    #[Test]
    public function setImageStoresTheObjectStorage(): void
    {
        $location = new Location();
        $storage = new ObjectStorage();
        $location->setImage($storage);
        self::assertSame($storage, $location->getImage());
    }

    // ── openingHours getter / setter ─────────────────────────────────────────

    #[Test]
    public function setOpeningHoursStoresTheObjectStorage(): void
    {
        $location = new Location();
        $storage = new ObjectStorage();
        $location->setOpeningHours($storage);
        self::assertSame($storage, $location->getOpeningHours());
    }

    // ── getCoverImage ────────────────────────────────────────────────────────

    #[Test]
    public function getCoverImageReturnsNullWhenImageStorageIsEmpty(): void
    {
        $location = new Location();
        self::assertNull($location->getCoverImage());
    }

    // ── hasCoordinates ───────────────────────────────────────────────────────

    #[Test]
    public function hasCoordinatesReturnsFalseWhenBothAreZero(): void
    {
        $location = new Location();
        self::assertFalse($location->hasCoordinates());
    }

    #[Test]
    public function hasCoordinatesReturnsTrueWhenLatitudeIsNonZero(): void
    {
        $location = new Location();
        $location->setLatitude(51.0);
        self::assertTrue($location->hasCoordinates());
    }

    #[Test]
    public function hasCoordinatesReturnsTrueWhenLongitudeIsNonZero(): void
    {
        $location = new Location();
        $location->setLongitude(6.8);
        self::assertTrue($location->hasCoordinates());
    }

    // ── Instance isolation ───────────────────────────────────────────────────

    #[Test]
    public function twoLocationInstancesHaveIndependentImageStorages(): void
    {
        $loc1 = new Location();
        $loc2 = new Location();
        self::assertNotSame($loc1->getImage(), $loc2->getImage());
    }

    #[Test]
    public function twoLocationInstancesHaveIndependentOpeningHoursStorages(): void
    {
        $loc1 = new Location();
        $loc2 = new Location();
        self::assertNotSame($loc1->getOpeningHours(), $loc2->getOpeningHours());
    }
}
