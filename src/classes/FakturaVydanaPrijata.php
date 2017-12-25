<?php
/**
 * FlexiHuBee - Outcoming to incoming invoice convertor.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015-2016 Vitex Software
 */

namespace FlexiHuBee;

/**
 * Description of FakturaVydanaPrijata
 *
 * @author vitex
 */
class FakturaVydanaPrijata extends \FlexiPeeHP\FakturaPrijata
{

    /**
     * Convert data from outcoming to incoming invoice.
     *
     * @param array $data \FlexiPeeHP\FakturaVydana->getData()
     * @return int number of taken columns
     */
    public function takeData($data)
    {
        $incoming = [];

        $this->divDataArray($data, $incoming, 'id');
        $this->divDataArray($data, $incoming, 'kod'); //Interní číslo
        $this->divDataArray($data, $incoming, 'datVyst'); //Dat.přijetí
        $this->divDataArray($data, $incoming, 'typDokl');
        $this->divDataArray($data, $incoming, 'generovatSkl');
        $this->divDataArray($data, $incoming, 'zdrojProSkl');
        $this->divDataArray($data, $incoming, 'dobropisovano');
        $this->divDataArray($data, $incoming, '');
        $this->divDataArray($data, $incoming, '');
        $this->divDataArray($data, $incoming, '');
        return parent::takeData($incoming);
    }

    public function getIDValue()
    {
        $parts = explode(':', $this->getDataValue('id'));
        return (int) end($parts);
    }

    static public function unsetValue($column, &$data)
    {
        unset($data[$column]);
        foreach ($data as $colname => $value) {
            if (substr($colname, 0, strlen($column.'@')) === $column.'@') {
                unset($data[$colname]);
            }
        }
    }

}
