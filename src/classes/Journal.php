<?php
/**
 * FlexiHuBee - Journal dispatcher.
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

    /**
     * Save latest record id for evidence
     *
     * @param int    $instanceId ID from table flexibees
     * @param string $evidence   Code of FlexiBee evidence
     * @param int    $topId      ID of currently synced record
     */
    public function updateEvidence($instanceId, $evidence, $topId)
    {
        $this->dblink->exeQuery('UPDATE '.$this->getMyTable().' SET topid='.$topId.' WHERE instance='.$instanceId.' AND evidence LIKE '.$evidence);
        if (!$this->dblink->numRows) {
            $this->dblink->arrayToInsert(['topid' => $topId, 'instance' => $instanceId
                , 'evidence' => $evidence]);
        }
    }


    /**
     * Obtain latest number of record for given evidence in FlexiBee instance
     *
     * @param int $instanceId
     * @param string $evidence
     * @return int
     */
    public function getTopID($instanceId, $evidence)
    {
        return (int) $this->dblink->queryToValue('SELECT topid FROM '.$this->getMyTable().' WHERE instance='.$instanceId.' AND evidence LIKE \''.$evidence.'\'');
    }
}