<?php

define('path', WWW_ROOT . 'uploads' . DS);
define('watermark_path', WWW_ROOT . 'img' . DS);

class UploadBehavior extends ModelBehavior
{
	/**
	* Name of the Behavior, 'UploadBehavior'
	*/
	public $name = 'UploadBehavior';

	/**
	* Array of accepted image types
	*/
	public $image_ext = array(
        'jpg',
        'png',
        'gif',
        'jpeg'
        );

	/**
	* This function used by articles, uploads the file and attempts to do anything else depending on options picked.
	* This includes random file name, watermark, attempts to thumbnail and formats data.
	*
	* @param model Holds the model
	* @param file The file itself (array)
	* @param field_id Used for formatting data
	* @param id The article ID
	* @param model_name Used for flexibility
	* @param file_model_name Same as above
	*/
	public function uploadFile(&$model, $file = null, $field_id, $id = null, $model_name, $file_model_name, $id_type = null)
	{
        if (is_array($file) && $file['size'] > 0)
        {
           $prefix = '';
           $path = path;
           $dir = 'uploads/';

           if (!empty($id_type))
           {
              $path = path . 'custom' . DS;
              $dir = $dir . 'custom/';

              if (!empty($id))
              {
                 $prefix = $id . '_' . $id_type . '_';
             }
         }

         $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
         $tmpName = $file['tmp_name'];

         $file['name'] = Inflector::slug(str_replace('.' . $ext, '', $file['name'])).'.'.$ext;

         if (file_exists($path . $file['name']))
         {
            $fileData['name'] = $this->generateRand() . '_' . $prefix . $file['name'];
        } else {
            $fileData['name'] = $prefix . $file['name'];
        }

        $fileData['type'] = $ext[1];
        $fileData['size'] = $file['size'];

        $result = move_uploaded_file($tmpName, $path . $fileData['name']);
        
        if (!empty($result))
        {
            $this->createThumbnail(
                $path . $fileData['name'],
                $path . 'thumb/'.$fileData['name'],
                $ext
                );
        }

        if (!empty($model->data[$model->name]['watermark']))
        {
            $this->createWatermark(
                $path . $fileData['name'],
                $ext
                );
        }
        
        $data = array();

        $data[$model_name]['data'] = $fileData['name'];
        $data[$model_name]['field_id'] = $field_id;

        if (!empty($id))
        {
           if (empty($id_type))
           {
               $data[$model_name]['article_id'] = $id;
           }
           else
           {
              $data[$model_name]['module_id'] = $id;
          }
      }

      $data[$file_model_name]['filesize'] = $file['size'];
      $data[$file_model_name]['dir'] = $dir;
      $data[$file_model_name]['filename'] = $fileData['name'];
      $data[$file_model_name]['mimetype'] = $file['type'];

      return $data;
  }
}

