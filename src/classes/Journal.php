<?php
/**
 * FlexiHuBee - FlexiBee instances.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015-2016 Vitex Software
 */

namespace FlexiHuBee;

/**
 * Description of flexibees
 *
 * @author vitex
 */
class Journal extends \Ease\Brick
{
    /**
     * We Work With Table
     * @var string
     */
    public $myTable = 'journal';

    /**
     * Column with record create time
     * @var string
     */
    public $myCreateColumn = 'DatCreate';

    /**
     * Column with last record upadate time
     * @var string
     */
    public $myLastModifiedColumn = 'DatSave';

}
