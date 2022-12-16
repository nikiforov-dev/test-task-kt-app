<?php

namespace App\DependencyInjection\Trait;

use Psr\Log\LoggerInterface;

trait LoggerDependencyInjectionTrait
{
    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @required
     *
     * @param LoggerInterface $logger
     *
     * @return $this
     */
    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }
}