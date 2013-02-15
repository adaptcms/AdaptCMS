<?php

define('path', WWW_ROOT . 'uploads' . DS);
define('watermark_path', WWW_ROOT . 'img' . DS);

class UploadBehavior extends ModelBehavior{
	public $name = 'UploadBehavior';
	public $image_ext = array(
		'jpg',
		'png',
		'gif',
		'jpeg'
	);

	public function uploadFile(&$model, $file = null, $field_id, $id, $model_name, $file_model_name)
	{
		if (is_array($file) && $file['size'] > 0) {
			$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
			$tmpName = $file['tmp_name'];

			$file['name'] = Inflector::slug(str_replace('.' . $ext, '', $file['name'])).'.'.$ext;

			if (file_exists(path . $file['name'])) {
				$fileData['name'] = $this->generateRand() . '_' . $file['name'];
			} else {
				$fileData['name'] = $file['name'];
			}

			$fileData['type'] = $ext[1];
			$fileData['size'] = $file['size'];

			$result = move_uploaded_file($tmpName, path . $fileData['name']);
			$thumb = $this->createThumbnail(
				$model, 
				path . $fileData['name'], 
				path . 'thumb/'.$fileData['name'],
				$ext
			);

			if (!empty($model->data[$model->name]['watermark'])) {
				$watermark = $this->createWatermark(
					path . $fileData['name'],
					$ext
				);
			}

			$model->data[$model_name][$model_name]['data'] = $fileData['name'];
			$model->data[$model_name][$model_name]['field_id'] = $field_id;
			$model->data[$model_name][$model_name]['article_id'] = $id;

			$model->data[$file_model_name][$file_model_name]['filesize'] = $file['size'];
			$model->data[$file_model_name][$file_model_name]['dir'] = 'uploads/';
			$model->data[$file_model_name][$file_model_name]['filename'] = $fileData['name'];
			$model->data[$file_model_name][$file_model_name]['mimetype'] = $file['type'];

			return $model->data;
		}
	}

	public function themeFile(&$model, $data)
	{
		if (!empty($data['theme'])) {
			if ($data['folder'] == "other") {
				$data['folder'] = "";
			} else {
				$data['folder'] = $data['folder'].'/';
			}

			if ($data['type'] == "upload") {
				$ext = pathinfo($data['filename']['name'], PATHINFO_EXTENSION);
				$tmpName = $data['filename']['tmp_name'];

				$data['filename']['name'] = Inflector::slug(str_replace('.' . $ext, '', $data['filename']['name'])).'.'.$ext;

				$fileData['name'] = $data['filename']['name'];

				if ($data['theme'] == "Default") {
					$path = WWW_ROOT.$data['folder'].$fileData['name'];
				} else {
					$path = WWW_ROOT.'themes/'.$data['theme'].'/'.$data['folder'].$fileData['name'];
				}

				$result = move_uploaded_file($tmpName, $path);
			} else {
				if ($data['theme'] == "Default") {
					$path = WWW_ROOT.$data['folder'].$data['file_name'].'.'.$data['file_extension'];
				} else {
					$path = WWW_ROOT.'themes/'.$data['theme'].'/'.$data['folder'].$data['file_name'].'.'.$data['file_extension'];
				}

	        	$fh = fopen($path, 'w') or die("can't open file");
				fwrite($fh, $data['content']);
				fclose($fh);
			}

			return true;
		}
	}

