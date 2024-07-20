<?php

namespace Carlin\TranslateDrives\Supports;


use ArrayAccess;
use Carlin\TranslateDrives\Traits\ArrayAccessTrait;

class Config implements ArrayAccess
{
	use ArrayAccessTrait;

	private array $attributes;

	public function __construct(array $config = [])
    {
        $this->attributes = $config;
    }

	public function __get($value):mixed
	{
		return $this->attributes[$value];
	}

	public function __set($name, $value): void
	{
		$this->attributes[$name] = $value;
	}

	public function __isset($offset):bool
	{
		return isset($this->attributes[$offset]);
	}

	public function toArray()
	{
		return $this->attributes;

	}
}
