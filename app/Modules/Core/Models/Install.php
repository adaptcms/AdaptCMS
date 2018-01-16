<?php

namespace App\Modules\Core\Models;

use App\Modules\Core\Events\InstallSeedEvent;
use App\Modules\Users\Models\User;

use Artisan;
use DB;
use DotenvEditor;
use Settings;
use Storage;

class Install
{
    /**
    * Get Server Checks
    *
    * @return array
    */
    public function getServerChecks()
    {
        // prep work
        $composer = Storage::disk('base')->get('composer.json');
        $composer = json_decode($composer, true);

        $checks = [
            'php' => [
                'min' => '',
                'current' => '',
                'status' => true
            ],
            'permissions_list' => [
                '.env',
                'app/Modules',
                'public/themes',
                'public/uploads',
                'storage',
                'bootstrap/cache'
            ],
            'permissions' => [],
            'permissions_status' => true,
            'status' => true
        ];

        // php check
        $php_min = $composer['require']['php'];
        $php_min = preg_replace('/[^0-9.]/', '', $php_min);

        $checks['php']['status'] = version_compare(PHP_VERSION, $php_min, '>=');
        $checks['php']['current'] = PHP_VERSION;
        $checks['php']['min'] = $php_min;

        if (!$checks['php']['status']) {
            $checks['status'] = false;
        }

        // permissions
        foreach($checks['permissions_list'] as $path) {
            $status = is_writable(base_path() . '/' . $path);

            if (!$status) {
                $checks['permissions_status'] = false;
                $checks['status'] = false;
            }

            $checks['permissions'][] = [
                'full_path' => base_path() . '/' . $path,
                'is_dir' => is_dir(base_path() . '/' . $path),
                'status' => $status
            ];
        }

        return $checks;
    }

    /**
    * Get Connection Types
    *
    * @return array
    */
    public function getConnectionTypes()
    {
        return [
            'mysql' => 'MySQL'
        ];
    }

    /**
    * Test Database
    *
    * @param array $data
    * @param bool $write_file
    *
    * @return bool
    */
    public function testDatabase($data = [], $write_file = false)
    {
        if ($write_file) {
            foreach($data as $key => $value) {
                DotenvEditor::setKey($key, $value);
            }

            $write = DotenvEditor::save();

            if (!$write) {
                abort(500, 'Could not write to .env file. Please give write permissions');
            }
        }

        // now we test the connection
        try {
            config([ 'database.connections.mysql.host' => $data['DB_HOST'] ]);

            DB::connection()->getPdo();

            $status = true;
        } catch(\Exception $e) {
            $status = false;
        }

        return $status;
    }

    /**
    * Install Sql
    *
    * @return bool
    */
    public function installSql()
    {
        try {
            $status = Artisan::call('migrate');
        } catch(\Exception $e) {
        	$error = '<br /><br />' . $e->getMessage();
        	
	    	abort(500, 'Unable to install SQL part one. Go back and ensure database credentials are accurate.' . $error);
        }

        try {
            $status = Artisan::call('module:migrate');
        } catch(\Exception $e) {
        	$error = '<br /><br />' . $e->getMessage();
        	
            abort(500, 'Unable to migrate plugin database changes.' . $error);
        }

        try {
            event(new InstallSeedEvent());
        } catch(\Exception $e) {
        	$error = '<br /><br />' . $e->getMessage();
        	
            abort(500, 'Unable to install SQL part two. Go back and ensure database credentials are accurate.' . $error);
        }

        try {
            $status = Artisan::call('vendor:publish');
        } catch(\Exception $e) {
        	$error = '<br /><br />' . $e->getMessage();
        	
            abort(500, 'Cannot publish module assets. Try chmodding the /public/assets/modules/ folder to 755 recursively.' . $error);
        }

        try {
            Artisan::call('module:seed');
        } catch(\Exception $e) {
            // do nothing
        }

        return $status;
    }
}
