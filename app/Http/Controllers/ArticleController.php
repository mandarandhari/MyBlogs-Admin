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
    public function index(Request $request)
    {
        if ($request->query('searchterm') != "") {
            $articles = Article::where('title', 'like', '%' . $request->query('searchterm') . '%')->orderBy('id', 'desc')->paginate(10);
        } else {
            $articles = Article::latest()->orderBy('id', 'desc')->paginate(10);
        }        
        
        return view('articles.list')->with('articles', $articles->appends($request->except('page')));
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
                'message' => 'Article added',
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
        $article = Article::find($id);

        return view('articles.edit')->with('article', $article);
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
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['max:1000'],
            'isPremium' => ['required', 'in:yes,no'],
            'content' => ['required', 'max:10000'],
            'metaTitle' => ['required', 'string', 'max:255'],
            'metaDescription' => ['max:1000'],
            'banner' => ['sometimes', 'image', 'max:4096'],
            'thumb' => ['sometimes', 'image', 'max:2048']
        ]);

        $article = Article::find($id);

        if (isset($article->id)) {        
            $article_url = ($article->title != $request->title) ? getUrlString($request->title) : $article->url;

            $article->title = $request->title;
            $article->url = $article_url;
            $article->description = $request->description;
            $article->is_premium = $request->isPremium;
            $article->content = $request->content;
            $article->meta_title = $request->metaTitle;
            $article->meta_description = $request->metaDescription;

            if ($request->hasFile('banner')) {
                if ( file_exists( public_path( 'storage/articleBanners/' . $article->id . '/' . $article->banner ) ) ) {
                    unlink( public_path( 'storage/articleBanners/' . $article->id . '/'. $article->banner ) );
                }

                $bannerName = md5( $request->file('banner')->getClientOriginalName() . time() ) . '.' . $request->file('banner')->extension();

                if ( $request->file('banner')->storeAs( 'public/articleBanners/' . $article->id, $bannerName ) ) {
                    $article->banner = $bannerName;
                }
            }

            if ($request->hasFile('thumb')) {
                if (file_exists( public_path( 'storage/articleThumbs/' . $article->id .'/' . $article->thumb ) )) {
                    unlink( public_path( 'storage/articleThumbs/' . $article->id . '/' . $article->thumb ) );
                }

                $thumbName = md5( $request->file('thumb')->getClientOriginalName() . time() ) . '.' . $request->file('thumb')->extension();

                if ( $request->file('thumb')->storeAs( 'public/articleThumbs/' . $article->id, $thumbName ) ) {
                    $article->thumb = $thumbName;
                }
            }

            if ($article->update()) {
                $notification = [
                    'message' => "Article updated",
                    'alert-type' => 'success'
                ];

                return redirect('/articles')->with($notification);
            } else {
                $notification = [
                    'message' => "An unexpected error occured",
                    'alert-type' => 'error'
                ];

                return redirect()->back()->with($notification);
            }
        } else {
            $notification = [
                'message' => "Article does not exists",
                'alert-type' => 'error'
            ];

            return redirect('/articles')->with($notification);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);

        if (isset($article->id)) {
            if ($article->delete()) {
                $notification = [
                    'message' => "Article deleted",
                    'alert-type' => 'success'
                ];
            } else {
                $notification = [
                    'message' => "An unexpected error occured",
                    'alert-type' => 'error'
                ];
            }            
        } else {
            $notification = [
                'message' => "Article does not exists",
                'alert-type' => 'error'
            ];
        }
            
        return redirect('/articles')->with($notification);
    }
}
