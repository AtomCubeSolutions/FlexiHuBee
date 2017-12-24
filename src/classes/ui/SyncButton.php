<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FlexiHuBee\ui;

/**
 * Description of SyncButton
 *
 * @author vitex
 */
class SyncButton extends \Ease\TWB\LinkButton
{

    public function __construct()
    {
        $instances = \Ease\Shared::db()->getNumRows('SELECT id FROM flexibees');
        
        if($instances > 1){
            parent::__construct('sync.php' ,_('Synchronise now'), 'success' );
            
        } else {
            parent::__construct('flexibee.php',_('Add FlexiBee Instance'),'default');
        }
        
    }
}
