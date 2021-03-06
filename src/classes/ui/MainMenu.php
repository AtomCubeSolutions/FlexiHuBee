<?php
/**
 * FlexiHuBee - Hlavní menu.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015 Vitex Software
 */

namespace FlexiHuBee\ui;

class MainMenu extends \Ease\Html\DivTag
{

    /**
     * Vytvoří hlavní menu.
     */
    public function __construct()
    {
        parent::__construct(null, ['id' => 'MainMenu']);
        $this->addItem('');
    }

    /**
     * Data source.
     *
     * @param type   $source
     * @param string $icon   Description
     *
     * @return string
     */
    protected function getMenuList($source, $icon = '')
    {
        $keycolumn  = $source->getkeyColumn();
        $namecolumn = $source->nameColumn;
        $lister     = $source->getColumnsFromSQL([$source->getkeyColumn(), $namecolumn],
            null, $namecolumn, $keycolumn);

        $itemList = [];
        if ($lister) {
            foreach ($lister as $uID => $uInfo) {
                $itemList[$source->keyword.'.php?'.$keycolumn.'='.$uInfo[$keycolumn]]
                    = \Ease\TWB\Part::GlyphIcon($icon).'&nbsp;'.$uInfo[$namecolumn];
            }
        }

        return $itemList;
    }

    /**
     * Insert menu.
     */
    public function afterAdd()
    {
        $nav = $this->addItem(new BootstrapMenu());

        $userID = \Ease\Shared::user()->getUserID();
        if ($userID) { //Authenticated user
            $nav->addDropDownMenu('<img width=30 src=images/flexibee-logo.png> '._('FlexiBee instances'),
                array_merge([
                'flexibee.php' => \Ease\TWB\Part::GlyphIcon('plus').' '._('New Instance'),
//                'flexibees.php' => \Ease\TWB\Part::GlyphIcon('list').'&nbsp;'._('Instance list'),
                '' => '',
                    ], $this->getMenuList(new \FlexiHuBee\FlexiBees(), 'name'))
            );



//            $nav->addDropDownMenu('<img width=30 src=images/profits_150.png> '._('Importy'),
//                array(
//                'storage2flexibee.php' => \Ease\TWB\Part::GlyphIcon('plus').' '._('Sklad do FlexiBee'),
//                'invoice2flexibee.php' => \Ease\TWB\Part::GlyphIcon('plus').'&nbsp;'._('Faktury do Flexibee'),
//                'address2flexibee.php' => \Ease\TWB\Part::GlyphIcon('plus').'&nbsp;'._('Adresář do Flexibee'),
//                )
//            );
//            $nav->addDropDownMenu('<img width=30 src=img/contract_150.png> '._('Smlouvy'),
//                array_merge(array(
//                'contract.php' => \Ease\TWB\Part::GlyphIcon('plus').' '._('Nová smlouva'),
//                'contracts.php' => \Ease\TWB\Part::GlyphIcon('list').'&nbsp;'._('Přehled smluv'),
//                'rspcntrcts.php' => \Ease\TWB\Part::GlyphIcon('user').'&nbsp;'._('Respondenti'),
//                ))
//            );
            $nav->addDropDownMenu('<img width=30 src=images/users_150.png> '._('Users'),
                array_merge([
                'createaccount.php' => \Ease\TWB\Part::GlyphIcon('plus').' '._('New   '),
                'users.php' => \Ease\TWB\Part::GlyphIcon('list').'&nbsp;'._('Přehled uživatelů'),
                '' => '',
                    ], $this->getMenuList(\Ease\Shared::user(), 'user'))
            );


            $nav->addMenuItem(new SyncButton());
        }
    }

    /**
     * Přidá do stránky javascript pro skrývání oblasti stavových zpráv.
     */
    public function finalize()
    {
        $this->addCss('body {
                padding-top: 60px;
                padding-bottom: 40px;
            }');

        \Ease\JQuery\Part::jQueryze($this);
        \Ease\Shared::webPage()->addCss('.dropdown-menu { overflow-y: auto } ');
        \Ease\Shared::webPage()->addJavaScript("$('.dropdown-menu').css('max-height',$(window).height()-100);",
            null, true);
        $this->includeJavaScript('js/slideupmessages.js');
    }
}