<?php
App::uses('Blocks', 'Service');
App::uses('View', 'View');
/**
 * Class AdaptcmsView
 *
 * @property AppController $Controller
 */
class AdaptcmsView extends View
{
	private $admin = false;
	private $is_element = false;
	private $blocks = array();
	private $disable_parsing;
	private $Block;

	/**
	 * Construct
	 *
	 * @param Controller $Controller
	 */
	public function __construct(Controller $Controller = null)
	{
		$this->Block = new Blocks();

		parent::__construct($Controller);

		if (!empty($Controller)) {
			if (isset($Controller->theme)) {
				if ($Controller->theme == 'Default') {
					$this->theme = '';
				} else {
					$this->theme = $Controller->theme;
				}
			}

			if (isset($Controller->layout)) {
				$this->layout = $Controller->layout;
			}

			$this->viewFile = $Controller->view;

			if (strstr($this->viewFile, 'Frontend')) {
				$this->viewFile = str_replace('Frontend/', '', $this->viewFile);
			} elseif (strstr($this->request->action, 'admin')) {
				$this->admin = true;
			}

			if ($this->admin) {
				$this->viewFile = str_replace('admin_', '', $this->viewFile);
			}

			if ($this->layout == 'admin' || $this->layout == 'default') {
				$this->layoutFile = 'layout';
			} else {
				$this->layoutFile = $this->layout;
			}

			if (!empty($this->viewFile)) {
				try {
					$this->viewFile = $this->_getViewFileName($this->viewFile);
				} catch(Exception $e) {
//					debug($e->getMessage());
				}
			}

			$this->disable_parsing = $Controller->disable_parsing;
		}

		$this->Controller = $Controller;
	}

	/**
	 * Paths
	 *
	 * @param null $plugin
	 * @param bool $cached
	 * @return array
	 */
	protected function _paths($plugin = null, $cached = true)
	{
		$list = parent::_paths($plugin, $cached);

		foreach($list as $key => $row) {
			if ($this->is_element)
				$list[] = $row;

			if (!strstr($row, 'Cake')) {
				if ($this->admin) {
					$row = $row . 'Admin/';
				} else {
					$row = $row . 'Frontend/';
				}
			}

			if (!strstr($this->request->controller, 'install') && strstr($row, 'installer') || strstr($row, 'lib/Cake')) {
				unset($list[$key]);
			}

			$list[$key] = $row;
		}

		return $list;
	}

	/**
	 * Render
	 *
	 * @param null|string $view
	 * @param null|string $layout
	 * @return string
	 */
	public function render($view, $layout)
	{
		$this->getEventManager()->dispatch(new CakeEvent('View.beforeRender', $this, array($this->viewFile)));
		$this->Blocks->set('content', parent::_render($this->viewFile, $layout));
		$this->getEventManager()->dispatch(new CakeEvent('View.afterRender', $this, array($this->viewFile)));

		$this->Blocks->set('content', $this->renderLayout('', $this->layoutFile));

		$this->hasRendered = true;
		$content = $this->Blocks->get('content');

		return $content;
	}

	/**
	 * Evaluate
	 *
	 * @param $viewFile
	 * @param array $dataForView
	 * @throws Exception
	 * @return string
	 */
	protected function _evaluate($viewFile, $dataForView) {
		$viewFileModified = filemtime($viewFile);
		$tempFile = str_replace(APP, '', $viewFile);

		if (strstr(basename($tempFile), 'layout')) {
			if ($this->disable_parsing) {
				$append = '_1';
			} else {
				$append = '_0';
			}
		} else {
			$append = '';
		}

		$tmpFile = strtolower(Inflector::slug($tempFile)) . $append . '.tmp';

		$cache = TMP . 'templates' . DS;

		$file = file_get_contents($viewFile);
		if (!file_exists($cache . $tmpFile) || $viewFileModified > filemtime($cache . $tmpFile)) {
			if (!$this->disable_parsing) {
				$contents = $this->_parse($viewFile, $file);
			} else {
				$contents = $file;
			}

			file_put_contents($cache . $tmpFile, $contents);
		}

		$dataForView['block_data'] = array();
		$dataForView['block_permissions'] = array();
		$dataForView = $this->_findBlocks($file, $dataForView);

		$this->__viewFile = $cache . $tmpFile;

		extract($dataForView);
		ob_start();

		try {
			include $this->__viewFile;
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}

		unset($this->__viewFile);
		return ob_get_clean();
	}

