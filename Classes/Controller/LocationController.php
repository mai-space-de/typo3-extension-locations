<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\AppendDataToPluginVariablesTrait;
use Maispace\MaiLocations\Domain\Model\Location;
use Maispace\MaiLocations\Domain\Repository\LocationRepository;
use Psr\Http\Message\ResponseInterface;

class LocationController extends AbstractActionController
{
    use AppendDataToPluginVariablesTrait;

    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {}

    public function listAction(): ResponseInterface
    {
        $settings = $this->getSettings();

        $pageUids = $this->resolveStoragePageUids();

        if ($pageUids !== []) {
            $locations = $this->locationRepository->findFromPages($pageUids);
        } else {
            $locations = $this->locationRepository->findAll();
        }

        $this->view->assignMultiple([
            'locations' => $locations,
            'settings' => $settings,
            'contentObject' => $this->getContentObjectData(),
        ]);

        return $this->htmlResponse();
    }

    public function detailAction(?Location $location = null): ResponseInterface
    {
        if ($location === null) {
            $location = $this->resolveFallbackLocation();
        }

        if ($location === null) {
            return $this->htmlResponse('<p>No location found.</p>');
        }

        $this->view->assignMultiple([
            'location' => $location,
            'settings' => $this->getSettings(),
            'contentObject' => $this->getContentObjectData(),
        ]);

        return $this->htmlResponse();
    }

    private function resolveFallbackLocation(): ?Location
    {
        $pageUids = $this->resolveStoragePageUids();

        if ($pageUids !== []) {
            $locations = $this->locationRepository->findFromPages($pageUids);
        } else {
            $locations = $this->locationRepository->findAll();
        }

        $firstLocation = $locations->getFirst();

        return $firstLocation instanceof Location ? $firstLocation : null;
    }

    private function resolveStoragePageUids(): array
    {
        $pages = $this->settings['pages'] ?? '';
        if (empty($pages)) {
            return [];
        }

        return array_filter(
            array_map('intval', explode(',', (string) $pages)),
            static fn(int $uid): bool => $uid > 0,
        );
    }
}
