<?php


class Soup implements ArrayAccess
{
	private $_parent = NULL;
	private $_params;
	private $_blockSetKey = NULL;
	private $_includeBlockKey = NULL;
	
	
	function __construct($params = array())
	{
		assert('is_array($params)');
		$this->_params = $params;
	}
	
	function getParent()
	{
		return $this->_parent;
	}
	
	function fork($params = array())
	{
		$fork = new Soup($params);
		$fork->_parent = $this;
		return $fork;
	}
	
	function get($key, $defValue = '')
	{
		assert('is_string($key)');
		
		$soup = $this;
		$value = NULL;
		while ($soup !== NULL && $value === NULL) {
			$value = @$soup->_params[$key];
			$soup = $soup->_parent;
		}
		return ($value !== NULL) ? $value : $defValue;
	}
	
	function set($keyOrArray, $value = NULL)
	{
		assert('is_array($keyOrArray) || is_string($keyOrArray)');
		
		if (is_array($keyOrArray)) {
			$this->_params = array_merge($this->_params, $keyOrArray);
		} else {
			$this->_params[$keyOrArray] = $value;
		}
	}
	
	function clear($keyOrArray)
	{
		assert('is_array($keyOrArray) || is_string($keyOrArray)');
		
		if (is_array($keyOrArray)) {
			foreach ($keyOrArray as $key) {
				unset($this->_params[$key]);
			}
		} else {
			unset($this->_params[$keyOrArray]);
		}
	}
	
	function setDefault($keyOrArray, $defValue = NULL)
	{
		assert('is_array($keyOrArray) || is_string($keyOrArray)');
		
		if (is_array($keyOrArray)) {
			foreach ($keyOrArray as $key => $value) {
				if ($this->get($key) === NULL) {
					$this->_params[$key] = $value;
				}
			}
		} else {
			if ($this->get($key) === NULL) {
				$this->_params[$key] = $defValue;
			}
		}
	}
	
	function startBlockGet($key)
	{
		assert('$this->_includeBlockKey === NULL');
		
		$this->_includeBlockKey = $key;
		ob_start();
	}
	
	function endBlockGet()
	{
		assert('$this->_includeBlockKey !== NULL');
		
		$value = $this->get($this->_includeBlockKey);
		if ($value !== NULL) {
			ob_end_clean();
			echo $value;
		} else {
			ob_end_flush();
		}
		$this->_includeBlockKey = NULL;
	}
	
	function startBlockSet($key)
	{
		assert('$this->_blockSetKey === NULL');
		
		$this->_blockSetKey = $key;
		ob_start();
	}
	
	function endBlockSet()
	{
		assert('$this->_blockSetKey !== NULL');
		
		$this->set($this->_blockSetKey, ob_get_clean());
		$this->_blockSetKey = NULL;
	}
	
	function toArray()
	{
		$array = $this->_params;
		$soup = $this->_parent;
		
		while ($soup !== NULL) {
			$array = $array + $soup->_params;
			$soup = $soup->_parent;
		}
		
		return $array;
	}
	
	
	function offsetExists($offset)
	{
		return $this->get($offset, NULL) !== NULL;
	}
	
	function offsetGet($offset)
	{
		return $this->get($offset);
	}
	
	function offsetSet($offset, $value)
	{
		$this->_params[$offset] = $value;
	}
	
	function offsetUnset($offset)
	{
		unset($this->_params[$offset]);
	}
	
	function render($templateName, $extraParams = NULL)
	{
		$SOUP = $this;
		if ($extraParams) {
			$SOUP = $this->fork($extraParams);
		}
		
		if (defined('TEMPLATE_PATH')) {
			$TEMPLATE_PATH = TEMPLATE_PATH;
		} else {
			$TEMPLATE_PATH = '.';
		}
		
		include("{$TEMPLATE_PATH}/{$templateName}.tpl.php");
	}
	
	function capture($templateName, $extraParams = NULL)
	{
		ob_start();
		$this->render($templateName, $extraParams);
		return ob_get_clean();
	}
}


?>