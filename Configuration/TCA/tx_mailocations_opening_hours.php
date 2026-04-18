<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CheckboxConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\SelectSingleConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_locations', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mailocations_opening_hours')))
    ->setDefaultConfig()
    ->setLabel('day_of_week')
    ->setIconFile('EXT:mai_locations/Resources/Public/Icons/tx_mailocations_opening_hours.svg')
    ->setSortingField()
    ->addColumn(
        'day_of_week',
        $lang('tx_mailocations_opening_hours.day_of_week'),
        (new SelectSingleConfig())
            ->setItems([
                [$lang('tx_mailocations_opening_hours.day_of_week.0'), '0'],
                [$lang('tx_mailocations_opening_hours.day_of_week.1'), '1'],
                [$lang('tx_mailocations_opening_hours.day_of_week.2'), '2'],
                [$lang('tx_mailocations_opening_hours.day_of_week.3'), '3'],
                [$lang('tx_mailocations_opening_hours.day_of_week.4'), '4'],
                [$lang('tx_mailocations_opening_hours.day_of_week.5'), '5'],
                [$lang('tx_mailocations_opening_hours.day_of_week.6'), '6'],
                [$lang('tx_mailocations_opening_hours.day_of_week.special'), 'special'],
            ])
    )
    ->addColumn(
        'time_open',
        $lang('tx_mailocations_opening_hours.time_open'),
        (new InputConfig())->setSize(5)->setMax(5)->setEval('trim')
    )
    ->addColumn(
        'time_close',
        $lang('tx_mailocations_opening_hours.time_close'),
        (new InputConfig())->setSize(5)->setMax(5)->setEval('trim')
    )
    ->addColumn(
        'is_closed',
        $lang('tx_mailocations_opening_hours.is_closed'),
        (new CheckboxConfig())->setRenderType('checkboxToggle')
    )
    ->addColumn(
        'note',
        $lang('tx_mailocations_opening_hours.note'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')
    )
    ->addColumn(
        'special_date',
        $lang('tx_mailocations_opening_hours.special_date'),
        (new InputConfig())->setSize(13)->setEval('date')
    )
    ->addTypeShowItem(
        '0',
        'day_of_week, special_date, is_closed,
        --palette--;;times,
        note'
    )
    ->addPalette('times', $lang('palette.times'), 'time_open, time_close')
    ->getConfig();
