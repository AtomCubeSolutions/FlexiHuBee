<?php
/**
 * FlexiHuBee - Synchronization engine.
 *
 * @author     Vítězslav Dvořák <vitex@arachne.cz>
 * @copyright  2015-2016 Vitex Software
 */

namespace FlexiHuBee;

/**
 * Description of Syncer
 *
 * @author vitex
 */
class Syncer extends \Ease\Atom
{

    public function doSync()
    {
        $flexibees   = new FlexiBees();
        $fbInstances = $flexibees->getColumnsFromSQL('*', null, 'id', 'id');

        if (count($fbInstances)) {
            foreach ($fbInstances as $companyInfo) {
                $invoicesToSave = $this->obtainInvoicesForMe($companyInfo,
                    $fbInstances);
                $this->saveInvoicesForMe($companyInfo, $invoicesToSave);
            }
            //1) Sahni do VitexSoftware pro faktury Spoje
            //2) Uloz je do Spoje
        }
    }

    /**
     * Obtain all invoices for $me from each $others
     *
     * @param array $me     FlexiBee connection info
     * @param array $others FlexiBee instances to check for invoices for me
     * @return array
     */
    public function obtainInvoicesForMe($me, $others)
    {
        $invoices = [];
        foreach ($others as $flexiBee) {
            if ($me['ic'] == $flexiBee['ic']) {
                continue;
            }
            $invoicer      = new \FlexiPeeHP\FakturaVydana(null, $flexiBee);
            $invoicesForMe = $invoicer->getColumnsFromFlexibee('*',
                ['ic' => $me['ic']]); //+ newer than last saved
            if (($invoicer->lastResponseCode == 200) && count($invoicesForMe)) {
                $invoices = array_merge($invoices, $invoicesForMe);
            } else {
                $this->addStatusMessage(sprintf(_('Could not obtain invoices forom %s'),
                        $flexiBee['name']), 'warning');
            }
        }
        return $invoices;
    }

    /**
     * Save importred invoices to target FlexiBee
     *
     * @param array $me Connection details for target FlexiBee
     * @param array $invoicesToSave array of records to save
     */
    public function saveInvoicesForMe($me, $invoicesToSave)
    {
        $invoicer = new \FlexiPeeHP\FakturaPrijata(null, $me);
        foreach ($invoicesToSave as $invoiceToSave) {
            $invoicer->takeData($invoiceToSave);
            if ($invoicer->saveToFlexiBee() && $this->saveLastRecordNumber($me['id'],
                    'faktura-prijata', $invoicer->getDataValue('id'))) {
                $this->addStatusMessage(_('Invoice Imported'), 'success');
            } else {
                $this->addStatusMessage(_('Invoice Import error'), 'error');
            }
        }
        //Save Invoices
        //Save top record numbers
    }

    /**
     * Keep last imported record id for FlexiBees and evidencies
     *
     * @param int    $instanceId
     * @param string $evidence
     * @param int    $lastId
     * @return boolean
     */
    public function saveLastRecordNumber($instanceId, $evidence, $lastId)
    {
        $journaler = new Journal();
        return $journaler->updateEvidence($instanceId, $evidence, $lastId);
    }

}
