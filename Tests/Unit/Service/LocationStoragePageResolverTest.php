<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Tests\Unit\Service;

use Maispace\MaiLocations\Service\LocationStoragePageResolver;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Site\SiteFinder;

final class LocationStoragePageResolverTest extends TestCase
{
    #[Test]
    public function resolveReturnsEmptyArrayForInvalidPageUid(): void
    {
        $subject = new LocationStoragePageResolver(
            $this->createMock(ConnectionPool::class),
            $this->createMock(SiteFinder::class),
        );

        self::assertSame([], $subject->resolve(0));
        self::assertSame([], $subject->resolve(-1));
    }
}
