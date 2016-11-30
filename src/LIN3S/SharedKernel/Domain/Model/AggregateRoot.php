<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
abstract class AggregateRoot
{
    private $recordedEvents = [];

    public function recordedEvents()
    {
        return $this->recordedEvents;
    }

    public function clearEvents()
    {
        $this->recordedEvents = [];
    }

    protected function publish(DomainEvent $event)
    {
        $this->apply($event);
        $this->record($event);
    }

    protected function apply(DomainEvent $event)
    {
        $modifier = 'apply' . array_reverse(explode('\\', get_class($event)))[0];
        if (!method_exists($this, $modifier)) {
            return;
        }
        $this->$modifier($event);
    }

    private function record(DomainEvent $event)
    {
        $this->recordedEvents[] = $event;
    }
}