	/**
	* The big one. This is mainly used by Files for when uploading file(s). It handles all
	* field options, file renaming and such. Returns the model.
	*
	* @param model Holds the model
	*/
	public function beforeSave(&$model)
	{
        if ($model->name != 'ArticleValue' && $model->name != 'ModuleValue')
        {
            foreach($model->data as $i => $data)
            {
                if ($i != "_Token")
                {
                    $multi = false;

                    $model_data = $model->data[$model->name];

                    foreach($data as $key => $file)
                    {
                        if (is_array($file) && isset($file['error']) && $file['error'] == 0 || 
                            !empty($file[$model->name]) && is_array($file[$model->name]) && isset($file[$model->name]['filename']['error']) && $file[$model->name]['filename']['error'] == 0)
                        {
                            if (isset($file[$model->name]['filename']['error']))
                            {
                                $file = array_merge($file[$model->name], $file[$model->name]['filename']);
                                $multi = true;
                            }

                            if (empty($file['name']))
                            {
                                $model_data['filename'] = $model_data['old_filename'];
                            } else {
                                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

                                $file['name'] = Inflector::slug(str_replace('.' . $ext, '', $file['name'])).'.'.$ext;
                                $tmpName = $file['tmp_name'];

                                if (file_exists(path . $file['name']))
                                {
                                    $fileData['name'] = $this->generateRand() . '_' . $file['name'];
                                } else {
                                    $fileData['name'] = $file['name'];

                                    if (!empty($model_data['random_filename']))
                                    {
                                        $fileData['name'] = $this->generateRand(30) . '.' . $ext;
                                    }
                                }

                                $fileData['type'] = $ext;
                                $fileData['size'] = $file['size'];

                                $result = move_uploaded_file($tmpName, path . $fileData['name']);
                                
                                if ($result)
                                {
                                    $this->createThumbnail(
                                        path . $fileData['name'],
                                        path . 'thumb/'.$fileData['name'],
                                        $ext
                                        );


                                    $resize = $this->resize($model_data);

                                    if (!empty($resize))
                                    {
                                        $this->resizeImage(
                                            path . $fileData['name'],
                                            $ext,
                                            $resize
                                        );
                                    }

                                    $this->createThumbnail(
                                        path . $fileData['name'],
                                        path . 'thumb/'.$fileData['name'],
                                        $ext
                                    );

                                    if (!empty($model_data['watermark']))
                                    {
                                        $this->createWatermark(
                                            path . $fileData['name'],
                                            $ext,
                                            $this->resize($model_data)
                                            );
                                    }


                                    $model_data['filesize'] = $file['size'];
                                    $model_data['dir'] = 'uploads/';
                                    $model_data['filename'] = $fileData['name'];
                                    $model_data['mimetype'] = $file['type'];

                                    $file_data = array(
                                        'filesize' => $model_data['filesize'],
                                        'dir' => $model_data['dir'],
                                        'filename' => $model_data['filename'],
                                        'mimetype' => $model_data['mimetype']
                                    );
                                }
                            }

                            if ($multi)
                            {
                                $orig_data = $model->data[$model->name][$key];          
                                $model->data[$model->name][$key] = array_merge($orig_data, $file_data);
                            } else {
                                $model->data[$model->name] = $model_data;
                            }
                        }
                    }
                }
            }

            // die(debug($model->data));

            if (empty($model->data[$model->name]['created']) && !empty($model->data[$model->name]['filename']))
            {
                $ext = pathinfo(path . $model->data[$model->name]['filename'], PATHINFO_EXTENSION);

                $model->data[$model->name]['filename'] = Inflector::slug(
                    str_replace(
                        '.' . $ext, 
                        '', 
                        $model->data[$model->name]['filename']
                        )).'.'.$ext;
                $resize = $this->resize($model->data[$model->name]);

                if (!empty($model->data[$model->name]['random_filename']))
                {
                    $model->data[$model->name]['filename'] = $this->generateRand(25) . '.' . $ext;
                }

                if (!empty($model->data[$model->name]['watermark']))
                {
                    $watermark = $this->createWatermark(
                        path . $model->data[$model->name]['filename'],
                        $ext,
                        $resize
                        );
                } elseif (!empty($resize))
                {
                    $this->resizeImage(
                        path . $model->data[$model->name]['filename'],
                        $ext,
                        $resize
                        );

                    $this->createThumbnail(
                        path . $model->data[$model->name]['filename'],
                        path . 'thumb/' . $model->data[$model->name]['filename'],
                        $ext
                    );
                }

                if (!empty($model->data[$model->name]['old_filename']) && 
                    $model->data[$model->name]['old_filename'] != $model->data[$model->name]['filename'])
                {

                    if (file_exists(path . $model->data[$model->name]['old_filename']))
                    {
                        rename(
                            path . $model->data[$model->name]['old_filename'],
                            path . $model->data[$model->name]['filename']
                            );
                    } else {
                        $model->data[$model->name]['filename'] = $model->data[$model->name]['old_filename'];
                    }

                    if (file_exists(path . 'thumb' . DS .$model->data[$model->name]['old_filename']))
                    {
                        rename(
                            path . 'thumb' . DS .$model->data[$model->name]['old_filename'],
                            path . 'thumb' . DS .$model->data[$model->name]['filename']
                            );
                    }
                }
            }
        }

        // die(debug($model->data));
        
        return true;
    }

	/**
	* Placeholder, this should work in the future
	*/
	public function beforeDelete(&$model)
	{
		if ($model->name == 'File' && 1 == 2)
		{
			$data = $model->find('first', array(
				'conditions' => array(
					'File.id' => $model->id
					)
				)
			);

			if (file_exists(path . $data[$model->name]['filename']))
			{
				unlink(path . $data[$model->name]['filename']);
			}
			if (file_exists(path . 'thumb/'.$data[$model->name]['filename']))
			{
				unlink(path . 'thumb/'.$data[$model->name]['filename']);
			}
		}
	}

