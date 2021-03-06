<?php

namespace FlexiHuBee;

/**
 * FlexiHuBee - Persistence nastavení datagridu.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */
require_once 'includes/Init.php';

$column = $oPage->getRequestValue('column');
$value = $oPage->getRequestValue('value');
$grid = $oPage->getRequestValue('grid');

if (!isset($_SESSION['gridPreferences'])) {
    $_SESSION['gridPreferences'] = [];
}

if (($column == 'reset') && ($value == 'reset')) {
    $_SESSION['gridPreferences'][$grid] = [];
    $oUser->setSettings(['gridPreferences' => $_SESSION['gridPreferences']]);
    $oUser->saveSettings();
} else {
    $_SESSION['gridPreferences'][$grid][$column] = $value;
}
