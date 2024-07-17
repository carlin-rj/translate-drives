<?php

namespace Carlin\TranslateDrivers\Supports;


use ArrayAccess;
use Carlin\TranslateDrivers\Traits\ArrayAccessTrait;

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