	/**
	 * Get Layout File Name
	 *
	 * @param null $name
	 * @return string
	 * @throws MissingLayoutException
	 */
	protected function _getLayoutFileName($name = null) {
		if ($name === null) {
			$name = $this->layout;
		}
		$subDir = null;

		if ($this->layoutPath !== null) {
			$subDir = $this->layoutPath . DS;
		}
		list($plugin, $name) = $this->pluginSplit($name);

		$paths = $this->_paths($plugin);

		if ($this->layoutFile != 'layout') {
			$file = 'Layouts' . DS . $subDir . $name;
		} else {
			$file = $subDir . $name;
		}
		$exts = $this->_getExtensions();
		foreach ($exts as $ext) {
			foreach ($paths as $path) {
				if (file_exists($path . $file . $ext)) {
					return $path . $file . $ext;
				}
			}
		}
		throw new MissingLayoutException(array('file' => $paths[0] . $file . $this->ext));
	}

	/**
	 * Get Element File Name
	 *
	 * @param string $name
	 * @return bool|mixed|string
	 */
	protected function _getElementFileName($name) {
		list($plugin, $name) = $this->pluginSplit($name);

		$this->is_element = true;
		$paths = $this->_paths($plugin);
		$this->is_element = false;
		$exts = $this->_getExtensions();
		foreach ($exts as $ext) {
			foreach ($paths as $path) {
				if (file_exists($path . 'Elements' . DS . $name . $ext)) {
					return $path . 'Elements' . DS . $name . $ext;
				}
			}
		}
		return false;
	}

