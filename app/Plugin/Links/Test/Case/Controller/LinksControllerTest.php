<?php
/**
 * Links Controller Test File
 *
 * PHP version 5
 *
 * @category Test
 * @package  Controller
 * @author   Charlie Page <charliepage88@gmail.com>
 * @license  Simplified BSD License (http://www.adaptcms.com/pages/license-info)
 * @link     http://www.adaptcms.com
 */

App::uses('LinksController', 'Links.Controller');
class TestLinksController extends LinksController
{
    public $params = array();
    public $autoRender = false;

    public $uses = array('User', 'Permission');

    public function redirect($url, $status = null, $exit = true)
    {
        $this->redirectUrl = $url;
    }

    public function render($action = null, $layout = null, $file = null)
    {
        $this->renderedAction = $action;
    }

    public function _stop($status = 0)
    {
        $this->stopped = $status;
    }
}

/**
 * Class LinksControllerTest
 *
 * @category Test
 * @package  Controller
 * @author   Charlie Page <charliepage88@gmail.com>
 * @license  Simplified BSD License (http://www.adaptcms.com/pages/license-info)
 * @link     http://www.adaptcms.com
 * @property TestLinksController $Links
 */
class LinksControllerTest extends ControllerTestCase
{
    /**
     * @var array
     */
    public $fixtures = array(
        'app.user',
        'app.permission'
    );

    public function startTest($method)
    {
        $this->Links = new TestLinksController();
        $this->Links->constructClasses();

        $this->Links->Session->destroy();

        $this->Links->request = new CakeRequest();
        $this->Links->response = new CakeResponse();
    }

    /**
     * End Test
     *
     * @param string $method
     *
     * @return void
     */
    public function endTest($method)
    {
        $this->Links->Session->destroy();
        unset($this->Links);
        ClassRegistry::flush();
    }

    /**
     * Test Admin Links Test
     *
     * @return void
     */
    public function testAdminLinksTest()
    {
        $this->Links->Session->write('Auth.User', array(
            'id' => 1,
            'username' => 'admin',
            'role_id' => '1'
        ));

        $this->Links->params = Router::parse('/admin/links');

        $this->Links->request->prefix = 'admin';
        $this->Links->beforeFilter();
        $this->Links->admin_index();

        debug($this->Links->viewVars);
    }
}