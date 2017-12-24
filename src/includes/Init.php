<?php
/**
 * FlexiHuBee - Application init.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2016 Vitex Software
 */

namespace FlexiHuBee;

require_once '../vendor/autoload.php';

\Ease\Shared::instanced()->loadConfig('../config.json');
\Ease\Shared::initializeGetText('flexihubee');

session_start();

/*
 * Objekt uživatele VSUser nebo VSAnonym
 * @global EaseUser
 */
$oUser                 = \Ease\Shared::user();
$oUser->settingsColumn = 'settings';

if (!\Ease\Shared::isCli()) {
    /* @var $oPage \Sys\WebPage */
    $oPage = new ui\WebPage();
}
