<?php

namespace FlexiHuBee;

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

if ($oPage->isPosted()) {
    $flexiBees->takeData($_POST);
    if ($flexiBees->saveToSQL()) {
        $flexiBees->addStatusMessage(_('FlexiBee instance Saved'), 'success');
    } else {
        $flexiBees->addStatusMessage(_('Error saving FlexiBee instance'),
            'error');
    }
}

$oPage->container->addItem(new \Ease\TWB\Panel(_('New FlexiBee'), 'info',
    new ui\RegisterFlexiBeeForm($flexiBees)));

$oPage->addItem(new ui\PageBottom());

$oPage->draw();
