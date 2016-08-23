<?php

namespace FlexiHuBee;

/**
 * FlexiHuBee - Obnova hesla.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */
require_once 'includes/Init.php';
require_once 'Ease/EaseMail.php';
require_once 'Ease/\Ease\Html\Form.php';
$success = false;

$emailTo = $oPage->getPostValue('Email');

$oPage->includeJavaScript('js/jquery.validate.js');
$oPage->addJavascript('$("#PassworRecovery").validate({
  rules: {
    Email: {
      required: true,
      email: true
    }
  }
});', null, true);

if ($emailTo) {
    $oPage->takemyTable();
    $userEmail = $oPage->easeAddSlashes($emailTo);
    $userFound = $oPage->dblink->queryToArray('SELECT id,login FROM user WHERE email=\''.$userEmail.'\'');
    if (count($userFound)) {
        $userID = intval($userFound[0]['id']);
        $userLogin = $userFound[0]['login'];
        $newPassword = $oPage->randomString(8);

        $PassChanger = new EaseUser($userID);
        $PassChanger->passwordChange($newPassword);

        $email = $oPage->addItem(new EaseMail($userEmail, 'FlexiHuBee - '._('Nové heslo pro').' '.$_SERVER['SERVER_NAME']));
        $email->setMailHeaders(['From' => constant('EMAIL_FROM')]);
        $email->addItem(_("Vaše přihlašovací údaje byly změněny:\n"));

        $email->addItem(' Login: '.$userLogin."\n");
        $email->addItem(' Heslo: '.$newPassword."\n");

        $email->send();

        $oUser->addStatusMessage('Tvoje nové heslo vám bylo odesláno mailem na zadanou adresu <strong>'.$_REQUEST['Email'].'</strong>');
        $success = true;
    } else {
        $oUser->addStatusMessage('Promiňnte, ale email <strong>'.$_REQUEST['Email'].'</strong> nebyl v databázi nalezen', 'warning');
    }
} else {
    $oUser->addStatusMessage(_('Zadejte prosím váš eMail.'));
}

$oPage->addItem(new ui\PageTop(_('Obnova zapomenutého hesla')));

$row = $oPage->container->addItem(new \Ease\Html\Div(null, ['class' => 'row']));
$columnI = $row->addItem(new \Ease\Html\Div(null, ['class' => 'col-md-4']));
$columnII = $row->addItem(new \Ease\Html\Div(null, ['class' => 'col-md-4']));
$columnIII = $row->addItem(new \Ease\Html\Div(null, ['class' => 'col-md-4']));

if (!$success) {
    $columnI->addItem('<h1>Zapoměl jsem své heslo!</h1>');

    $columnIII->addItem(_('Zapoměl jste heslo? Vložte svou e-mailovou adresu, kterou jste zadal při registraci a my Vám pošleme nové.'));

    $emailForm = $columnII->addItem(new \Ease\TWB\Form('PassworRecovery'));
    $emailForm->addItem(new EaseLabeledTextInput('Email', null, _('Email'), ['size' => '40', 'class' => 'form-control']));
    $emailForm->addItem(new EaseTWSubmitButton(_('Zaslat nové heslo')));

    if ($oPage->isPosted()) {
        $emailForm->fillUp($_POST);
    }
} else {
    $columnII->addItem(new \Ease\TWB\LinkButton('login.php', _('Pokračovat')));
    $oPage->redirect('login.php');
}

$oPage->addItem(new ui\PageBottom());

$oPage->draw();
