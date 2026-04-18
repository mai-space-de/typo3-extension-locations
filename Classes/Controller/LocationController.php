<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\AppendDataToPluginVariablesTrait;
use Maispace\MaiBase\Controller\Traits\DetailActionTrait;
use Maispace\MaiLocations\Domain\Model\Location;
use Maispace\MaiLocations\Domain\Repository\LocationRepository;
use Psr\Http\Message\ResponseInterface;

class LocationController extends AbstractActionController
{
    use AppendDataToPluginVariablesTrait;
    use DetailActionTrait;

    public function __construct(
        private readonly LocationRepository $locationRepository,
    ) {
    }

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

    public function detailAction(): ResponseInterface
    {
        $location = $this->resolveDetailOrNotFound($this->locationRepository);
        assert($location instanceof Location);

        $this->view->assignMultiple([
            'location' => $location,
            'settings' => $this->getSettings(),
            'contentObject' => $this->getContentObjectData(),
        ]);

        return $this->htmlResponse();
    }

    private function resolveStoragePageUids(): array
    {
        $pages = $this->settings['pages'] ?? '';
        if (empty($pages)) {
            return [];
        }

        return array_filter(
            array_map('intval', explode(',', (string)$pages)),
            static fn(int $uid): bool => $uid > 0,
        );
    }
}
