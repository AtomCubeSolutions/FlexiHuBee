<?php
/**
 * FlexiHuBee - FlexiBee instance editor.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */

namespace FlexiHuBee;

require_once 'includes/Init.php';

$oPage->onlyForLogged();

$oPage->addItem(new ui\PageTop(_('FlexiBee instance')));

$flexiBees    = new FlexiBees($oPage->getRequestValue('id', 'int'));
$instanceName = $flexiBees->getRecordName();

if ($oPage->isPosted()) {
    if ($flexiBees->takeData($_POST) && !is_null($flexiBees->saveToSQL())) {
        $flexiBees->addStatusMessage(_('FlexiBee instance Saved'), 'success');
        $flexiBees->prepareRemoteFlexiBee();
    } else {
        $flexiBees->addStatusMessage(_('Error saving FlexiBee instance'),
            'error');
    }
}

if (strlen($instanceName)) {
    $instanceLink = new \Ease\Html\ATag($flexiBees->getLink(),
        $flexiBees->getLink());
} else {
    $instanceName = _('New FlexiBee instance');
    $instanceLink = null;
}

$instanceRow = new \Ease\TWB\Row();
$instanceRow->addColumn(8,new ui\RegisterFlexiBeeForm($flexiBees));
$instanceRow->addColumn(4,new ui\FlexiBeeInstanceStatus($flexiBees));

$oPage->container->addItem(new \Ease\TWB\Panel($instanceName, 'info',
    $instanceRow, $instanceLink));

$oPage->addItem(new ui\PageBottom());

$oPage->draw();
