<?php

/**
 * FlexiHuBee - nastavení testů.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */



include_once 'Token.php';
include_once 'Token/Stream.php';
//echo getcwd();

chdir('/home/vitex/Projects/Vitex Software/flexihubee/src/'); //TODO: relative
//exit;
require_once 'includes/config.php';
require_once 'includes/Init.php';

\Ease\Shared::user(new \FlexiHuBee\User());
\Ease\Shared::webPage(new FlexiHuBee\ui\WebPage());
