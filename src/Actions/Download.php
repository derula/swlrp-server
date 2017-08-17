<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Application;

class Download extends Action {
    private $dir;
    public function __construct(array $data, Application $application) {
        $this->dir = $application->getRoot() . '/public/downloads';
    }
    public function execute() {
        $dir = new \FilesystemIterator($this->dir, \FilesystemIterator::SKIP_DOTS);
        $newestTime = 0;
        $newestFile = null;
        foreach ($dir as $fileInfo) {
            if ($fileInfo->isDir()) {
                continue;
            }
            $time = $fileInfo->getMTime();
            if ($time > $newestTime) {
                $newestTime = $time;
                $newestFile = $fileInfo->getBasename();
            }
        }
        if (!isset($newestFile)) {
            http_response_code(404);
            return;
        }
        header('Location: /downloads/' . $newestFile);
    }
}
