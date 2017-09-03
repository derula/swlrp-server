#!/usr/bin/env php
<?php
use Incertitude\SWLRP\Application;

function run($command) {
    $status = null;
    passthru($command, $status);
    if ($status) {
        die("Couldn't execute '$command'. Deployment canceled.\n");
    }
}
function get_remote_runner($ssh, $dir) {
    return function ($command, $return = false) use ($ssh, $dir) {
        $stream = ssh2_exec($ssh, "cd $dir;" . $command . ' 2>&1;echo "#$?#"');
        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);
        fclose($stream);
        preg_match('/#(-?\d+)#\Z/', $output, $status);
        $output = trim(preg_replace("/#$status[1]#\Z/", '', $output));
        $isError = ('0' !== $status[1]);
        if (!$return) {
            echo $output;
            if (!empty($output)) {
                echo "\n";
            }
        } elseif (!$isError) {
            return $output;
        }
        if ($isError) {
            die("Couldn't execute '$command' on the server. Deployment canceled.\n");
        }
    };
}

$baseDir = dirname(__DIR__);
require $baseDir . '/vendor/autoload.php';
$config = (new Application($baseDir))->getConfig();
$options = getopt('e::', ['environment::']);
$environment = array_pop($options) ?: $config->get('Deployment', 'default');
if (!$config->exists('Deployment', $environment)) {
    die("Fatal: environment '$environment' doesn't exist.\n");
}
chdir($baseDir);
$version = trim(`git describe --tags`);
$confirm = readline("Deploy version $version to environment $environment? [y/N] ");
if ('y' !== strtolower($confirm)) {
    die("Deployment canceled by the user.\n");
}
$remote = $config->get('Deployment', $environment, 'remote');
if (empty($remote['url'])) {
    die("Remote URL missing in environment $environment.\n");
}
run('composer install');
run('scripts/transpile_js.php');
$ssh = ssh2_connect($remote['url'], (int)($remote['port'] ?? 22));
if (!isset($remote['fingerprint']['value'])) {
    echo "WARNING! No expected fingerprint provided! Identity of the server cannot be validated!\n";
} else {
    $format = strtolower($remote['fingerprint']['format']);
    // Read ssh-keygen compatible fingerprint depending on the format.
    if ('sha1' === $format) {
        $opts = SSH2_FINGERPRINT_SHA1 | SSH2_FINGERPRINT_RAW;
        $fingerprint = rtrim(base64_encode(ssh2_fingerprint($ssh, $opts)), '=');
    } elseif ('md5' === $format) {
        $fingerprint = implode(':', str_split(strtolower(ssh2_fingerprint($ssh)), 2));
    } else {
        die("Invalid fingerprint format '$format'.\n");
    }
    if ($fingerprint !== $remote['fingerprint']['value']) {
        die("Fingerprint doesn't match expected value. Deployment canceled.\n");
    }
}
if (!@ssh2_auth_agent($ssh, $remote['user'] ?? 'root')) {
    die("Authentification failed. Please ensure ssh-agent is running and the key necessary for connecting to $remote[url] is registered.\n");
}
$runner = get_remote_runner($ssh, $remote['path'] ?? '.');
$remoteVersion = $runner('git describe --tags', true);
echo "Replacing $remoteVersion with $version.\n";
$runner('git fetch auto');
$runner("git checkout $version");
$composer = $config->get('Deployment', $environment, 'composer') ?? [];
$runner(($composer['binary'] ?? 'composer') . ' install ' . ($composer['args'] ?? ''));
foreach (glob($baseDir . '/public/assets/*.compat.js') as $file) {
    $base = basename($file);
    if (!@ssh2_scp_send($ssh, $file, $remote['path'] . '/public/assets/' . $base)) {
        die("Failed deploying $base to $environment. Please fix or roll back manually. Selected version remains checked out.\n");
    }
}
$phpBinary = $config->get('Deployment', $environment, 'php', 'binary') ?? '';
$runner($phpBinary . ' scripts/refresh_properties.php');
ssh2_exec($ssh, 'exit');
