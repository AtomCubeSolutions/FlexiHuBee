<?php
/**
 * FlexiHuBee - Hlavní strana.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */

namespace FlexiHuBee;

require_once 'includes/Init.php';

$oPage->onlyForLogged();

$oPage->addItem(new ui\PageTop(_('FlexiHuBee')));

$flexiBees = new FlexiBees();

$instances = $flexiBees->getAllFromSQL();
if (count($instances) < 2) {
    $flexiBees->addStatusMessage(_('Please register at least two FlexiBee instances').' '.sprintf(_('%s defined now'),
            count($instances)), 'warning');
    $oPage->redirect('flexibee.php');
}
$oPage->addItem(new ui\PageBottom());

$oPage->draw();
