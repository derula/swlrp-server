<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Models\Profile;

/**
 * @method Profile getModel()
 */
class SaveProfile extends Action {
    const MAX_LENGTH = ['properties' => 40, 'texts' => 40000];
    public function execute() {
        $data = $this->getData();
        $saveData = [];
        $hpConfig = \HTMLPurifier_Config::createDefault();
        $hpConfig->set('HTML.Doctype', 'HTML 4.01 Transitional');
        $attrDef = $hpConfig->getHTMLDefinition(true);
        $attrDef->addAttribute(
            'p', 'style', new \HTMLPurifier_AttrDef_Enum(['direction: rtl;'])
        );
        $hp = new \HTMLPurifier($hpConfig);
        foreach ($this->iterateMetaData() as $type => $prop) {
            $name = $prop['name'];
            if (!isset($data[$name])) {
                continue;
            }
            $value = mb_substr($data[$name], 0, self::MAX_LENGTH[$type] ?? 20);
            if ('texts' === $type) {
                $value = $hp->purify($value);
            }
            $saveData[$name] = $value;
        }
        $id = $this->getSession()->getCharacterId();
        $this->getModel()->saveProperties($id, $saveData);
    }
}
