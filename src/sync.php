<?php
/**
 * FlexiHuBee - Hlavní strana.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2016 Vitex Software
 */

namespace FlexiHuBee;

require_once 'includes/Init.php';

$oPage->onlyForLogged();

$oPage->addItem(new ui\PageTop(_('Synchronization')));

$syncer = new Syncer();
$syncer->doSync();

$oPage->container->addItem(new ui\SyncResult($syncer));

$oPage->addItem(new ui\PageBottom());

$oPage->draw();
