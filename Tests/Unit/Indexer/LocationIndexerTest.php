<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Tests\Unit\Indexer;

use Maispace\MaiLocations\Domain\Model\Location;
use Maispace\MaiLocations\Indexer\LocationIndexer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class LocationIndexerTest extends TestCase
{
    private LocationIndexer $subject;

    protected function setUp(): void
    {
        $this->subject = new LocationIndexer();
    }

    #[Test]
    public function getTypeReturnsLocation(): void
    {
        self::assertSame('location', $this->subject->getType());
    }

    #[Test]
    public function supportsLocationsTable(): void
    {
        self::assertTrue($this->subject->supports('tx_mailocations_location'));
    }

    #[Test]
    public function doesNotSupportOtherTables(): void
    {
        self::assertFalse($this->subject->supports('tx_mainews_news'));
        self::assertFalse($this->subject->supports('pages'));
        self::assertFalse($this->subject->supports('tt_content'));
    }

    #[Test]
    public function getIconReturnsExpectedValue(): void
    {
        self::assertSame('content-map-marker', $this->subject->getIcon('location'));
    }

    #[Test]
    public function buildContentIncludesAddressFields(): void
    {
        $location = new Location();
        $location->setStreet('Musterstraße 1');
        $location->setZip('12345');
        $location->setCity('Musterstadt');
        $location->setCountry('Deutschland');

        $content = $this->invokeBuildContent($location);

        self::assertStringContainsString('Musterstraße 1', $content);
        self::assertStringContainsString('12345', $content);
        self::assertStringContainsString('Musterstadt', $content);
        self::assertStringContainsString('Deutschland', $content);
    }

    #[Test]
    public function buildContentIncludesContactInfo(): void
    {
        $location = new Location();
        $location->setPhone('+49 123 456789');
        $location->setEmail('info@example.org');

        $content = $this->invokeBuildContent($location);

        self::assertStringContainsString('+49 123 456789', $content);
        self::assertStringContainsString('info@example.org', $content);
    }

    #[Test]
    public function buildContentStripsHtmlFromDescription(): void
    {
        $location = new Location();
        $location->setDescription('<p>Beautiful <strong>venue</strong> in the city centre.</p>');

        $content = $this->invokeBuildContent($location);

        self::assertStringContainsString('Beautiful', $content);
        self::assertStringContainsString('venue', $content);
        self::assertStringNotContainsString('<p>', $content);
        self::assertStringNotContainsString('<strong>', $content);
    }

    #[Test]
    public function buildContentReturnsEmptyStringForNonLocationRecord(): void
    {
        $content = $this->invokeBuildContent(new \stdClass());

        self::assertSame('', $content);
    }

    #[Test]
    public function formatResultReturnsSearchResultWithCorrectType(): void
    {
        $solrDoc = [
            'title_s' => 'Main Office',
            'content_t' => 'Musterstraße 1, Musterstadt',
            'url_s' => '/locations/main-office',
            'score' => 1.8,
        ];

        $result = $this->subject->formatResult($solrDoc);

        self::assertSame('location', $result->type);
        self::assertSame('Main Office', $result->title);
        self::assertSame('/locations/main-office', $result->url);
        self::assertSame('content-map-marker', $result->icon);
        self::assertSame(1.8, $result->score);
    }

    #[Test]
    public function formatResultDefaultsToEmptyStringsWhenFieldsAreMissing(): void
    {
        $result = $this->subject->formatResult([]);

        self::assertSame('', $result->title);
        self::assertSame('', $result->url);
        self::assertSame(0.0, $result->score);
        self::assertNull($result->date);
    }

    #[Test]
    public function formatResultDateIsAlwaysNull(): void
    {
        $result = $this->subject->formatResult(['crdate_dt' => '2026-01-01T00:00:00Z']);

        self::assertNull($result->date);
    }

    private function invokeBuildContent(object $record): string
    {
        $reflection = new \ReflectionMethod($this->subject, 'buildContent');
        $reflection->setAccessible(true);

        /** @var string $result */
        return $reflection->invoke($this->subject, $record);
    }
}
