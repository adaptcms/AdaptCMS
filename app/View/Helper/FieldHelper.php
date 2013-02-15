<?php

class FieldHelper extends AppHelper
{
	public $name = 'FieldHelper';

	public function getTextAreaData($data)
	{
		if (empty($data)) {
			return false;
		}

		if (!empty($data['ArticleValue'])) {
			foreach($data['ArticleValue'] as $value) {
				if ($value['Field']['field_type'] == 'textarea') {
					return $value['data'];
				}
			}
		}
	}
	
}