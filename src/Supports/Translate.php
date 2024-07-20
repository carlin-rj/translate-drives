<?php

namespace Carlin\TranslateDrives\Supports;


use ArrayAccess;
use Carlin\TranslateDrives\Traits\ArrayAccessTrait;

class Translate implements ArrayAccess
{
	use ArrayAccessTrait;
	private array $attributes;

	public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    public function getSrc():string
    {
        return $this->attributes['src'];
    }

    public function getDst():string
    {
        return $this->attributes['dst'];
    }

    public function getOriginal(): array
	{
        return $this->attributes;
    }
}
