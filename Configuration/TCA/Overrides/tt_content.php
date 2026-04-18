<?php

declare(strict_types=1);

defined('TYPO3') or die();

use Maispace\MaiBase\TableConfigurationArray\CType;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$lang = Helper::localLangHelperFactory('mai_locations', 'Default/locallang_tca.xlf');

ExtensionUtility::registerPlugin(
    'MaiLocations',
    'List',
    $lang('plugin.list.title'),
    'ext-maispace-mai_locations',
    'maispace_feature',
);

(new CType('maispace_locations_list', $lang('ctype.locations_list'), 'ext-maispace-mai_locations'))
    ->addDefaultHeaderPalette()
    ->addCustomFields('pi_flexform')
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();

ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_locations/Configuration/FlexForms/LocationsListPlugin.xml',
    'maispace_locations_list',
);

ExtensionUtility::registerPlugin(
    'MaiLocations',
    'Detail',
    $lang('plugin.detail.title'),
    'ext-maispace-mai_locations',
    'maispace_feature',
);

(new CType('maispace_locations_detail', $lang('ctype.locations_detail'), 'ext-maispace-mai_locations'))
    ->addDefaultHeaderPalette()
    ->addDefaultLanguageTab()
    ->addDefaultAccessTab()
    ->setGroup('maispace_feature')
    ->register();