	/**
	 * Parse
	 *
	 * @param $file
	 * @param $content
	 * @return mixed
	 */
	public function _parse($file, $content)
	{
		$is_layout = (!empty($file) ? strstr(basename($file), 'layout') : false);

		$find = array();
		$replace = array();

		if ($is_layout && !strstr($content, 'id="webroot"')) {
			$var = '<div id="webroot" style="display:none">'. $this->request->webroot . '</div></body>';
			$find[] = '</body>';
			$replace[] = $var;
		}

		if ($is_layout) {
			$headers = '
<?php echo $this->Html->script("jquery.min") ?>
<?php echo $this->Html->script("jquery.validate.min") ?>
<?php echo $this->Html->script("bootstrap.min") ?>
<?= $this->Html->script("vendor/noty/jquery.noty.packaged.min.js") ?>
<?= $this->Html->script("vendor/noty/themes/default.js") ?>
<?php echo $this->Html->script("global") ?>

<?php echo $this->fetch("meta") ?>
<?php echo $this->fetch("css") ?>
<?php echo $this->fetch("script") ?>

<?php echo $this->AutoLoadJS->getJs() ?>
<?php echo $this->AutoLoadJS->getCss() ?>
			';

			if (!strstr($content, '/js/global.js')) {
				if (strstr($content, '{{ headers }}')) {
					$find[] = '{{ headers }}';
				} else {
					$find[] = '<head>';
					$headers = '<head>' . $headers;
				}

				$replace[] = $headers;
			}
		}

		// CMS Version
		$find[] = '{{ ADAPTCMS_VERSION }}';
		$replace[] = '<?php echo ADAPTCMS_VERSION ?>';
		
		// Powered By
		$find[] = '{{ powered_by }}';
		$replace[] = 'Powered by <?php echo $this->Html->link("AdaptCMS " . ADAPTCMS_VERSION, "http://www.adaptcms.com", array("target" => "_blank")) ?>';

		// Copyright
		$find[] = '{{ copyright }}';
		$replace[] = '2006-<?php echo date("Y") ?> <?php echo $this->Html->link("AdaptCMS", "http://www.adaptcms.com", array("target" => "_blank")) ?>';

		// Show content in layout
		$find[] = '{{ content }}';
		$replace[] = '<?php echo $this->fetch("content") ?>';

		// CSS Helper
		$find[] = '{{ css(';
		$replace[] = '<?php echo $this->Html->css(';

		// JS Helper
		$find[] = '{{ js(';
		$replace[] = '<?php $this->AdaptHtml->script(';

		// Html Helper
		$find[] = '{{ html.';
		$replace[] = '<?php echo $this->Html->';

		// Field Helper - getTextAreaData method
		$find[] = '{{ getTextAreaData(';
		$replace[] = '<?php echo $this->Field->getTextAreaData($';

		// Webroot Path
		$find[] = '{{ webroot }}';
		$replace[] = '<?php echo $this->webroot ?>';

		// Is Logged In
		$find[] = '{% if logged_in';
		$replace[] = '<?php if ($this->Session->check("Auth.User.username")';

		$find[] = '{% if not logged_in';
		$replace[] = '<?php if (!$this->Session->check("Auth.User.username")';

		// Is an Admin
		$find[] = '{% if is_admin %}';
		$replace[] = '<?php if (!$this->Session->read("Auth.User.Role.defaults") || $this->Session->read("Auth.User.Role.defaults") == "default-admin"): ?>';

		$find[] = '{% if not is_admin %}';
		$replace[] = '<?php if (!$this->Session->read("Auth.User.Role.defaults") || $this->Session->read("Auth.User.Role.defaults") != "default-admin"): ?>';

		// Show Flash Messages
		$find[] = '{{ flash }}';
		$replace[] = '<?php echo $this->Session->flash() ?>';

		// Display Partial (element)
		$find[] = '{{ partial(';
		$replace[] = '<?php echo $this->Element(';

		// Show Breadcrumbs
		$find[] = '{{ breadcrumbs }}';
		$replace[] = '<?php echo
				$this->Html->getCrumbList(array(
					"class" => "breadcrumb",
					"escape" => false,
					"lastClass" => "active"
				), "Home") ?>';

		// Add Breadcrumb
		$find[] = '{{ addCrumb(';
		$replace[] = '<?php $this->Html->addCrumb(';

		// URL Helper
		$find[] = '{{ url(';
		$replace[] = '<?php echo $this->View->url(';

		$find[] = ' url(';
		$replace[] = ' $this->View->url(';

		// Link Helper
		$find[] = '{{ link(';
		$replace[] = '<?php echo $this->Html->link(';

		// Image Helper
		$find[] = '{{ image(';
		$replace[] = '<?php echo $this->Html->image(';

		// Time Helper
		$find[] = '{{ time(';
		$replace[] = '<?php echo $this->Admin->time($';

		$find[] = 'time.within(';
		$replace[] = '$this->Time->wasWithinLast(';

		// Form Helper
		$find[] = '{{ form.';
		$replace[] = '<?php echo $this->Form->';

		// Captcha Helper
		$find[] = '{{ captcha.form(';
		$replace[] = '<?php echo $this->Captcha->form(';

		// TinyMCE Helper
		$find[] = '{{ tinymce.simple';
		$replace[] = '<?php echo $this->TinyMce->editor(array("simple" => true))';

		$find[] = '{{ tinymce.bbcode';
		$replace[] = '<?php echo $this->TinyMce->editor(array("bbcode" => true))';

		$find[] = '{{ tinymce';
		$replace[] = '<?php echo $this->TinyMce->editor()';

		// Set Title of Page
		$find[] = '{{ setTitle(';
		$replace[] = '<?php $this->set("title_for_layout", ';

		// Truncate Helper
		$find[] = '{{ truncate(';
		$replace[] = '<?php echo $this->Text->truncate($';

		// Obfuscate Email Helper
		$find[] = '{{ email(';
		$replace[] = '<?php echo $this->View->obfuscateEmail(';

		// Paginator Counter helper
		$find[] = '{{ paginator.counter(';
		$replace[] = '<?php echo $this->Paginator->counter(';

		// Paginator Sort helper
		$find[] = '{{ paginator.sort(';
		$replace[] = '<?php echo $this->Paginator->sort(';

		// Paginator Misc
		$find[] = '{{ paginator.';
		$replace[] = '<?php echo $this->Paginator->';

		// Humanize Helper
		$find[] = '{{ humanize(';
		$replace[] = '<?php echo Inflector::humanize($';

		// Current User Info
		$find[] = "{{ current_user('";
		$replace[] = '<?php echo $this->Session->read(\'Auth.User.';
		$find[] = '{{ current_user("';
		$replace[] = '<?php echo $this->Session->read(\'Auth.User.';

		$find[] = "current_user('";
		$replace[] = '$this->Session->read(\'Auth.User.';
		$find[] = 'current_user("';
		$replace[] = '$this->Session->read(\'Auth.User.';

		// Plugin Exists
		$find[] = 'hasPlugin(';
		$replace[] = '$this->View->pluginExists(';

		// Set
		$find[] = '{{ set ';
		$replace[] = '<?php $';

		// End loop
		$find[] = '{% endloop %}';
		$replace[] = '<?php endforeach ?>';

		// End for
		$find[] = '{% endfor %}';
		$replace[] = '<?php endfor ?>';

		// Block Data
		$find[] = 'block_data[';
		$replace[] = 'block_data[';

		$vars = Configure::read('global_vars');

		foreach($vars as $var) {
			if (!empty($var['tag']) && !empty($var['value'])) {
				$find[] = $var['tag'];
				$replace[] = $var['value'];
			}
		}

		// Normal Variable replacing
		$find[] = '{{ ';
		$replace[] = '<?php echo $';

		$find[] = ' }}';
		$replace[] = ' ?>';

		// If Statements
		$find[] = ' not empty(';
		$replace[] = ' !empty($';

		$find[] = '{% if not';
		$replace[] = '<?php if (!';

		$find[] = '{% if empty(';
		$replace[] = '<?php if (empty($';

		$find[] = '{% if';
		$replace[] = '<?php if (';

		$find[] = '{% elseif not empty(';
		$replace[] = '<?php elseif (!empty($';

		$find[] = '{% elseif empty(';
		$replace[] = '<?php elseif (empty($';

		$find[] = '{% elseif ';
		$replace[] = '<?php elseif (';

		$find[] = '{% else %}';
		$replace[] = '<?php else: ?>';

		$find[] = '{% endif %}';
		$replace[] = '<?php endif ?>';

		$find[] = ' %}';
		$replace[] = '): ?>';

		$content = str_replace($find, $replace, $content);

		// Normal foreach loop
		$find = array();
		$replace = array();

		$find[] = '/{% loop ([a-zA-Z0-9\'\"\[\]\-\_]+) in ([a-zA-Z0-9\'\"\[\]\-\_]+)/';
		$replace[] = '<?php foreach($$2 as $index => $$1';

		$content = preg_replace($find, $replace, $content);

		// For loop
		$find = array();
		$replace = array();

		$find[] = '/{% for ([a-zA-Z0-9\[\]\_]+),([a-zA-Z0-9\[\]\_]+)/';
		$replace[] = '<?php for($i = $1; $i <= $$2; $i++';

		$content = preg_replace($find, $replace, $content);

		return $content;
	}

