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
        'ArticleValue' => array(
            'dependent' => true
            // 'conditions' => array('ArticleValue.file_id >' => 0)
        ),
        'ModuleValue' => array(
            'dependent' => true
        )
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
        foreach($data as $key => $row)
        {
            if (!empty($row['File']) && !empty($data['File']['filename']['name']))
            {
                $unset_file = true;

                if ($key == 0 && !empty($data['File']['filename']['name']))
                {
                    $data[$key]['File'] = array_merge($data[$key]['File'], $data['File']);
                }

                if (!isset($row['File']['user_id']) && !empty($data['File']['user_id']))
                {
                    $data[$key]['File']['user_id'] = $data['File']['user_id'];
                }
            }

            if(!empty($row['File']['id']) && !empty($row['File']['content']))
            {
                $fh = fopen(WWW_ROOT . $row['File']['dir']. $row['File']['filename'], 'w');
                if ($fh)
                {
                    fwrite($fh, $row['File']['content']);
                    fclose($fh);
                }
            }
        }

        if (isset($unset_file))
        {
            unset($data['File']);
        }

        return $data;
    }

    public function beforeSave()
    {
        if (!empty($this->data['File']['content']))
        {
            if (!empty($this->data['File']['file_name']))
            {
                $filename = $this->data['File']['file_name'];
            }
            else
            {
                $filename = $this->data['File']['filename'];
            }

            if (!empty($this->data['File']['file_extension']))
            {
                $file = $this->slug($filename) . '.' . $this->data['File']['file_extension'];
            }
            else
            {
                $file = $filename;
            }

            $path = WWW_ROOT . $this->data['File']['dir'] . $file;

            if (!empty($this->data['File']['old_filename']) && $this->data['File']['old_filename'] != $file)
            {
                rename(
                    WWW_ROOT . $this->data['File']['dir'] . $this->data['File']['old_filename'], 
                    $path
                );
            }

            $fh = fopen($path, 'w');
            if ($fh)
            {
                fwrite($fh, $this->data['File']['content']);
                fclose($fh);
            }

            $this->data['File']['filename'] = $file;
            $this->data['File']['mimetype'] = $this->mime_type($file);
            $this->data['File']['filesize'] = filesize($path);

            if (isset($this->data[0]['File']))
            {
                unset($this->data[0]);
            }
        }

        return true;
    }
}