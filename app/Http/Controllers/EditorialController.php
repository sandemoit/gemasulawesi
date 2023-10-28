<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use App\Models\Rubrik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class EditorialController extends Controller
{
    public function create()
    {
        $data = [
            'rubriks' => Rubrik::get(),
        ];
        return view('editorial.create', $data);
    }

    public function insert(Request $request)
    {
        $postData = [
            'title' => $request->title,
            'slug' => str_replace(' ', '-', $request->title),
            'category' => $request->rubrik,
            'description' => $request->description,
            'article' => $request->content,
            'allow_comment' => $request->allow_comment,
            'view_in_welcome_page' => $request->view_in_welcome_page,
            'author_id' => Auth::user()->id,
            'editor_id' => Auth::user()->id,
            'status' => 'published',
            'related_articles' => json_encode($request->related),
            'tags' => json_encode($request->tags),
            'topics' => json_encode($request->topics),
            'schedule_time' => $request->schedule_time,
            'published_at' => $request->published_at,
            'is_deleted' => $request->is_deleted,
        ];

        // dd($postData);
        if (Posts::create($postData)) {
            return redirect()->route('editorial.published');
        }
        // dd($request->all());
    }

    public function modal_related()
    {
        $data['posts'] = Posts::where('status', 'published')->orderBy('created_at', 'DESC')->get();
        return view('editorial.components.modal_related', $data);
    }

    public function draft()
    {
        $data['posts'] = Posts::where('status', 'draft')->orderBy('created_at', 'DESC')->get();
        return view('editorial.draft', $data);
    }

    public function published()
    {
        $data['posts'] = Posts::where('status', 'published')->orderBy('created_at', 'DESC')->get();
        return view('editorial.published', $data);
    }


    public function api_create(Request $request)
    {
        $postData = [
            'title' => $request->title,
            'slug' => str_replace(' ', '-', $request->title),
            'category' => $request->category,
            'description' => $request->description,
            'article' => $request->article,
            'allow_comment' => false,
            'view_in_welcome_page' => false,
            'author_id' => $request->author_id,
            'editor_id' => $request->editor_id,
            'status' => 'published',
            'related_articles' => null,
            'tags' => $request->tags,
            'topics' => null,
            'schedule_time' => $request->schedule_time,
            'published_at' => $request->published_at,
            'is_deleted' => $request->is_deleted,
        ];

        // dd($postData);

        $res = Posts::create($postData);

        // die;
        if ($res) {
            return response()->json([
                'status' => True,
                'data' => $res
            ]);
        } else {
            return response()->json([
                'status' => False
            ]);
        }
    }
}
