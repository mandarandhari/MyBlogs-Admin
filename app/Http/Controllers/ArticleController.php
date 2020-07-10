<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;
use Auth;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('articles.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('articles.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['max:1000'],
            'isPremium' => ['required', 'in:yes,no'],
            'content' => ['required', 'max:10000'],
            'metaTitle' => ['required', 'string', 'max:255'],
            'metaDescription' => ['max:1000'],
            'banner' => ['required', 'image', 'max:4096'],
            'thumb' => ['required', 'image', 'max:2048']
        ]);

        $article = new Article;

        $article->user_id = Auth::user()->id;
        $article->title = $request->title;
        $article->url = getUrlString($request->title);
        $article->description = $request->description;
        $article->is_premium = $request->isPremium;
        $article->content = $request->content;
        $article->meta_title = $request->metaTitle;
        $article->meta_description = $request->metaDescription;

        if ($article->save()) {
            $bannerName = md5( $request->file('banner')->getClientOriginalName() . time() ) . "." . $request->file('banner')->extension();
            $request->file('banner')->storeAs( 'public/articleBanners/' . $article->id, $bannerName);

            $thumbName = md5( $request->file('thumb')->getClientOriginalName() . time() ) . "." . $request->file('thumb')->extension();
            $request->file('thumb')->storeAs( 'public/articleThumbs/'. $article->id, $thumbName);

            $article_update = Article::find($article->id);

            $article_update->banner = $bannerName;
            $article_update->thumb = $thumbName;

            $article_update->update();

            $notification = [
                'message' => 'Article added successfully',
                'alert-type' => 'success'
            ];

            return redirect('/articles')->with($notification);
        } else {
            $notification = [
                'message' => 'An unexpected error occured',
                'alert-type' => 'error'
            ];

            return redirect()->back()->with($notification);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}