<?php

namespace Laravel\Forge\Services\Commands;

class UninstallServiceCommand extends ServiceCommand
{
    /**
     * @{inheritdoc}
     */
    public function command()
    {
        return 'remove';
    }

    /**
     * @{inheritdoc}
     */
    public function runnable()
    {
        return $this->getService()->uninstallable();
    }
}