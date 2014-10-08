<?php

namespace spec\Updater\Tools\Composer;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Output\NullOutput;
use Updater\Service\PackageService;
use Updater\Updater;
use Updater\Tools\Json\JsonManager;

class ComposerManagerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Updater\Tools\Composer\ComposerManager');
    }

    public function it_runs_composer_action()
    {
        /*$output = new NullOutput();
        $updater = new Updater();
        $updater->setPackageService(new PackageService())
            ->setWorkingDir(__DIR__ . '/../../../../');

        $schema = file_get_contents(realpath(__DIR__ . '/../../../../schema/') . '/updater-schema.json');
        $jsonManager = new JsonManager();
        //$diffFile = $jsonManager->getJsonFromFile('update.json', $packageDir);
        $packageJson = json_decode($schema);

        //$jsonManager->getFileContent($reference, realpath(__DIR__ . '/../../../../schema/') . '/updater-schema.json')
        $packageService = $updater->getPackageService();

        $package = $packageService->fillPackage($schema);
        $this->runComposer($package->getComposerAction(), $updater->getWorkingDir(), $output);*/
    }

    public function it_makes_composer_self_update()
    {
        $output = new NullOutput();
        $this->selfUpdate(__DIR__ . '/../../../../', $output);
    }
}
