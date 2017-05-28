<?php
namespace App\Modules\Adaptbb\Events;

use Storage;
use Cache;

class AdaptbbInstall
{
    public function __construct()
	{
        $theme = Cache::get('theme', 'default');

        $origViewsPath = 'Adaptbb/Resources/Views/';
        $newViewsPath = $theme . '/views/adaptbb/';
        $partialsViewsPath = $theme . '/partials/adaptbb/';

        if (!Storage::disk('themes')->exists($newViewsPath)) {
            Storage::disk('themes')->makeDirectory($newViewsPath);
        }

        $folders = Storage::disk('plugins')->directories($origViewsPath);
        foreach($folders as $folder) {
            $folder = basename($folder);
            $files = Storage::disk('plugins')->files($origViewsPath . $folder);

            if ($folder == 'Partials') {
                if (!Storage::disk('themes')->exists($partialsViewsPath)) {
                    Storage::disk('themes')->makeDirectory($partialsViewsPath);
                }

                if (!empty($files)) {
                    foreach($files as $file) {
                        $file = basename($file);

                        $contents = Storage::disk('plugins')->get($origViewsPath . $folder . '/' . $file);

                        Storage::disk('themes')->put($partialsViewsPath . '/' . $file, $contents);
                    }
                }
            } else {
                if (!Storage::disk('themes')->exists($newViewsPath . str_slug($folder))) {
                    Storage::disk('themes')->makeDirectory($newViewsPath . str_slug($folder));
                }

                if (!empty($files)) {
                    foreach($files as $file) {
                        $file = basename($file);

                        $contents = Storage::disk('plugins')->get($origViewsPath . $folder . '/' . $file);

                        Storage::disk('themes')->put($newViewsPath . str_slug($folder) . '/' . $file, $contents);
                    }
                }
            }
        }
    }
}