	/**
	* Using phpThumb, this function creates a thumbnail with the specified parameters
	*
	* @param model Holds the model
	* @param fileName Name of file
	* @param thumbFile Path to where the uploaded image thumbnail should be created
	* @param ext The extension of this file, must be an image
	*/
	public function createThumbnail($fileName, $thumbFile, $ext)
	{
		if (in_array($ext, $this->image_ext))
		{
			$params = array(
				'thumbWidth' => 150,
				'thumbHeight' => 225,
				'maxDimension' => '',
				'thumbnailQuality' => 100,
				'zoomCrop' => false
             );

			$thumb = App::import('Vendor','phpthumb', array('file' => 'phpThumb' . DS . 'phpthumb.class.php'));

			$phpThumb = new phpthumb;
			$phpThumb->setSourceFilename($fileName);

			$phpThumb->w = $params['thumbWidth'];
			$phpThumb->h = $params['thumbHeight'];
			$phpThumb->setParameter('zc', false);

			$phpThumb->q = $params['thumbnailQuality'];
			$phpThumb->f = $ext;
			$phpThumb->config_output_format = $ext;

			$phpThumb->GenerateThumbnail();
			$phpThumb->RenderToFile($thumbFile);
		}
	}

	/**
	* Using phpThumb, this function will watermark an image if the watermark exists
	*
	* @param fileName Name of file
	* @param ext The extension of this file, must be an image
	* @param params array of parameters (only supports width and height, currently)
	*/
	public function createWatermark($fileName, $ext, $params = array())
	{
		if (in_array($ext, $this->image_ext) && file_exists(watermark_path . "watermark.png"))
		{
			$thumb = App::import('Vendor','phpthumb', array('file' => 'phpThumb' . DS . 'phpthumb.class.php'));
			$phpThumb = new phpthumb;
			$phpThumb->setSourceFilename($fileName);

			if (!empty($params))
			{
				$phpThumb->w = $params['width'];
				$phpThumb->h = $params['height'];
				$phpThumb->zc = 1;
			}

			$phpThumb->q = 100;
			$phpThumb->fltr = array("wmi|". watermark_path . "watermark.png|BR|50|5");
			$phpThumb->f = $ext;
			$phpThumb->config_output_format = $ext;

			$phpThumb->GenerateThumbnail();
			$phpThumb->RenderToFile($fileName);
		}
	}

    /**
     * Using phpThumb, this function resizes an image to the specific parameters.
     *
     * @param Name $fileName
     * @param The $ext
     * @param array $params
     * @internal param $fileName of file
     * @internal param $ext extension of this file, must be an image
     * @internal param $params of parameters (only supports width and height, currently)
     */
	public function resizeImage($fileName, $ext, $params = array())
	{
		if (in_array($ext, $this->image_ext))
		{
			$thumb = App::import('Vendor','phpthumb', array('file' => 'phpThumb' . DS . 'phpthumb.class.php'));
			$phpThumb = new phpthumb;
			$phpThumb->setSourceFilename($fileName);

			if (!empty($params))
			{
				$phpThumb->w = $params['width'];
				$phpThumb->h = $params['height'];
			}

			$phpThumb->q = 100;
			$phpThumb->f = $ext;
			$phpThumb->config_output_format = $ext;

			$phpThumb->GenerateThumbnail();
			$phpThumb->RenderToFile($fileName);	
		}	
	}

	/**
	* This is a convinience function used by resizeImage and createWatermark that returns an array of
	* width and height to size to.
	*
	* @param data
	* @return array
	*/
	public function resize($data)
	{
		if (!empty($data['resize_width']) || !empty($data['resize_height']))
		{
			if (empty($data['resize_width']))
			{
				$data['resize_width'] = '';
			} else {
				$data['resize_height'] = '';
			}

			return array(
				'width' => $data['resize_width'],
				'height' => $data['resize_height']
             );
		} else {
			return array();
		}
	}

    /**
     * The random generate function is used when a file is marked as generating a random filename.
     *
     * @param int|string $length
     * @internal $length The length of this random string, 6 by default.
     * @return string
     */
	public function generateRand($length = 6)
	{
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZabcdefghijklmnopqrstuvwxyz";
		$real_string_legnth = strlen($characters) - 1;
		$string = "";

		for ($i = 0; $i < $length; $i++)
		{
			$string .= $characters[mt_rand(0, $real_string_legnth)];
		}

		return $string;
	}
}