	public function beforeSave(&$model)
	{
		foreach($model->data as $i => $data) {
			if ($i != "_Token") {
				$multi = false;

				if (!empty($data[$model->name])) {
					$data = $data[$model->name];
					$multi = true;
					$model_data = $model->data[$i][$model->name];
				} else {
					$model_data = $model->data[$model->name];
				}

				foreach($data as $key => $file) {
					if (is_array($file) && isset($file['error']) && $file['error'] != 4) {
						if (empty($file['name'])) {
							$model_data['filename'] = $model_data['old_filename'];
						} else {
							$ext = pathinfo($file['name'], PATHINFO_EXTENSION);

							$file['name'] = Inflector::slug(str_replace('.' . $ext, '', $file['name'])).'.'.$ext;
							$tmpName = $file['tmp_name'];

							if (file_exists(path . $file['name'])) {
								$fileData['name'] = $this->generateRand() . '_' . $file['name'];
							} else {
								$fileData['name'] = $file['name'];

								if (!empty($model_data['random_filename'])) {
									$fileData['name'] = $this->generateRand(30) . '.' . $ext;
								}
							}

							$fileData['type'] = $ext;
							$fileData['size'] = $file['size'];

							$result = move_uploaded_file($tmpName, path . $fileData['name']);
							$thumb = $this->createThumbnail(
								$model, 
								path . $fileData['name'], 
								path . 'thumb/'.$fileData['name'],
								$ext
							);

							if (!empty($model_data['watermark'])) {
								$watermark = $this->createWatermark(
									path . $fileData['name'],
									$ext,
									$this->resize($model)
								);
							}

							$model_data['filesize'] = $file['size'];
							$model_data['dir'] = 'uploads/';
							$model_data['filename'] = $fileData['name'];
							$model_data['mimetype'] = $file['type'];
						}

						if ($multi) {
							$model->data[$i][$model->name] = $model_data;
						} else {
							$model->data[$model->name] = $model_data;
						}

						$return = 1;
					} else {
						// die(debug($model_data));
					}
				}
			}
		}

		if (empty($model->data[$model->name]['created']) && !empty($model->data[$model->name]['filename'])) {
			$ext = pathinfo(path . $model->data[$model->name]['filename'], PATHINFO_EXTENSION);

			$model->data[$model->name]['filename'] = Inflector::slug(
				str_replace(
					'.' . $ext, 
					'', 
					$model->data[$model->name]['filename']
				)).'.'.$ext;
			$resize = $this->resize($model);

			if (!empty($model->data[$model->name]['random_filename'])) {
				$model->data[$model->name]['filename'] = $this->generateRand(25) . '.' . $ext;
			}

			if (!empty($model->data[$model->name]['watermark'])) {
				$watermark = $this->createWatermark(
					path . $model->data[$model->name]['filename'],
					$ext,
					$resize
				);
			} elseif (!empty($resize)) {
				$resize_image = $this->resizeImage(
					path . $model->data[$model->name]['filename'],
					$ext,
					$resize
				);
			}

    		if (!empty($model->data[$model->name]['old_filename']) && 
    			$model->data[$model->name]['old_filename'] != $model->data[$model->name]['filename']) {

    			if (file_exists(path . 'thumb' . DS .$model->data[$model->name]['old_filename'])) {
	    			rename(
	    				path . $model->data[$model->name]['old_filename'],
	    				path . $model->data[$model->name]['filename']
	    			);
	    		} else {
	    			$model->data[$model->name]['filename'] = $model->data[$model->name]['old_filename'];
	    		}

    			if (file_exists(path . 'thumb' . DS .$model->data[$model->name]['old_filename'])) {
    				rename(
    					path . 'thumb' . DS .$model->data[$model->name]['old_filename'],
    					path . 'thumb' . DS .$model->data[$model->name]['filename']
    				);
    			}
    		}
		}
		// die(debug($model->data));

		return $model;
	}

	public function beforeDelete(&$model)
	{
		if ($model->name == 'File' && 1 == 2) {
			$data = $model->find('first', array(
				'conditions' => array(
					'File.id' => $model->id
					)
				)
			);

			if (file_exists(path . $data[$model->name]['filename'])) {
				unlink(path . $data[$model->name]['filename']);
			}
			if (file_exists(path . 'thumb/'.$data[$model->name]['filename'])) {
				unlink(path . 'thumb/'.$data[$model->name]['filename']);
			}
		}
	}

	public function createThumbnail($model, $fileName, $thumbFile, $ext)
	{
		if (in_array($ext, $this->image_ext)) {
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

	public function createWatermark($fileName, $ext, $params = array())
	{
		if (in_array($ext, $this->image_ext)) {
			$thumb = App::import('Vendor','phpthumb', array('file' => 'phpThumb' . DS . 'phpthumb.class.php'));
			$phpThumb = new phpthumb;
			$phpThumb->setSourceFilename($fileName);

			if (!empty($params)) {
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

	public function resizeImage($fileName, $ext, $params = array())
	{
		if (in_array($ext, $this->image_ext)) {
			$thumb = App::import('Vendor','phpthumb', array('file' => 'phpThumb' . DS . 'phpthumb.class.php'));
			$phpThumb = new phpthumb;
			$phpThumb->setSourceFilename($fileName);

			if (!empty($params)) {
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

	public function resize($model)
	{
		if (!empty($model->data[$model->name]['resize_width']) || !empty($model->data[$model->name]['resize_height'])) {
			if (empty($model->data[$model->name]['resize_width'])) {
				$model->data[$model->name]['resize_width'] = '';
			} else {
				$model->data[$model->name]['resize_height'] = '';
			}

			return array(
				'width' => $model->data[$model->name]['resize_width'],
				'height' => $model->data[$model->name]['resize_height']
			);
		} else {
			return array();
		}
	}

	public function generateRand($length = 6)
	{
		$characters = "0123456789ABCDEFGHIJKLMNOPQRSTUVWZYZabcdefghijklmnopqrstuvwxyz";
		$real_string_legnth = strlen($characters) - 1;
		$string = "";

		for ($i = 0; $i < $length; $i++) {
			$string .= $characters[mt_rand(0, $real_string_legnth)];
		}

		return $string;
	}
}