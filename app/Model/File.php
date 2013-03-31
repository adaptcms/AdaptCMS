<?php

class File extends AppModel
{
    /**
    * Name of our Model, table will look like 'adaptcms_files'
    */
    public $name = 'File';

    /**
    * Files may have many article values. This is for fields when adding/editing articles of file type.
    */
    public $hasMany = array(
        'ArticleValue'
    );

    /**
    * Files may belong to many media albums. Setting unique to 'keepExisting' means that if
    * file #1 belongs to media #1 and then is added to media #2, cake will keep the first record
    * and not delete/re-add it.
    */
    public $hasAndBelongsToMany = array(
        'Media' => array(
            'className' => 'Media',
            'joinTable' => 'media_files',
            'unique' => 'keepExisting'
        )
    );

    /**
    * All files belong to a user
    */
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    /**
    * To abstract functionality, we have an upload behavior for files
    */
    public $actsAs = array(
        'Upload'
    );
    
    /**
    * Small function that retrieves mime type of a file based on extension
    *
    * @param filename
    * @return string of mime type
    *
    * @author svogal
    * @source http://www.php.net/manual/de/function.mime-content-type.php#87856
    */
    public function mime_type($filename)
    {
        $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }

    /**
    * This works in conjuction with the Block feature. Doing a simple find with any conditions filled in by the user that
    * created the block. This is customizable so you can do a contain of related data if you wish.
    *
    * @return associative array
    */
    public function getBlockData($data, $user_id)
    {
        $cond = array(
            'conditions' => array(
                'File.deleted_time' => '0000-00-00 00:00:00'
            )
        );

        if (!empty($data['limit'])) {
            $cond['limit'] = $data['limit'];
        }

        if (!empty($data['order_by'])) {
            if ($data['order_by'] == "rand") {
                $data['order_by'] = 'RAND()';
            }

            $cond['order'] = 'File.'.$data['order_by'].' '.$data['order_dir'];
        }

        if (!empty($data['data'])) {
            $cond['conditions']['File.id'] = $data['data'];
        }

        return $this->find('all', $cond);
    }

    /**
    * This function runs only when first adding files and goes through all (if multiple) and saves file contents,
    * associated media libraries, etc.
    *
    * @return data
    */
    public function beforeAdd($data)
    {
        foreach($data as $i => $row)
        {
            if (!empty($row['File']['content']))
            {
                $file = $this->slug($row['File']['file_name']) . '.' . $row['File']['file_extension'];
                $path = WWW_ROOT . $row['File']['dir'] . $file;

                $fh = fopen($path, 'w') or die("can't open file");
                fwrite($fh, $row['File']['content']);
                fclose($fh);

                $data[$key]['File']['filename'] = $file;
                $data[$key]['File']['mimetype'] = $this->File->mime_type($file);
                $data[$key]['File']['filesize'] = filesize($path);
            }

            if (!empty($row) && empty($row['File']['id']))
            {
                unset($data[$key]['File'], $data[$key]['_Token']);

                foreach($row as $i => $lib)
                {
                    if (!strstr($row['File']['filename']['type'], 'image') && !empty($row['File']['library']))
                    {
                        unset($data[$key][$i]['Media']);
                    }
                }
            } elseif(!empty($row['File']['id']) && !empty($row['File']['content']))
            {
                $fh = fopen(WWW_ROOT . $row['File']['dir']. $row['File']['filename'], 'w') or die("can't open file");
                fwrite($fh, $row['File']['content']);
                fclose($fh);
            }
        }

        return $data;
    }
}