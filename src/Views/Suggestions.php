<?php

namespace Incertitude\SWLRP\Views;

use Incertitude\SWLRP\View;

class Suggestions extends View {
    public function render(): string {
        $term = $this->getData('term');
        if (!empty($term)) {
            return json_encode($this->getModel()->suggestPropertyValues($this->getData(0), $term));
        }
    }
}