	/**
	 * Find Blocks
	 *
	 * @param $content
	 * @param $dataForView
	 * @param bool $permissions
	 * @return
	 */
	public function _findBlocks($content, $dataForView, $permissions = true)
	{
		$blocks = $this->_getBlocks($content, $permissions);

		if (!empty($blocks)) {
			foreach($blocks as $row) {
				$block = $row['block'];
				$type = $row['type'];

				if (empty($this->blocks[$block][$type])) {
					$result = $this->Block->blockLookup($block, $type);
					$this->blocks[$block][$type] = $result[$type];
				}

				$dataForView['block_' . $type][$block] = $this->blocks[$block][$type];
			}
		}

		return $dataForView;
	}

	/**
	 * Get Blocks
	 *
	 * @param $content
	 * @param $permissions
	 * @return array
	 */
	public function _getBlocks($content, $permissions)
	{
		preg_match_all('/block_data\[([a-zA-Z0-9\'\"\[\]\-\_\$]+)\]/', $content, $matches);

		$blocks = array();
		if (!empty($matches[1])) {
			foreach($matches[1] as $match) {
				$ex = explode(']', $match);

				if (!empty($ex[0]))
					$match = $ex[0];

				$find[] = '"';
				$find[] = "'";

				$match = str_replace($find, '', $match);

				if (!empty($match))
					$blocks[] = array('block' => $match, 'type' => 'data');
			}
		}

		if (!empty($permissions)) {
			preg_match_all('/block_permissions\[([a-zA-Z0-9\'\"\[\]\-\_\$]+)\]/', $content, $perm_matches);

			if (!empty($perm_matches[1])) {
				foreach($perm_matches[1] as $perm) {
					$ex = explode(']', $perm);

					if (!empty($ex[0]))
						$perm = $ex[0];

					$find[] = '"';
					$find[] = "'";

					$perm = str_replace($find, '', $perm);

					if (!empty($perm))
						$blocks[] = array('block' => $perm, 'type' => 'permissions');
				}
			}
		}

		return $blocks;
	}

	public function renderCache($filename, $timeStart) {
		$content = file_get_contents($filename);
		$this->viewVars = $this->_findBlocks($content, $this->viewVars, true);

		if (!empty($this->viewVars['block_data']))
			$block_data = $this->viewVars['block_data'];

		if (!empty($this->viewVars['block_permissions']))
			$block_permissions = $this->viewVars['block_permissions'];

		$response = $this->response;
		ob_start();
		include $filename;

		$type = $response->mapType($response->type());
		if (Configure::read('debug') > 0 && $type === 'html') {
			echo "<!-- Cached Render Time: " . round(microtime(true) - $timeStart, 4) . "s -->";
		}
		$out = ob_get_clean();

		if (preg_match('/^<!--cachetime:(\\d+)-->/', $out, $match)) {
			if (time() >= $match['1']) {
				//@codingStandardsIgnoreStart
				@unlink($filename);
				//@codingStandardsIgnoreEnd
				unset($out);
				return false;
			}
			return substr($out, strlen($match[0]));
		}
	}
}