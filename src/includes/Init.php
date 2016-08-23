<?php
/**
 * FlexiHuBee - Init aplikace.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */

namespace FlexiHuBee;

require_once 'includes/config.php';
require_once '../vendor/autoload.php';

//Initialise Gettext
$langs  = [
    'en_US' => ['en', 'English (International)'],
    'cs_CZ' => ['cs', 'Česky (Čeština)'],
];
$locale = 'en_US';
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $locale = \locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE']);
}
if (isset($_GET['locale'])) {
    $locale = preg_replace('/[^a-zA-Z_]/', '', substr($_GET['locale'], 0, 10));
}
foreach ($langs as $code => $lang) {
    if ($locale == $lang[0]) {
        $locale = $code;
    }
}
setlocale(LC_ALL, $locale);
bind_textdomain_codeset('systemsn', 'UTF-8');
putenv("LC_ALL=$locale");
if (file_exists('../i18n')) {
    bindtextdomain('systemsn', '../i18n');
}
textdomain('systemsn');

/*
  if (file_exists(LMS_DIRECTORY . 'lib/LMSDB.php')) {
  require_once LMS_DIRECTORY . 'lib/LMSDB.php';
  } else {
  die(_('Není definována cesta k LMS. Pomůže: #dpkg-reconfigure flexihubee'));
  }
  $DB = DBInit('mysql', DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD, DB_DATABASE);
  require_once LMS_DIRECTORY . 'lib/Session.class.php';
  $SESSION = new Session($DB, 9000);
 */

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