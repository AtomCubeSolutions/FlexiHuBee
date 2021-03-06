<?php
/**
 * FlexiHuBee - WebPage.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */

namespace FlexiHuBee\ui;

class WebPage extends \Ease\TWB\WebPage
{
    /**
     * Page's main block.
     *
     * @var \Ease\Html\DivTag
     */
    public $container = null;

    /**
     * First column.
     *
     * @var \Ease\Html\DivTag
     */
    public $columnI = null;

    /**
     * Second column.
     *
     * @var \Ease\Html\DivTag
     */
    public $columnII = null;

    /**
     * Third column.
     *
     * @var \Ease\Html\DivTag
     */
    public $columnIII = null;

    /**
     * Základní objekt stránky.
     *
     * @param VSUser $userObject
     */
    public function __construct($pageTitle = null, &$userObject = null)
    {
        if (is_null($userObject)) {
            $userObject = \Ease\Shared::user();
        }
        parent::__construct($pageTitle, $userObject);
        $this->IncludeCss('css/default.css');
        $this->head->addItem('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
        $this->head->addItem('<link rel="shortcut icon" type="image/svg+xml" href="images/logo.png">');
        $this->head->addItem('<link rel="apple-touch-icon-precomposed"  type="image/svg+xml" href="images/logo.png">');
        $this->head->addItem('<link rel="stylesheet" href="/javascript/font-awesome/css/font-awesome.min.css">');
        $this->addCss('body { background-image: url("images/logo-big.png"); background-repeat: no-repeat; background-position: 4px 50px; }');

        $this->container = $this->addItem(new \Ease\TWB\Container('<p></p>'));
    }

    /**
     * Divide page to three columns layout
     */
    function addPageColumns()
    {
        $row             = $this->container->addItem(new \Ease\TWB\Row());
        $this->columnI   = $row->addItem(new \Ease\TWB\Col(4));
        $this->columnII  = $row->addItem(new \Ease\TWB\Col(4));
        $this->columnIII = $row->addItem(new \Ease\TWB\Col(4));
    }

    /**
     * Only for admin.
     *
     * @param string $loginPage
     */
    public function onlyForAdmin($loginPage = 'login.php', $message = null)
    {
        if (!$this->user->getSettingValue('admin')) {
            \Ease\Shared::user()->addStatusMessage(empty($message) ? _('Sign in as admin first')
                        : $message, 'warning');
            $this->redirect($loginPage);
            exit;
        }
    }

    /**
     * Redirect anonymous user to login page
     *
     * @param string $loginPage login page address
     */
    public function onlyForLogged($loginPage = 'login.php', $message = null)
    {
        return parent::onlyForLogged($loginPage.'?backurl='.urlencode($_SERVER['REQUEST_URI']),
                $message);
    }
}
