<?php

namespace App\Modules\Core\Models;

use App\Modules\Core\Events\InstallSeedEvent;
use App\Modules\Users\Models\User;

use Settings;
use Storage;
use DB;
use Artisan;

class Install
{
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

    public function getConnectionTypes()
    {
        return [
            'mysql' => 'MySQL'
        ];
    }

    public function testDatabase($data = [], $write_file = false)
    {
        if ($write_file) {
            // get .env file
            $env_file = Storage::disk('base')->get('.env');

            // get all the rows
            $env_file = explode(PHP_EOL, $env_file);

            // format it into key value pairs
            $new_rows = [];
            foreach($env_file as $row) {
                if (empty($row)) {
                    $new_rows[] = '';
                } else {
                    $row = explode('=', $row);

                    $new_rows[$row[0]] = $row[1];
                }
            }

            // update the .env data
            foreach($new_rows as $key => $val) {
                if (!empty($data[$key])) {
                    $new_rows[$key] = $data[$key];
                }
            }

            // rebuild the file
            $new_file = '';
            foreach($new_rows as $key => $val) {
                if (empty($val) && is_int($key)) {
                    $new_file .= PHP_EOL;
                } else {
                    $new_file .= $key . '=' . $val . PHP_EOL;
                }
            }

            $write = Storage::disk('base')->put('.env', $new_file);

            if (!$write) {
                abort(500, 'Could not write to .env file. Please give write permissions');
            }
        }

        // now we test the connection
        try {
            config([ 'database.connections.mysql.host' => $new_rows['DB_HOST'] ]);

            DB::connection()->getPdo();

            $status = true;
        } catch(\Exception $e) {
            $status = false;
        }

        return $status;
    }

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

        return $status;
    }
}
