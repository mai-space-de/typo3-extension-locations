<?php

declare(strict_types=1);

namespace Maispace\MaiLocations\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\AppendDataToPluginVariablesTrait;
use Maispace\MaiLocations\Domain\Model\Location;
use Maispace\MaiLocations\Domain\Repository\LocationRepository;
use Maispace\MaiLocations\Service\LocationStoragePageResolver;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\QueryBuilderPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class LocationController extends AbstractActionController
{
    use AppendDataToPluginVariablesTrait;

    public function __construct(
        private readonly LocationRepository $locationRepository,
        private readonly LocationStoragePageResolver $locationStoragePageResolver,
    ) {}

    public function listAction(int $page = 1): ResponseInterface
    {
        $settings = $this->getSettings();
        $pageUids = $this->resolveStoragePageUids();
        $itemsPerPage = (int) ($settings['limit'] ?? 10);

        $queryBuilder = $this->locationRepository->createQueryBuilderForPagination($pageUids);

        $paginator = new QueryBuilderPaginator(
            $queryBuilder,
            $page,
            $itemsPerPage
        );

        $pagination = new SimplePagination($paginator);

        $this->view->assignMultiple([
            'locations' => $paginator->getPaginatedItems(),
            'pagination' => $pagination,
            'paginator' => $paginator,
            'currentPage' => $page,
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
            $message = LocalizationUtility::translate('location.notFound', 'mai_locations') ?? 'Location not found.';
            return $this->htmlResponse('<p class="location-detail__empty">' . htmlspecialchars($message) . '</p>');
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
        if (!empty($pages)) {
            return array_values(array_filter(
                array_map('intval', explode(',', (string) $pages)),
                static fn(int $uid): bool => $uid > 0,
            ));
        }

        $pageArguments = $this->request->getAttribute('routing');
        $pageUid = $pageArguments instanceof PageArguments ? $pageArguments->getPageId() : 0;

        return $this->locationStoragePageResolver->resolve($pageUid);
    }
}
