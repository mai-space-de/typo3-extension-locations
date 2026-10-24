<?php

declare(strict_types=1);

$allIcons = 'EXT:mai_base/Resources/Public/Icons/all-icons/';

return [
    'ext-maispace-mai_locations' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => 'EXT:mai_locations/Resources/Public/Icons/Extension.svg',
    ],
    'mai-locations' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'travel-map-location-pin--navigation-map-maps-pin-gps-location--Streamline-Core.svg',
    ],
    'mai-locations-opening-hours' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'interface-time-clock-nine-to-five--work-hour-time-clock--Streamline-Core.svg',
    ],
    'ext-maispace-tx_mailocations_location' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'travel-map-location-pin--navigation-map-maps-pin-gps-location--Streamline-Core.svg',
    ],
    'ext-maispace-tx_mailocations_opening_hours' => [
        'provider' => \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        'source' => $allIcons . 'interface-time-clock-nine-to-five--work-hour-time-clock--Streamline-Core.svg',
    ],
];
