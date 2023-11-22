<?php

namespace App\Message;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

class ImportMEPHandler extends AsMessageHandler
{
    public function __invoke(ImportMEPMessage $message, KernelInterface $kernel)
    {
        // Implement MEP import logic here
        $application = new Application($kernel);
        $application->setAutoExit(false);
        try {
            $application->run(['command' => 'app:import-mep-messanger'], new NullOutput());
        } catch (\Exception $e) {
        }
    }
}