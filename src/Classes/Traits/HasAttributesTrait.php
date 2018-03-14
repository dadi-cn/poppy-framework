<?php namespace Poppy\Framework\Classes\Traits;

/**
 * Trait HasAttributes.
 */
trait HasAttributesTrait
{
	/**
	 * @var array
	 */
	protected $attributes;

	/**
	 * Get the instance as an array.
	 * @return array
	 */
	public function toArray()
	{
		return $this->attributes;
	}

	/**
	 * @param string $offset
	 * @param null   $default
	 * @return null
	 */
	public function get(string $offset, $default = null)
	{
		return data_get($this->attributes, $offset, $default);
	}

	/**
	 * Whether a offset exists.
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->attributes[$offset]);
	}

	/**
	 * Offset to retrieve.
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		return $this->attributes[$offset];
	}

	/**
	 * Offset to set.
	 * @param mixed $offset
	 * @param mixed $value
	 */
	public function offsetSet($offset, $value)
	{
		$this->attributes[$offset] = $value;
	}

	/**
	 * Offset to unset.
	 * @param mixed $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->attributes[$offset]);
	}

	/**
	 * Specify data which should be serialized to JSON.
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->attributes;
	}
}
