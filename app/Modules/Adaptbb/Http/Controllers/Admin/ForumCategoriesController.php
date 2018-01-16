<?php

namespace App\Modules\Adaptbb\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Adaptbb\Models\ForumCategory;

class ForumCategoriesController extends Controller
{
    /**
    * Index
    *
    * @return View
    */
    public function index()
    {
        $items = ForumCategory::orderBy('ord', 'ASC')->paginate(15);

        return view('adaptbb::Admin/ForumCategories/index', compact('items'));
    }

    /**
    * Add
    *
    * @param Request $request
    *
    * @return mixed
    */
    public function add(Request $request)
    {
        $item = new ForumCategory;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
            'name' => 'required|unique:plugin_adaptbb_forum_categories|max:255'
        ]);

            $item->name = $request->get('name');
            $item->slug = str_slug($item->name, '-');
            $item->ord = (ForumCategory::count());

            $item->save();

            return redirect()
              ->route('plugin.adaptbb.admin.forum_categories.index')
              ->with('success', 'Category has been saved');
        }

        return view('adaptbb::Admin/ForumCategories/add', compact('item'));
    }

    /**
    * Edit
    *
    * @param Request $request
    * @param integer $id
    *
    * @return mixed
    */
    public function edit(Request $request, $id)
    {
        $item = ForumCategory::find($id);

        if (empty($item)) {
            abort(404);
        }

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
            'name' => 'required|unique:plugin_adaptbb_forum_categories|max:255'
        ]);

            $item->name = $request->get('name');
            $item->slug = str_slug($item->name, '-');

            $item->save();

            return redirect()
              ->route('plugin.adaptbb.admin.forum_categories.index')
              ->with('success', 'Category has been saved');
        }

        return view('adaptbb::Admin/ForumCategories/edit', compact('item'));
    }

    /**
    * Delete
    *
    * @param integer $id
    *
    * @return Redirect
    */
    public function delete($id)
    {
        $item = ForumCategory::find($id);

        if (empty($item)) {
            abort(404);
        }

        $item->delete();

        return redirect()
          ->route('plugin.adaptbb.admin.forum_categories.index')
          ->with('success', 'Category has been deleted.');
    }

    /**
    * Order
    *
    * @param Request $request
    *
    * @return mixed
    */
    public function order(Request $request)
    {
        $items = ForumCategory::orderBy('ord', 'ASC')->get();

        if ($request->getMethod() == 'POST') {
            $items = json_decode($request->get('items'), true);

            foreach ($items as $index => $id) {
                $item = ForumCategory::find($id);

                $item->ord = $index;

                $item->save();
            }

            return response()->json([
                'status' => true
            ]);
        }

        return view('adaptbb::Admin/ForumCategories/order', compact('items'));
    }
}
