<?php

namespace Yugo\Services;

interface Mail
{
    public function transport(): string;

    public function from(string $address, ?string $name = null): self;

    public function replyTo(string $address): self;

    public function to(string $address): self;

    public function subject(string $subject): self;

    public function body(string $message): self;

    public function send(): void;
}