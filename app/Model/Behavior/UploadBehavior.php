<?php

define('path', WWW_ROOT . 'uploads' . DS);

class UploadBehavior extends ModelBehavior{
	public $name = 'UploadBehavior';

	public function uploadFile(&$model, $file = null, $field_id, $id, $model_name, $file_model_name)
	{
		if (is_array($file) && $file['size'] > 0) {
			$ext = explode(".", $file['name']);
			$tmpName = $file['tmp_name'];

			$fileData['name'] = Inflector::slug($ext[0]).'.'.$ext[1];
			$fileData['type'] = $ext[1];
			$fileData['size'] = $file['size'];

			$result = move_uploaded_file($tmpName, path . $fileData['name']);
			$thumb = $this->createThumbnail(
				$model, 
				path . $fileData['name'], 
				path . 'thumb/'.$fileData['name'],
				$ext[1]);

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
				$ext = explode(".", $data['filename']['name']);
				$tmpName = $data['filename']['tmp_name'];

				$fileData['name'] = Inflector::slug($ext[0]).'.'.$ext[1];

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
		$data = $model->data[$model->name];
		$continue = 0;

		if (!empty($data)) {
			foreach($data as $key => $file) {
				
				if (is_array($file) && !empty($file['error']) && $file['error'] != 4) {
					if (empty($file['name'])) {
						$model->data[$model->name]['filename'] = $model->data[$model->name]['old_filename'];

						return $model;
					} else {
						$ext = explode(".", $file['name']);
						$tmpName = $file['tmp_name'];

						$fileData['name'] = Inflector::slug($ext[0]).'.'.$ext[1];
						$fileData['type'] = $ext[1];
						$fileData['size'] = $file['size'];

						$result = move_uploaded_file($tmpName, path . $fileData['name']);
						$thumb = $this->createThumbnail(
							$model, 
							path . $fileData['name'], 
							path . 'thumb/'.$fileData['name'],
							$ext[1]);

						$model->data[$model->name]['filesize'] = $file['size'];
						$model->data[$model->name]['dir'] = 'uploads/';
						$model->data[$model->name]['filename'] = $fileData['name'];
						$model->data[$model->name]['mimetype'] = $file['type'];

						return $model;
					}
				} else {
					$continue = 1;
				}
			}
		}
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
		$params = array(
				'thumbWidth' => 150,
				'thumbHeight' => 225,
				'maxDimension' => '',
				'thumbnailQuality' => 100,
				'zoomCrop' => false,
				'watermark' => ''
			);
		$thumb = App::import('Vendor','phpthumb', array('file' => 'phpThumb' . DS . 'phpthumb.class.php'));

		$phpThumb = new phpthumb;
		$phpThumb->setSourceFilename($fileName);

		$phpThumb->w = $params['thumbWidth'];
		$phpThumb->h = $params['thumbHeight'];
		$phpThumb->setParameter('zc', false);

		if (!empty($params['watermark'])) {
			$phpThumb->fltr = array("wmi|". IMAGES . $params['watermark']."|BR|50|5");
		}

		$phpThumb->q = $params['thumbnailQuality'];
		$phpThumb->config_output_format = $ext[1];

		$phpThumb->GenerateThumbnail();
		$phpThumb->RenderToFile($thumbFile);
	}
}