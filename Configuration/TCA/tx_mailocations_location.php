<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\EmailConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InlineConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\NumberConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_locations', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_mailocations_location')))
    ->setSearchFields('name,street,city,email')
    ->setDefaultConfig()
    ->setLabel('name')
    ->setIconFile('EXT:mai_locations/Resources/Public/Icons/tx_mailocations_location.svg')
    ->setSortingField()
    ->addColumn(
        'name',
        $lang('tx_mailocations_location.name'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')->setRequired()
    )
    ->addColumn(
        'street',
        $lang('tx_mailocations_location.street'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')
    )
    ->addColumn(
        'zip',
        $lang('tx_mailocations_location.zip'),
        (new InputConfig())->setSize(10)->setMax(20)->setEval('trim')
    )
    ->addColumn(
        'city',
        $lang('tx_mailocations_location.city'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')
    )
    ->addColumn(
        'country',
        $lang('tx_mailocations_location.country'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')
    )
    ->addColumn(
        'phone',
        $lang('tx_mailocations_location.phone'),
        (new InputConfig())->setSize(20)->setMax(50)->setEval('trim')
    )
    ->addColumn(
        'email',
        $lang('tx_mailocations_location.email'),
        (new EmailConfig())
    )
    ->addColumn(
        'latitude',
        $lang('tx_mailocations_location.latitude'),
        (new NumberConfig())->setFormat('decimal')
    )
    ->addColumn(
        'longitude',
        $lang('tx_mailocations_location.longitude'),
        (new NumberConfig())->setFormat('decimal')
    )
    ->addColumn(
        'description',
        $lang('tx_mailocations_location.description'),
        (new TextConfig())->setRows(8)->setCols(50)->enableRte()->setRichtextConfiguration('default')
    )
    ->addColumn(
        'image',
        $lang('tx_mailocations_location.image'),
        (new FileConfig())
            ->setAllowed('common-image-types')
            ->setMaxItems(1)
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_mailocations_location.image.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => false, 'sort' => false, 'hide' => true, 'delete' => true],
            ])
    )
    ->addColumn(
        'opening_hours',
        $lang('tx_mailocations_location.opening_hours'),
        (new InlineConfig())
            ->setForeignTable('tx_mailocations_opening_hours')
            ->setForeignField('parentid')
            ->setForeignTableField('parenttable')
            ->setAppearance([
                'collapseAll' => false,
                'expandSingle' => true,
                'newRecordLinkAddTitle' => true,
                'levelLinksPosition' => 'bottom',
                'useSortable' => true,
                'showPossibleLocalizationRecords' => true,
                'showAllLocalizationLink' => true,
                'showSynchronizationLink' => true,
            ])
    )
    ->addTypeShowItem(
        '0',
        'name,
        --palette--;;address,
        phone, email,
        --palette--;;coordinates,
        description, image, opening_hours,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->addPalette('address', $lang('palette.address'), 'street, zip, city, country')
    ->addPalette('coordinates', $lang('palette.coordinates'), 'latitude, longitude')
    ->getConfig();
