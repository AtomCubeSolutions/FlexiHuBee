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
class FlexiBees extends Engine
{
    public $keyword = 'flexibee';

    /**
     * Column with name of record
     * @var string
     */
    public $nameColumn = 'name';

    /**
     * We Work With Table
     * @var string
     */
    public $myTable = 'flexibees';

    /**
     * Column with record create time
     * @var string
     */
    public $myCreateColumn = 'DatCreate';

    /**
     * Filter Input data
     *
     * @param array $data
     * @return int data taken count
     */
    public function takeData($data)
    {
        unset($data['class']);
        if (isset($data['id']) && !strlen($data['id'])) {
            unset($data['id']);
        }
        if (isset($data['rw'])) {
            $data['rw'] = true;
        } else {
            $data['rw'] = false;
        }
        return parent::takeData($data);
    }
}