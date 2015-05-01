<?php

use Storyplayer\TestEnvironments\Vagrant_GroupAdapter;
use Storyplayer\TestEnvironments\Vagrant_VirtualboxHostAdapter;
use Storyplayer\TestEnvironments\CentOS_6_HostAdapter;
use Storyplayer\TestEnvironments\Dsbuild_Adapter;

$testEnv = newTestEnvironment();

$group1 = $testEnv->newGroup(new Vagrant_GroupAdapter);
$group1->newHost('default')
       ->setOperatingSystem(new CentOS_6_HostAdapter)
       ->setHostAdapter(new Vagrant_VirtualboxHostAdapter)
       ->setRoles([
            "host_target",
            "upload_target",
            "ssl_target",
            "zmq_target",
        ])
       ->setStorySettings((object)[
            "host" => (object)[
                "expected" => "successfully retrieved this storySetting :)",
            ],
            "http" => (object)[
                "homepage" => "https://storyplayer.test/",
            ],
            "user" => (object)[
                "username" => "vagrant",
                "group"    => "vagrant",
            ],
            "zmq" => (object)[
                "single" => (object)[
                    "inPort"  => 5000,
                    "outPort" => 5001,
                ],
                "multi"  => (object)[
                    "inPort"  => 5002,
                    "outPort" => 5003,
                ],
            ]
        ]);

$prov1 = new Dsbuild_Adapter();
$prov1->setExecutePath("dsbuild.sh");
$group1->addProvisioningAdapter($prov1);

$testEnv->setModuleSettings((object)[
    "http" => (object)[
        "validateSsl" => false,
    ],
]);

// all done
return $testEnv;