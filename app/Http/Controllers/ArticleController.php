<?php

namespace App\Http\Controllers;

use App\Lib\MyHelper;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private string $path = 'be/article/';
    public function index()
    {
        $data = [
            'title'             => 'Manage Article',
            'sub_title'         => 'List',
            'menu_active'       => 'article',
        ];
        $article = MyHelper::get($this->path);

        if (isset($article['status']) && $article['status'] == "success") {
            $data['articles'] = $article['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
        return view('pages.article.index', $data);
    }

    public function create()
    {
        return view('pages.article.create', [
            'title'             => 'Create Article',
            'sub_title'         => 'List',
            'menu_active'       => 'article',
        ]);
    }

    public function store(Request $request)
    {
        $payload = [
            "title" => $request->title,
            "writer" => $request->writer,
            "release_date" => $request->release_date,
            "description" => $request->description,
        ];
        if ($request->hasFile('image')) {
            $name_file = $request->file('image')->getClientOriginalName();
            $path = public_path('\images');
            $request->file('image')->move($path, $name_file);
            $payload['image'] = $name_file;
        }

        $save = MyHelper::post($this->path, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('article')->withSuccess(['New Article successfully added.']);
        } else {
            return back()->withErrors($save['error'])->withInput();
        }
    }

    public function show($id)
    {
        $data = [
            'title'             => 'CMS Detail Article',
            'sub_title'         => 'Detail',
        ];
        $article = MyHelper::get($this->path . $id);

        if (isset($article['status']) && $article['status'] == "success") {
            $data['detail'] = $article['result'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('pages.article.detail', $data);
    }

    public function update(Request $request, $id)
    {
        $payload = [
            "title" => $request->title,
            "writer" => $request->writer,
            "release_date" => $request->release_date,
            "description" => $request->description,
        ];
        if ($request->hasFile('image')) {
            $name_file = $request->file('image')->getClientOriginalName();
            $path = public_path('\images');
            $request->file('image')->move($path, $name_file);
            $payload['image'] = $name_file;
        }
        $save = MyHelper::patch($this->path . $id, $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('article')->withSuccess(['CMS Article detail has been updated.']);
        } else {
            if (isset($save['status']) && $save['status'] == "error") {
                return back()->withErrors($save['message'])->withInput();
            }
            return back()->withErrors($save['error'])->withInput();
        }
    }

    public function deleteArticle($id)
    {
        $delete = MyHelper::deleteApi($this->path . $id);
        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Product deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
