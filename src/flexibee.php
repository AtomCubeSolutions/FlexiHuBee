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

$flexiBees = new FlexiBees($oPage->getRequestValue('id', 'int'));
$instanceName = $flexiBees->getRecordName();

if ($oPage->isPosted()) {
    $flexiBees->takeData($_POST);
    if ($flexiBees->saveToSQL()) {
        $flexiBees->addStatusMessage(_('FlexiBee instance Saved'), 'success');
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

$oPage->container->addItem(new \Ease\TWB\Panel($instanceName, 'info',
    new ui\RegisterFlexiBeeForm($flexiBees), $instanceLink));

$oPage->addItem(new ui\PageBottom());

$oPage->draw();
