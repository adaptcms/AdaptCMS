<?php

namespace App\Modules\Core\Http\Controllers\Install;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use App\Modules\Core\Models\Install;
use App\Modules\Users\Models\User;

use Storage;
use Core;
use Cache;
use Settings;
use Auth;

class InstallController extends Controller
{
    public function index()
    {
        $install = new Install;

        // first let's connect to the adaptcms.com servers
        Core::syncWebsiteInit('index');

        $checks = $install->getServerChecks();

        return view('core::Install/index', compact('checks'));
    }

    public function database(Request $request)
    {
        $install = new Install;

        $connection_types = $install->getConnectionTypes();

        // test connection
        if ($request->ajax()) {
            $status = $install->testDatabase($request->all(), true);

            return response()->json([
                'status' => $status
            ]);
        }

        // after form submits
        if ($request->getMethod() == 'POST') {
            // install sql
            $install->installSql();

            // settings
            Settings::set('sitename', $request->get('sitename'));
            Cache::put('install_database', true, 15);

            return redirect()->route('install.me')->with('success', 'Onto the next step.');
        }

        // let's connect to the adaptcms.com servers
        Core::syncWebsiteInit('database');

        return view('core::Install/database', compact('connection_types'));
    }

    public function me(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            Cache::forever('cms_collect_data', $request->get('cms_collect_data'));
            Cache::put('webhost', $request->get('webhost'), 15);

            Core::syncWebsiteInit('me', [
                'cms_collect_data' => $request->get('cms_collect_data'),
                'webhost' => $request->get('webhost')
            ]);

            return redirect()->route('install.account')->with('success', 'Almost there!');
        }

        $install = new Install;

        // first let's connect to the adaptcms.com servers
        Core::syncWebsiteInit('me');

        Cache::put('install_me', true, 15);

        return view('core::Install/me');
    }

    public function account(Request $request)
    {
        $user = new User;

        if ($request->getMethod() == 'POST') {
            // save admin account
            $user->fill($request->except([ '_token' ]));
            
            $user->status = 1;
            $this->syncRoles([ 'admin', 'demo', 'member', 'editor' ]);

            $user->save();

            return redirect()->route('install.finished')->with('success', 'Congrats!');
        }

        // let's connect to the adaptcms.com servers
        Core::syncWebsiteInit('account');

        Cache::put('install_account', true, 15);

        return view('core::Install/account', compact('user'));
    }

    public function finished(Request $request)
    {
        // let's connect to the adaptcms.com servers
        Core::syncWebsiteInit('finished');

        Cache::put('install_finished', true, 15);

        return view('core::Install/finished');
    }
}
