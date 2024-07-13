<?php

namespace Yugo\Jobs;

abstract class Job
{
    public string $name = 'queue';

    abstract function handle(): void;
}