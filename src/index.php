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

$oPage->addItem(new ui\PageTop(_('FlexiHUBee')));

$flexiBees = new FlexiBees();

$instances = $flexiBees->getAllFromSQL();
if (count($instances) < 2) {
    $flexiBees->addStatusMessage(_('Please register at least two FlexiBee instances').' '.sprintf(_('%s defined now'),
            count($instances)), 'warning');
    $oPage->redirect('flexibee.php');
} else {
    $mainPageMenu = new \Ease\ui\MainPageMenu();
    $mainPageMenu->addMenuItem('images/sync.png', _('Synchronization'),
        'sync.php');
    $oPage->container->addItem($mainPageMenu);
}


$oPage->addItem(new ui\PageBottom());

$oPage->draw();
