<?php

namespace Incertitude\SWLRP\Actions;

use Incertitude\SWLRP\Action;
use Incertitude\SWLRP\Application;
use Incertitude\SWLRP\Exceptions\ProfileNotFound;

class Download extends Action {
    private $dir;
    public function __construct(array $data, Application $application) {
        $this->dir = $application->getRoot() . '/public/downloads';
    }
    public function execute() {
        $newestTime = 0;
        $newestFile = null;
        try {
            $dir = new \FilesystemIterator($this->dir, \FilesystemIterator::SKIP_DOTS);
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
        } catch (\UnexpectedValueException $e) {
        }
        if (!isset($newestFile)) {
            throw new ProfileNotFound();
        }
        header('Location: /downloads/' . $newestFile);
    }
}
