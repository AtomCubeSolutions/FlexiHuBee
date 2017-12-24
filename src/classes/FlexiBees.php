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
     * Column with last record upadate time
     * @var string
     */
    public $myLastModifiedColumn = 'DatSave';

    /**
     * Filter Input data
     *
     * @param array $data
     * @return int data taken count
     */
    public function takeData($data)
    {
        unset($data['class']);
        if (isset($data['id'])) {
            if (!strlen($data['id'])) {
                unset($data['id']);
            } else {
                $data['id'] = intval($data['id']);
            }
        }
        if (isset($data['rw'])) {
            $data['rw'] = true;
        } else {
            $data['rw'] = false;
        }
        if (isset($data['webhook'])) {
            $data['webhook'] = true;
        } else {
            $data['webhook'] = false;
        }
        $result = parent::takeData($data);
        if (array_key_exists('name', $data) && !strlen($data['name'])) {
            $this->addStatusMessage(_('Instance name cannot be empty'),
                'warning');
            $result = false;
        }
        if (array_key_exists('url', $data) && !strlen($data['url'])) {
            $this->addStatusMessage(_('FlexiBee API URL cannot be empty'),
                'warning');
            $result = false;
        }
        if (array_key_exists('user', $data) && !strlen($data['user'])) {
            $this->addStatusMessage(_('User name cannot be empty'), 'warning');
            $result = false;
        }
        if (array_key_exists('password', $data) && !strlen($data['password'])) {
            $this->addStatusMessage(_('API User password cannot be empty'),
                'warning');
            $result = false;
        }
        if (array_key_exists('company', $data) && !strlen($data['company'])) {
            $this->addStatusMessage(_('Company code cannot be empty'), 'warning');
            $result = false;
        }

        return $result;
    }

    /**
     * Obtain link to FlexiBee webserver
     *
     * @return string
     */
    function getLink()
    {
        return $this->getDataValue('url').'/c/'.$this->getDataValue('company');
    }

    /**
     * Get Copany Identification number, establish webhook and save
     *
     * @param array $data
     * @param boolean $searchForID
     * @return int result
     */
    public function saveToSQL($data = null, $searchForID = false)
    {
        if (is_null($data)) {
            $data = $this->getData();
        }
        if (!isset($data['ic'])) {
            $flexiBeeData = new \FlexiPeeHP\Nastaveni(1, $data);
            $ic           = $flexiBeeData->getDataValue('ic');
            if (strlen($ic)) {
                $data['ic'] = intval($ic);
                $this->addStatusMessage(sprintf(_('Succesfully obtained organisation identification number #%d from FlexiBee %s'),
                        $data['ic'], $data['name']), 'success');
            } else {
                $this->addStatusMessage(sprintf(_('Cannot obtain organisation identification number for FlexiBee %s'),
                        $data['name']), 'error');
            }
        }
        return parent::saveToSQL($data, $searchForID);
    }

    public function prepareRemoteFlexiBee()
    {
        if ($this->getDataValue('rw')) {
            $this->addFlexiHUBeeLabels();
            if ($this->changesApi(true)) {
                $this->registerWebHook(self::webHookUrl($this->getMyKey()));
            }
        }
    }

    /**
     * WebHook url for Given ID of FlexiBee instance
     * 
     * @param int $instanceId
     * 
     * @return string URL for WebHook
     */
    public static function webHookUrl($instanceId)
    {
        $baseUrl    = \Ease\Page::phpSelf();
        $urlInfo    = parse_url($baseUrl);
        $curFile    = basename($urlInfo['path']);
        $webHookUrl = str_replace($curFile,
            'webhook.php?instanceid='.$instanceId, $baseUrl);
        return $webHookUrl;
    }

    public function addFlexiHUBeeLabels()
    {
        $result        = true;
        $evidenceToVsb = array_flip(\FlexiPeeHP\Stitek::$vsbToEvidencePath);
        /**
         * @var \FlexiPeeHP\Stitek Label Object
         */
        $stitek        = new \FlexiPeeHP\Stitek(null, $this->getData());
        /**
         * @var string Name / Code of new Label
         */
        $label         = 'FlexiHUBee';
        /**
         * @see https://demo.flexibee.eu/c/demo/stitek/properties
         * @var array initial Label contexts
         */
        $stitekData    = [
            "kod" => strtoupper($label),
            "nazev" => $label,
            $evidenceToVsb['adresar'] => true,
            $evidenceToVsb['cenik'] => true,
            $evidenceToVsb['faktura-vydana'] => true,
            $evidenceToVsb['faktura-prijata'] => true,
            $evidenceToVsb['objednavka-vydana'] => true,
            $evidenceToVsb['objednavka-prijata'] => true,
        ];

        $stitekID = $stitek->getColumnsFromFlexibee('id', $stitekData);

        if (!isset($stitekID[0]['id'])) {
            $stitek->insertToFlexiBee($stitekData);
            if ($stitek->lastResponseCode == 201) {
                $stitek->addStatusMessage(sprintf(_('label %s created'), $label),
                    'success');
            } else {
                $result = false;
            }
        }

        return $result;
    }

    public function registerWebHook($hookurl)
    {
        $format     = 'json';
        $hooker     = new \FlexiPeeHP\Hooks(null, $this->getData());
        $hooker->setDataValue('skipUrlTest', 'true');
        $hookResult = $hooker->register($hookurl, $format);
        if ($hookResult) {
            $hooker->addStatusMessage(sprintf(_('Hook %s was registered'),
                    $hookurl), 'success');
            $hookurl = '';
        } else {
            $hooker->addStatusMessage(sprintf(_('Hook %s not registered'),
                    $hookurl), 'warning');
        }
    }

    /**
     * Eanble Or disble ChangesAPI
     * 
     * @param boolean $enable requested state
     * 
     * @return boolean
     */
    public function changesApi($enable)
    {
        $changer     = new \FlexiPeeHP\Changes(null, $this->getData());
        $chapistatus = $changer->getStatus();
//        $globalVersion = $changer->getGlobalVersion();

        if ($enable === true) {
            if ($chapistatus === FALSE) {
                $changer->enable();
                $changer->addStatusMessage(_('ChangesAPI was enabled'),
                    'success');
                $chapistatus = true;
            }
        } else {
            if ($chapistatus === TRUE) {
                $changer->disable();
                $changer->addStatusMessage(_('ChangesAPI was disabled'),
                    'warning');
                $chapistatus = false;
            }
        }
        return $chapistatus;
    }
}
