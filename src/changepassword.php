<?php

namespace FlexiHuBee;

/**
 * FlexiHuBee - Změna hesla.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */
require_once 'includes/Init.php';

$oPage->onlyForLogged(); //Pouze pro přihlášené
$formOK = true;

if (!isset($_POST['password']) || !strlen($_POST['password'])) {
    $oUser->addStatusMessage('Prosím zadejte nové heslo');
    $formOK = false;
} else {
    if ($_POST['password'] == $oUser->getUserLogin()) {
        $oUser->addStatusMessage('Heslo se nesmí shodovat s přihlašovacím jménem', 'waring');
        $formOK = false;
    }
    /* TODO:
      if(!$OUser->passwordCrackCheck($_POST['password'])){
      $OUser->addStatusMessage('Heslo není dostatečně bezpečné');
      $FormOK = false;
      }
     */
}
if (!isset($_POST['passwordConfirm']) || !strlen($_POST['passwordConfirm'])) {
    $oUser->addStatusMessage('Prosím zadejte potvrzení hesla');
    $formOK = false;
}
if ((isset($_POST['passwordConfirm']) && isset($_POST['password'])) && ($_POST['passwordConfirm'] != $_POST['password'])) {
    $oUser->addStatusMessage('Zadaná hesla se neshodují', 'waring');
    $formOK = false;
}

if (!isset($_POST['CurrentPassword'])) {
    $oUser->addStatusMessage('Prosím zadejte stávající heslo');
    $formOK = false;
} else {
    if (!$oUser->passwordValidation($_POST['CurrentPassword'], $oUser->GetDataValue($oUser->passwordColumn))) {
        $oUser->AddStatusMessage('Stávající heslo je neplatné', 'warning');
        $formOK = false;
    }
}

$oPage->addItem(new ui\PageTop(_('Změna hesla uživatele')));

if ($formOK && $oPage->isPosted()) {
    $plainPass = $oPage->getRequestValue('password');

    if ($oUser->passwordChange($plainPass)) {
        $oUser->addStatusMessage(_('Heslo bylo změněno'), 'success');
    }
} else {
    $loginForm = new \Ease\Html\Form(null);

    $loginForm->addItem(new EaseLabeledPasswordInput('CurrentPassword', null, _('Stávající heslo')));

    $loginForm->addItem(new EaseLabeledPasswordStrongInput('password', null, _('Nové heslo').' *'));
    $loginForm->addItem(new EaseLabeledPasswordControlInput('passwordConfirm', null, _('potvrzení hesla').' *', ['id' => 'confirmation']));

    $loginForm->addItem(new EaseJQuerySubmitButton('Ok', 'Změnit heslo'));

    $loginForm->fillUp($_POST);

    $oPage->container->addItem(new \Ease\Html\FieldSet(_('změna hesla'), $loginForm));
}

$oPage->addItem(new ui\PageBottom());

$oPage->draw();
