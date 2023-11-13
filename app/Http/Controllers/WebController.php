<?php

namespace App\Http\Controllers;

use App\Models\Editorcoice;
use App\Models\Headlinerubrik;
use App\Models\Headlinewp;
use App\Models\Posts;
use App\Models\Rubrik;
use App\Models\Tags;
use App\Models\Topic;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Sarfraznawaz2005\VisitLog\Facades\VisitLog;
use Sarfraznawaz2005\VisitLog\Models\VisitLog as VisitLogModel;

class WebController extends Controller
{
    public function subscribe()
    {
    }
    public function index(): View
    {
        VisitLog::save(request()->all());

        $data['editorCohice'] = Editorcoice::get();
        $data['headlineWp'] = Headlinewp::get();
        $data['topikKhusus'] = Topic::get();

        // posts 1-30
        $data['paginatedPost'] = Posts::orderBy('created_at', 'DESC')
            ->where('status', 'published')
            ->paginate(30);
        $data['beritaTerkini'] = $data['paginatedPost']->split(2);

        // dd($data['beritaTerkini']);
        return view('frontend.web', $data);
    }


    public function indeks(Request $request)
    {
        VisitLog::save($request->all());

        // Cek apakah ada rentang tanggal yang dipilih
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date') . ' 00:00:00';
            $endDate = $request->input('end_date') . ' 23:59:59';

            $data['paginatedPost'] = Posts::where('status', 'published')
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } else {
            // Jika tidak ada tanggal yang dipilih, tampilkan semua berita
            $data['paginatedPost'] = Posts::where('status', 'published')
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        }

        $data['beritaTerkini'] = $data['paginatedPost']->split(2);

        return view('frontend.indeks', $data);
    }


    public function showCategory(): View
    {
        VisitLog::save(request()->all());

        $data['editorCohice'] = Editorcoice::get();
        return view('frontend.web', $data);
    }

    public function singlePost(Request $request, $rubrik_name, $post_id, $slug): View
    {

        // visitor counter
        // jika ip sudah mengunjungi do nothing
        if (VisitLog::save(request()->all())['type'] == 'create') {
            $post = Posts::find($post_id);
            $post->visit += 1;
            $post->save();
        }
        // if(VisitLogModel::where('ip', $request->ip())->get()->count() < 0) {
        // }

        $data['post'] = Posts::find($post_id);
        $data['paginatedPost'] = Posts::orderBy('created_at', 'DESC')
            ->where('status', 'published')
            ->limit(10)->get();
        $data['beritaTerkini'] = $data['paginatedPost'];
        return view('frontend.singlepost', $data);
    }

    public function category($rubrik_name): View
    {
        $rubrik = Rubrik::where('rubrik_name', $rubrik_name)->get()[0];
        $data['rubrik_name'] = $rubrik_name;
        $data['headlineRubrik'] = Headlinerubrik::where('rubrik_id', $rubrik->rubrik_id)->get()[0];
        $data['topikKhusus'] = Topic::get();

        // posts 1-20
        $data['paginatedPost'] = Posts::orderBy('created_at', 'DESC')
            ->where(['status' => 'published', 'category' => $rubrik->rubrik_id])
            ->paginate(20);
        $data['beritaTerkini'] = $data['paginatedPost']->split(2);
        return view('frontend.category', $data);
    }

    public function tags($tag_name): View
    {
        $tag_id = Tags::where('tag_name', $tag_name)->get()[0]->tag_id;

        $data['tag_name'] = $tag_name;
        $data['topikKhusus'] = Topic::get();

        // posts 1-20
        $data['paginatedPost'] = Posts::orderBy('created_at', 'DESC')
            ->where(
                [
                    ['status', '=', 'published'],
                    ['tags', 'like', '%"' . $tag_id . '"%']
                ]
            )
            ->paginate(20);
        $data['beritaTerkini'] = $data['paginatedPost']->split(2);
        return view('frontend.tags', $data);
    }

    public function search(Request $request): View
    {
        $keyword = $request->input('q');

        $posts = Posts::where('title', 'like', "%$keyword%")
            ->orWhere('article', 'like', "%$keyword%")
            ->get();

        return view('frontend.search', compact('posts', 'keyword'));
    }
}
