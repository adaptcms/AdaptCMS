<?php

namespace App\Modules\Files\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumFile extends Model
{
    /**                                                                                                                                                                                                                                             
     * The table associated with the model.                                                                                                                                                                                                         
     *                                                                                                                                                                                                                                              
     * @var string                                                                                                                                                                                                                                  
     */
    protected $table = 'album_files';
    
    public function album()
    {
        return $this->belongsTo('App\Modules\Files\Models\Album');
    }
    
    public function file()
    {
        return $this->belongsTo('App\Modules\Files\Models\File');
    }
}