<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace FlexiHuBee\ui;

/**
 * Description of FlexiBeeInstanceStatus
 *
 * @author vitex
 */
class FlexiBeeInstanceStatus extends \Ease\TWB\Container
{

    public function __construct($engine)
    {
        parent::__construct();
        $this->addItem($this->permission($this->checkWritePermission($engine)));
        $this->addItem($this->webhook($this->checkWebhookPresence($engine)));
        $this->addItem($this->labels($this->checkLabelsPresence($engine)));
    }

    public function permission($status)
    {
        $permissonRow = new \Ease\TWB\Row();
        $permissonRow->addColumn(1, new \Ease\ui\SemaforLight($status));
        $permissonRow->addColumn(4, _('Write Permission'));
        return $permissonRow;
    }

    public function webhook($status)
    {
        $webhookRow = new \Ease\TWB\Row();
        $webhookRow->addColumn(1, new \Ease\ui\SemaforLight($status));
        $webhookRow->addColumn(4, _('WebHook Presence'));
        return $webhookRow;
    }

    public function labels($status)
    {
        $labelsRow = new \Ease\TWB\Row();
        $labelsRow->addColumn(1, new \Ease\ui\SemaforLight($status));
        $labelsRow->addColumn(4, _('Labels Defined'));
        return $labelsRow;
    }

    public function checkWritePermission($engine)
    {
        $prober   = new \FlexiPeeHP\Adresar(null, $engine->getData());
        $firstRaw = $prober->getColumnsFromFlexibee(['id'], ['limit' => 1]);
        $prober->insertToFlexiBee(['id' => $firstRaw[0]['id']]);
        return $prober->lastResponseCode == 201;
    }

    public function checkWebhookPresence($engine)
    {
        $result = 'danger';
        $hooker = new \FlexiPeeHP\Hooks(null, $engine->getData());
        $myHook = \FlexiHuBee\FlexiBees::webHookUrl($engine->getMyKey());
        $hooks  = $hooker->getColumnsFromFlexibee(['url', 'penalty'], [], 'url');
        if (array_key_exists($myHook, $hooks)) {
            $result = ($hooks[$myHook]['penalty'] == 0) ? 'success' : 'warning';
        }
        return $result;
    }

    public function checkLabelsPresence($engine)
    {
        $labeler = new \FlexiPeeHP\Stitek(null, $engine->getData());
        $labeler->setKeyColumn('id');
        $labeler->ignore404(true);
        return $labeler->recordExists(['kod' => 'FLEXIHUBEE']);
    }
}
