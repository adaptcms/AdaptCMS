<?php

namespace App\Modules\Adaptbb\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Adaptbb\Models\Forum;
use App\Modules\Adaptbb\Models\ForumCategory;

class ForumsAdminController extends Controller
{
    public $categories = [];

    public function __construct()
    {
        $this->categories = ForumCategory::pluck('name', 'id');
    }

    public function index()
    {
        $items = Forum::orderBy('ord', 'ASC')->paginate(15);

        $categories = $this->categories;

        return view('adaptbb::ForumsAdmin/index', compact('items', 'categories'));
    }

    public function add(Request $request)
    {
        $item = new Forum;

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
            'name' => 'required|unique:plugin_adaptbb_forums|max:255',
            'category_id' => 'required'
        ]);

            $item->fill($request->except('_token'));

            $item->slug = str_slug($item->name, '-');
            $item->ord = (Forum::count());

            $item->save();

            return redirect()
              ->route('plugin.adaptbb.admin.forums.index')
              ->with('success', 'Forum has been saved');
        }

        $categories = $this->categories;

        return view('adaptbb::ForumsAdmin/add', compact('item', 'categories'));
    }

    public function edit(Request $request, $id)
    {
        $item = Forum::find($id);

        if (empty($item)) {
            abort(404);
        }

        if ($request->getMethod() == 'POST') {
            $this->validate($request, [
            'name' => 'required|unique:plugin_adaptbb_forums|max:255'
        ]);

            $item->fill($request->except('_token'));
            
            $item->slug = str_slug($item->name, '-');

            $item->save();

            return redirect()
              ->route('plugin.adaptbb.admin.forums.index')
              ->with('success', 'Forum has been saved');
        }

        $categories = $this->categories;

        return view('adaptbb::ForumsAdmin/edit', compact('item', 'categories'));
    }

    public function delete(Request $request, $id)
    {
        $item = Forum::find($id);

        if (empty($item)) {
            abort(404);
        }

        $item->delete();

        return redirect()
          ->route('plugin.adaptbb.admin.forums.index')
          ->with('success', 'Forum has been deleted.');
    }

    public function order(Request $request)
    {
        $items = Forum::orderBy('ord', 'ASC')->get();

        if ($request->getMethod() == 'POST') {
            $items = json_decode($request->get('items'), true);

            foreach ($items as $index => $id) {
                $item = Forum::find($id);

                $item->ord = $index;

                $item->save();
            }

            return response()->json([
            'status' => true
          ]);
        }

        $categories = $this->categories;

        return view('adaptbb::ForumsAdmin/order', compact('items', 'categories'));
    }
}
