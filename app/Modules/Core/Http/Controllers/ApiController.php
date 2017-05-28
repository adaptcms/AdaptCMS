<?php
	
namespace App\Modules\Core\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiController extends Controller
{
	public $validModels = [
		'posts' => '\App\Modules\Posts\Models\Post',
        'pages' => '\App\Modules\Posts\Models\Page'
	];

	public function index($module, Request $request)
	{
		$response = [];
		if (empty($this->validModels[$module])) {
			$response = [
				'status' => false,
				'message' => 'Invalid module of: ' . $module	
			];
		} else {
			$model = new $this->validModels[$module];
			
			$results = $model->searchLogic($request->all());
			
			$response = [
				'status' => true,
				'results' => $results	
			];
		}
		
		return response()->json($response);
	}
}