<?php

declare(strict_types=1);

return [
    \Maispace\MaiLocations\Domain\Model\Location::class => [
        'tableName' => 'tx_mailocations_location',
    ],
    \Maispace\MaiLocations\Domain\Model\OpeningHours::class => [
        'tableName' => 'tx_mailocations_opening_hours',
    ],
];
