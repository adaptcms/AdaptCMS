<?php
/**
 * Class SampleComponent
 */
class SampleComponent extends Object
{
	/**
	 * @var
	 */
	private $controller;

	public function initialize(Controller $Controller)
	{
		$this->controller = $Controller;
	}

	public function startup()
	{
	}

	public function beforeRender()
	{
	}

	public function beforeRedirect()
	{
	}

	public function shutdown()
	{
	}

	/**
	* Test
	* This is just an example, this just returns text.
	*
	* @return string
	*/
	public function test()
	{
		return 'just some text';
	}
}