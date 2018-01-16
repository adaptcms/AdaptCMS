<?php

namespace App\Modules\Plugins\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Plugins\Models\Plugin;

use Cache;
use Module;
use Storage;

class PluginsController extends Controller
{
    /**
    * Index
    *
    * @return View
    */
    public function index()
    {
        // current plugins
        $items = Module::all();

        $core_modules = Plugin::getCoreModules();

        return view('plugins::Admin/Plugins/index', compact('items', 'core_modules'));
    }

    /**
    * Install
    *
    * @param string $slug
    *
    * @return Redirect
    */
    public function install($slug)
    {
        $this->fireEvent($slug, $slug . 'Install');

        Plugin::enable($slug);

        return redirect()->route('admin.plugins.index')->with('status', 'Plugin has been enabled.');
    }

    /**
    * Uninstall
    *
    * @param string $slug
    *
    * @return Redirect
    */
    public function uninstall($slug)
    {
        $this->fireEvent($slug, $slug . 'Uninstall');

        Plugin::disable($slug);

        return redirect()->route('admin.plugins.index')->with('status', 'Plugin has been disabled.');
    }
}
