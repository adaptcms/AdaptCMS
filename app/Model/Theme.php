<?php
/**
 * Class Theme
 *
 * @property Template $Template
 *
 * @method findByTitle(string $title)
 */
class Theme extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_themes'
    */
	public $name = 'Theme';

    /**
    * A theme has many templates
    */
	public $hasMany = array(
        'Template' => array(
            'dependent' => true
        )
    );

    /**
    * Validation rules
    */
	public $validate = array(
        'title' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Please enter theme name'
            ),
            array(
                'rule' => 'isUnique',
                'message' => 'Theme name already in use'
            )
        )
    );

	/**
	 * @var array
	 */
	public $actsAs = array(
		'Delete' => array(
			'cascade' => true
		)
	);

    /**
    * Types of files that can be edited
    */
    public $file_types_editable = array(
        'txt',
        'php',
        'html',
        'css',
        'js',
        'phps',
        'htm',
        'less',
        'sass',
        'scss'
    );

    /**
    * Creates folders need for themes
    *
    * @param array $options
    *
    * @return boolean
    */
    public function beforeSave($options = array())
    {
        if (empty($this->data['Theme']['skipBeforeSave']))
        {
            if (!empty($this->data) &&
                empty($this->data['Theme']['id']) &&
                !empty($this->data['Theme']['title']))
            {
                $this->data['Theme']['title'] = $this->camelCase($this->data['Theme']['title']);

	            if (empty($this->data['Theme']['path'])) {
		            $path = $this->getActivePath();
	            } else {
		            $path = $this->data['Theme']['path'];
	            }

	            $path = $path . $this->data['Theme']['title'];

                if (!file_exists($path))
                    mkdir($path);

                if (!file_exists($path . '/webroot'))
                    mkdir($path . '/webroot');

                if (!file_exists($path . '/webroot/css'))
                    mkdir($path . '/webroot/css');

                if (!file_exists($path . '/webroot/js'))
                    mkdir($path . '/webroot/js');

                if (!file_exists($path . '/webroot/img'))
                    mkdir($path . '/webroot/img');

                foreach($this->Template->folderList() as $folder)
                {
                    if (!file_exists($path . '/' . $folder))
                        mkdir($path . '/' . $folder);

					$folders = $this->Template->folderList(VIEW_PATH . $folder);
	                if (!empty($folders)) {
		                foreach($folders as $sub) {
			                if (!file_exists($path . '/' . $folder . DS . $sub))
				                mkdir($path . '/' . $folder . DS . $sub);

			                $list = $this->Template->folderList(VIEW_PATH . $folder . DS . $sub);

			                if (!empty($list)) {
				                foreach($list as $lvl3) {
					                if (!file_exists($path . '/' . $folder . DS . $sub . DS . $lvl3))
						                mkdir($path . '/' . $folder . DS . $sub . DS . $lvl3);
				                }
			                }
		                }
	                }
                }
            } elseif (!empty($this->data) && !empty($this->data['Theme']['old_title']))
            {
                $this->data['Theme']['title'] = $this->camelCase($this->data['Theme']['title']);

                if ($this->data['Theme']['title'] != $this->data['Theme']['old_title'])
                {
                    if (file_exists(VIEW_PATH . 'Themed/' . $this->data['Theme']['old_title']))
                    {
                        rename(
                            VIEW_PATH . 'Themed/' . $this->data['Theme']['old_title'],
                            VIEW_PATH . 'Themed/' . $this->data['Theme']['title']
                        );
                    } else {
                        return false;
                    }
                }
            }
        }

        return true;
    }

	/**
	 * Before Delete
	 *
	 * @param bool $cascade
	 * @return bool
	 */
	public function beforeDelete($cascade = true)
	{
		$theme = $this->findById($this->id);

		if (file_exists($this->getActivePath() . $theme['Theme']['title']))
			$this->recursiveDelete($this->getActivePath() . $theme['Theme']['title']);

		/*
		if (file_exists($this->getInactivePath() . $theme['Theme']['title']))
			$this->recursiveDelete($this->getInactivePath() . $theme['Theme']['title']);
		*/

		return true;
	}

    /**
    * Transforms a text string into camel cased text, used in controllers
    *
    * @param string $string to camel case the text
    * @param string $spaces if set, adds spaces for camel case words
    * @return string
    */
	public function camelCase($string, $spaces = null) 
	{ 
	  	$string = ucwords(str_replace(array('-', '_'), ' ', $string));

	  	if (!$spaces) {
	  		return str_replace(' ', '', $string);
	  	} else {
	  		return $string;
		}
	}

    /**
    * Returns list of assets for specified theme along with plugin assets
    *
    * @param string $theme theme name
    * @param boolean $plugin false by default, otherwise provides name of plugin
    * @return array of theme assets
    */
    public function getAssets($theme = null, $plugin = false)
    {
        $assets = $this->assetsList($theme, $plugin);
        
        foreach(Configure::read('Plugins.list') as $plugin)
        {
            $assets = array_merge($assets, $this->getAssets($theme, $plugin));
        }

        return $assets;
    }

    /**
    * Returns list of assets for specified theme
    *
    * @param string $theme theme name
    * @param boolean $plugin false by default, otherwise provides name of plugin
    * @return array of theme assets
    */
	public function assetsList($theme = null, $plugin = false)
	{
        if (empty($theme) || $theme == 'Default')
        {
            $path = WWW_ROOT;
            $rel_path = '/';
            $view_path = '/';
        } else {
            $path = VIEW_PATH.'Themed/' . $theme.'/webroot/';
            $view_path = DS . 'theme' . DS . $theme.'/';
            $rel_path = '/';
        }

        if (!empty($plugin))
        {
            $path = APP . DS . 'Plugin' . DS . $plugin . DS . 'webroot' . DS;
            $rel_path = DS . 'Plugin' . DS . $plugin . DS . 'webroot' . DS;
            $view_path = DS . $plugin . DS;
        }

        $rel_path = urlencode( str_replace('/', '__', $rel_path) );

        $exclude = array('.', 'themes', '.htaccess', 'index.php', 'test.php', 'uploads', 'libraries', 'fonts', 'installer', 'angular', 'folder_upload');
        $exclude2 = array('..', 'fancybox', 'tinymce', 'vendor');

        if (!file_exists($path))
        {
            return array();
        }

        $data = array();

        if ($dh = opendir($path))
        {
            while (($file = readdir($dh)) !== false)
            {
                if (!in_array($file, $exclude) && $file != ".." && $file != ".")
                {
                    if (is_dir($path.$file) && $fol = opendir($path.$file))
                    {
                        while(($row = readdir($fol)) != false)
                        {
                            if ($row != ".." && $row !="." && !in_array($row, $exclude2))
                            {
                                if ($file != ".")
                                {
                                    $new_path = str_replace('.', '&', $file . '__' . $row);

                                    $data[$rel_path . $new_path] = $file . DS . $row;
                                } else {
                                    $data[$rel_path . $row] = $row;
                                }
                            }
                        }
                    } else {
                    	$data[$rel_path . $file] = $file;
                    }
                }
            }
        }

        return array(
            'assets' => $data,
            'path' => $path,
            'view_path' => $view_path
        );
	}

	/**
	 * Gets config file for a theme with a JSON file
	 *
	 * @param $name
	 * @param string $folder
	 * @return array Array of configuration, blank array on false
	 */
	public function getConfig($name, $folder = 'Themed')
	{
		$json = VIEW_PATH . DS . $folder . DS . $name . DS . 'theme.json';

		if (file_exists($json) && is_readable($json)) {
			$handle = fopen($json, "r");
			$json_file = fread($handle, filesize($json));

			return json_decode($json_file, true);
		}

		return array();
	}

	/**
	 * The function gets parameters needed on the view such as status of the Theme
	 *
	 * @param string $path path to themes
	 * @return array of theme data
	 */
	public function getThemes($path)
	{
		$themes = array();
		$api_lookup = array();
		$exclude = array('empty');

		if ($dh = opendir($path)) {
			while (($file = readdir($dh)) !== false) {
				if (!in_array($file, $exclude) && $file != ".." && $file != ".") {
					$json = $path . DS . $file . DS . 'theme.json';

					if (file_exists($json) && is_readable($json)) {
						$handle = fopen($json, "r");
						$json_file = fread($handle, filesize($json));

						$themes[$file] = json_decode($json_file, true);

						if (!empty($themes[$file]['api_id'])) {
							$api_lookup[] = $themes[$file]['api_id'];
						}

						$upgrade = $path . DS . $file . DS . 'Install' . DS . 'upgrade.json';

						if (file_exists($upgrade) && is_readable($upgrade))
						{
							$themes[$file]['upgrade_status'] = 1;
						} else {
							$themes[$file]['upgrade_status'] = 0;
						}

						if (strstr($path, 'Old')) {
							$themes[$file]['status'] = 0;
						} else {
							$themes[$file]['status'] = 1;
						}
					}
				}
			}
		}

		return array(
			'themes' => $themes,
			'api_lookup' => $api_lookup
		);
	}

	/**
	 * Get Themes List
	 *
	 * @return array
	 */
	public function getThemesList()
	{
		$themes = $this->find('list', array(
			'order' => 'Theme.id ASC',
		));

		return $themes;
	}

	/**
	 * Get Themes By Name
	 *
	 * @param $themes
	 * @return mixed
	 */
	public function getThemesByName($themes)
	{
		if (!empty($themes)) {
			foreach($themes as $key => $theme) {
				$themes[$theme] = $theme;
				unset($themes[$key]);
			}
		}

		return $themes;
	}

	/**
	 * Refresh Theme
	 *
	 * @param $id
	 * @param null $name
	 * @param array $files
	 *
	 * @return array
	 */
	public function refreshTheme($id, $name = null, $files = array())
	{
		if (empty($files)) {
			if (!empty($name)) {
				$files = $this->Template->folderAndFilesList($name);
			} else {
				$files = $this->Template->folderAndFilesList();
			}
		}

		try {
			if (!empty($files)) {
				$data = $this->Template->find('all', array(
					'conditions' => array(
						'Template.theme_id' => $id
					),
					'fields' => array(
						'Template.location'
					)
				));

				$key = 0;
				$templates = array();

				$this->Template->create();

				if (!empty($data))
					$data = Set::extract('{n}.Template.location', $data);

				foreach ($files as $file) {
					if (empty($data) || !empty($data) && !in_array($file, $data)) {
						$title = explode('/', $file);

						$templates[$key]['Template']['title'] = end($title);
						$templates[$key]['Template']['label'] =
							str_replace('Plugin View', 'Plugin', str_replace('.ctp', '', Inflector::humanize(str_replace("/", " ", $file))));
						$templates[$key]['Template']['location'] = $file;
						$templates[$key]['Template']['theme_id'] = $id;
						$templates[$key]['Template']['created'] = $this->dateTime();
						$templates[$key]['Template']['nowrite'] = true;

						$key++;
					}
				}

				if (!empty($templates))
					$this->Template->saveAll($templates);
			}

			$type = 'success';
			$message = 'The theme has been refreshed.';
		} catch(Exception $e) {
			$type = 'error';
			$message = 'The Theme could not be refreshed.';
		}

		return array(
			'type' => $type,
			'message' => $message
		);
	}

	/**
	 * Get Active Path
	 *
	 * @return string
	 */
	public function getActivePath()
	{
		return VIEW_PATH . 'Themed' . DS;
	}

	/**
	 * Get Inactive Path
	 *
	 * @return string
	 */
	public function getInactivePath()
	{
		return VIEW_PATH . 'Old_Themed' . DS;
	}

	/**
	 * Create Theme
	 *
	 * @param $data
	 *
	 * @return integer
	 */
	public function createTheme($data)
	{
		$this->create();

		$data['basicInfo']['name'] = Inflector::camelize($data['basicInfo']['name']);

		$theme['Theme']['title'] = $data['basicInfo']['name'];
		$theme['Theme']['path'] = $this->getInactivePath();

		$this->save($theme);

		$path = $this->getInactivePath() . $data['basicInfo']['name'] . DS;

		$defaults = array(
			'Views' => array(
				'Categories' => array(
					'view.ctp' => '{{ addCrumb($category[\'title\'], null) }}

{{ setTitle($category[\'title\']) }}

<h1>{{ category[\'title\'] }}</h1>

{% if empty(articles) %}
	<p>No Articles Found</p>
{% else %}
	{% loop article in articles %}
		<div class="span8 no-marg-left clearfix">
			<a href="{{ url(\'article_view\', $article) }}"><h2>{{ article[\'Article\'][\'title\'] }}</h2></a>
			<p class="lead">
				@ <em>{{ time(article[\'Article\'][\'created\']) }}</em>
			</p>

			<blockquote>
				{{ getTextAreaData(article) }}
			</blockquote>

			<div id="post-options">
		        <span class="pull-left">
			        <a href="{{ url(\'article_view\', $article) }}" class="btn btn-primary">Read More</a>
			        <span style="margin-left: 10px">
		                <i class="fa fa-comment"></i>&nbsp;
				        <a href="{{ url(\'article_view\', $article) }}#comments">{{ article[\'Comments\'] }} Comments</a>
		            </span>
		            <span style="margin-left: 10px">
		                <i class="fa fa-user"></i>&nbsp;
		                Posted by <a href="{{ url(\'user_profile\', $article) }}">{{ article[\'User\'][\'username\'] }}</a>
		            </span>
		        </span>
		        <span class="pull-right">
			        {% if not empty(article[\'Article\'][\'tags\']) %}
						{% loop tag in article[\'Article\'][\'tags\'] %}
			                <a href="{{ url(\'article_tag\', $tag) }}" class="tags">
				                <span class="btn btn-success">{{ tag }}</span>
			                </a>
		                {% endloop %}
			        {% endif %}
		        </span>
			</div>
		</div>
		<hr>
	{% endloop %}
{% endif %}

{{ partial(\'pagination\') }}'
				),
				'Articles' => array(
					'view.ctp' => '{{ addCrumb($article[\'Category\'][\'title\'], url(\'category_view\', $article[\'Category\'][\'slug\'])) }}
{{ addCrumb($article[\'Article\'][\'title\'], null) }}

{{ setTitle($article[\'Article\'][\'title\']) }}

{% if not empty(wysiwyg) %}
	{{ tinymce.simple }}
{% endif %}

{{ js(\'jquery.blockui.min.js\') }}
{{ js(\'jquery.smooth-scroll.min.js\') }}
{{ js(\'comments.js\') }}

<div class="span8 no-marg-left">
	<h1>{{ article[\'Article\'][\'title\'] }}</h1>

	<p class="lead">
		@ <em>{{ time(article[\'Article\'][\'created\']) }}</em>
	</p>

	{{ getTextAreaData(article) }}

	<div id="post-options">
        <span class="pull-left">
	        <a href="{{ url(\'category_view\', $category[\'slug\']) }}" class="btn btn-primary">
		        {{ category[\'title\'] }}
	        </a>
            <span style="margin-left: 10px">
                <i class="fa fa-search fa fa-user"></i>&nbsp;
                Posted by <a href="{{ url(\'user_profile\', $user[\'username\']) }}">{{ user[\'username\'] }}</a>
            </span>
        </span>
        <span class="pull-right">
	        {% if not empty(tags) %}
	            {% loop tag in tags %}
	                <a href="{{ url(\'article_tag\', $tag) }}" class="tags">
		                <span class="btn btn-success">{{ tag }}</span>
	                </a>
	            {% endloop %}
        	{% endif %}
        </span>
    </div>
</div>

<div class="clearfix"></div>'
				),
				'Pages' => array(
					'home.ctp' => '{{ setTitle(\'Home Page\') }}

<h1>Newest Articles</h1>

{% if empty(articles) %}
	<p>No Articles Found</p>
{% else %}
	{% loop article in articles %}
		<div class="span8 no-marg-left clearfix">
			<a href="{{ url(\'article_view\', $article) }}"><h2>{{ article[\'Article\'][\'title\'] }}</h2></a>
			<p class="lead">
				@ <em>{{ time(article, \'\', \'created\') }}</em>
			</p>

			<blockquote>
				{{ getTextAreaData(article) }}
			</blockquote>

			<div id="post-options">
		        <span class="pull-left">
			        <a href="{{ url(\'article_view\', $article) }}" class="btn btn-primary">Read More</a>
			        <span style="margin-left: 10px">
		                <i class="fa fa-comment"></i>&nbsp;
				        <a href="{{ url(\'article_view\', $article) }}#comments">{{ article[\'CommentsCount\'] }} Comments</a>
		            </span>
		            <span style="margin-left: 10px">
		                <i class="fa fa-user"></i>&nbsp;
		                Posted by <a href="{{ url(\'user_profile\', $article) }}">{{ article[\'User\'][\'username\'] }}</a>
		            </span>
		        </span>
		        <span class="pull-right">
			        {% if not empty(article[\'Article\'][\'tags\']) %}
						{% loop tag in article[\'Article\'][\'tags\'] %}
			                <a href="{{ url(\'article_tag\', $tag) }}" class="tags">
				                <span class="btn btn-success">{{ tag }}</span>
			                </a>
		                {% endloop %}
			        {% endif %}
		        </span>
			</div>
		</div>
		<hr>
	{% endloop %}
{% endif %}

{{ partial(\'pagination\') }}'
				)
			),
			'json' => array(
				'header' => '{
    "title": "{full_name}",
    "api_id": "",
    "current_version": "{current_version}",'
			)
		);

		if (!empty($data['skeleton']['layout'])) {
			$layout_path = CACHE . 'persistent' . DS . 'create_theme_layout.tmp';

			if (file_exists($layout_path) && (time() - filemtime($layout_path) < 1209600) ) {
				$response = file_get_contents($layout_path);
			} else {
				$url = 'https://raw.github.com/adaptcms/sample-theme/master/Sample/Frontend/layout.ctp';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($ch);
				curl_close($ch);

				file_put_contents($layout_path, $response);
			}

			file_put_contents($path . 'Frontend' . DS . 'layout.ctp', $response);
		}

		if (!empty($data['skeleton']['views'])) {
			file_put_contents($path . 'Frontend' . DS . 'Categories' . DS . 'view.ctp', stripslashes($defaults['Views']['Categories']['view.ctp']));
			file_put_contents($path . 'Frontend' . DS . 'Articles' . DS . 'view.ctp', stripslashes($defaults['Views']['Articles']['view.ctp']));
			file_put_contents($path . 'Frontend' . DS . 'Pages' . DS . 'home.ctp', stripslashes($defaults['Views']['Pages']['home.ctp']));
		}

		$find = array(
			'{full_name}',
			'{current_version}'
		);
		$replace = array(
			$data['basicInfo']['name'],
			$data['versions']['current_version']
		);

		$json = str_replace($find, $replace, $defaults['json']['header']);

		$json .= '
	"versions": [';

		foreach($data['versions']['versions'] as $version) {
			$json .= '
		"' . $version . '"' . (end($data['versions']['versions']) != $version ? ',' : '');
		}

		$json .= '
	]
}';

		file_put_contents($path . 'theme.json', $json);

		return $this->id;
	}
}