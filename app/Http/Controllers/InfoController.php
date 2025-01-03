<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Info;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InfoController extends Controller
{
    public function index()
    {
        $infos = Info::with('file', 'user', 'category', 'savedByUsers')->get();
        foreach ($infos as $key => $value) {
            if (isset($value->savedByUsers) && $value->savedByUsers->pluck('id')->contains(Auth::user()->id)) {
                $value['isSavedByUser'] = true;
            } else {
                $value['isSavedByUser'] = false;
            }
        }
        return response()->json($infos, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required',
            'keywords' => 'nullable|string',
            'file_name' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,xls',
        ]);


        $file_id = Info::uploadFile($request);

        // return Auth::id();
        $info = Info::create([
            'title' => $request->title,
            'description' => $request->description,
            'keywords' => $request->keywords,
            'category_id' => $request->category_id,
            'file_id' => $file_id,
            'user_id' =>  Auth::id(),
        ]);
        $info->category_id = $request->category_id;
        $info->save();
        return response()->json($info, 201);
    }
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);



        // return Auth::id();
        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json($category, 201);
    }

    public function filter(Request $request)
    {
        $query = Info::query();

        if ($request->has('file_name') && $request->file_name) {
            // return $request->file_name;
            $query->whereHas('file', function ($q) use ($request) {
                $q->where('file_name', 'like', '%' . $request->file_name . '%');
            });
        }

        if ($request->has('description') && $request->description) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }

        if ($request->has('title') && $request->title) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        if ($request->has('keywords') && $request->keywords) {
            $query->where('keywords', 'like', '%' . $request->keywords . '%');
        }
        // if ($request->has('categories') && $request->categories) {
        //     $query->where('categories', 'in', $request->categories);
        // }
        // return $request->input('categoriesId');
        if ($request->has('categoriesId')) {
            $categoryIds = $request->input('categoriesId'); // Automatically collects repeated params into an array
            $query->whereIn('category_id', $categoryIds); // Filter directly by foreign key
        }


        $infos = $query->with('file', 'user', 'category')->get();
        return response()->json($infos, 200);
    }

    public function show($id)
    {
        $info = Info::with('file', 'user', 'category')->find($id);

        if (!$info) {
            return response()->json(['message' => 'Info non trouvée'], 404);
        }
        if (isset($info->savedByUsers) && $info->savedByUsers->pluck('id')->contains(Auth::user()->id)) {
            $info['isSavedByUser'] = true;
        } else {
            $info['isSavedByUser'] = false;
        }
        return response()->json($info, 200);
    }
    public function getCategories()
    {
        $category = Category::all();

        if (!$category) {
            return response()->json(['message' => 'category non trouvée'], 404);
        }

        return response()->json($category, 200);
    }

    public function update(Request $request, $id)
    {
        $info = Info::find($id);

        if (!$info) {
            return response()->json(['message' => 'Info non trouvée'], 404);
        }

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx',
        ]);


        $info->update($request->only(['title', 'description', 'keywords']));


        if ($request->hasFile('file')) {
            $file_id = Info::uploadFile($request);
            $info->file_id = $file_id;
            $info->save();
        }

        return response()->json($info, 200);
    }
    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category non trouvée'], 404);
        }

        $request->validate([
            'name' => 'nullable|string|max:255'
        ]);


        $category->update($request->only(['name']));


        return response()->json($category, 200);
    }
    public function deleteCategory($id)
    {
        $Category = Category::find($id);

        if (!$Category) {
            return response()->json(['message' => 'Category non trouvée'], 404);
        }
        $Category->delete();

        return response()->json(['message' => 'Category supprimée avec succès'], 200);
    }
    public function destroy($id)
    {
        $info = Info::find($id);

        if (!$info) {
            return response()->json(['message' => 'Info non trouvée'], 404);
        }


        if ($info->file) {
            Storage::delete($info->file->file_path);
            $info->file->delete();
        }


        $info->delete();

        return response()->json(['message' => 'Info supprimée avec succès'], 200);
    }


    public function saveDocumentForUser(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'info_id' => 'required|exists:infos,id',
        ]);


        $user = User::find($validated['user_id']);

        if (DB::table('saved_info_user')->where(['user_id' => $validated['user_id'], 'info_id' => $validated['info_id']])->exists()) {
            $user->savedInfos()->detach($validated['info_id']);
            return response()->json(['message' => 'Info unsaved  successfully.'], 200);
        } else {
            $user->savedInfos()->syncWithoutDetaching([$validated['info_id']]);
            return response()->json(['message' => 'Info saved  successfully.'], 200);
        }
    }
}
