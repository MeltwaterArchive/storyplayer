<?php

use Storyplayer\TestEnvironments\Vagrant_GroupAdapter;
use Storyplayer\TestEnvironments\Vagrant_VirtualboxHostAdapter;
use Storyplayer\TestEnvironments\CentOS_7_HostAdapter;
use Storyplayer\TestEnvironments\Dsbuild_Adapter;

$testEnv = newTestEnvironment();

$group1 = $testEnv->newGroup('vagrant', new Vagrant_GroupAdapter);
$group1->newHost('default', new Vagrant_VirtualboxHostAdapter)
       ->setOperatingSystem(new CentOS_7_HostAdapter);

$testEnv->setModuleSettings((object)[
    "http" => (object)[
        "validateSsl" => false,
    ],
]);

// all done
return $testEnv;