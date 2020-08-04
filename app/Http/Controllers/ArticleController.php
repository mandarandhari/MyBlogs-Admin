<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Dropbox\Client;
use Storage;
use App\Article;
use App\Comment;
use Auth;
use DB;

class ArticleController extends Controller
{
    public $client;

    public function __construct()
    {
        $this->client = new Client(env('DROPBOX_ACCESS_TOKEN'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $articles = Article::leftJoin('articles_has_comments', 'articles.id', '=', 'articles_has_comments.article_id');

        if ($request->query('searchterm') != "") {
            $articles = $articles->where('title', 'like', '%' . $request->query('searchterm') . '%');
        }

        $articles = $articles->orderBy('articles.id', 'desc')
                            ->select([
                                'articles.id',
                                'articles.title',
                                'articles.url',
                                'articles.thumb_url',
                                'articles.created_at',
                                DB::raw('COUNT(articles_has_comments.id) AS comments_count')
                            ])
                            ->groupBy('articles.id')
                            ->paginate(10);

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
            // $bannerName = md5( $request->file('banner')->getClientOriginalName() . time() ) . "." . $request->file('banner')->extension();
            // $request->file('banner')->storeAs( 'public/articleBanners/' . $article->id, $bannerName);
            $bannerName = Storage::disk('dropbox')->put( 'articleBanners/' . $article->id, $request->file('banner') );
            $bannerUrlArr = $this->client->createSharedLinkWithSettings($bannerName, [
                "requested_visibility" => "public",
                "audience" => "public",
            ]);

            // $thumbName = md5( $request->file('thumb')->getClientOriginalName() . time() ) . "." . $request->file('thumb')->extension();
            // $request->file('thumb')->storeAs( 'public/articleThumbs/'. $article->id, $thumbName);
            $thumbName = Storage::disk('dropbox')->put( 'articleThumbs/' . $article->id, $request->file('thumb') );
            $thumbUrlArr = $this->client->createSharedLinkWithSettings($thumbName, [
                "requested_visibility" => "public",
                "audience" => "public",
            ]);

            $article_update = Article::find($article->id);

            $article_update->banner = $bannerName;
            $article_update->banner_url = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $bannerUrlArr['url']);
            $article_update->thumb = $thumbName;
            $article_update->thumb_url = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $thumbUrlArr['url']);

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
                // if ( file_exists( public_path( 'storage/articleBanners/' . $article->id . '/' . $article->banner ) ) ) {
                //     unlink( public_path( 'storage/articleBanners/' . $article->id . '/'. $article->banner ) );
                // }

                if ($article->banner != NULL) {
                    $this->client->delete($article->banner);
                }
                
                $bannerName = Storage::disk('dropbox')->put( 'articleBanners/' . $article->id, $request->file('banner') );
                $bannerUrlArr = $this->client->createSharedLinkWithSettings($bannerName, [
                    "requested_visibility" => "public",
                    "audience" => "public",
                ]);

                // $bannerName = md5( $request->file('banner')->getClientOriginalName() . time() ) . '.' . $request->file('banner')->extension();

                // if ( $request->file('banner')->storeAs( 'public/articleBanners/' . $article->id, $bannerName ) ) {
                if ( isset($bannerUrlArr['url']) ) {
                    $article->banner = $bannerName;
                    $article->banner_url = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $bannerUrlArr['url']);
                }
            }

            if ($request->hasFile('thumb')) {
                // if (file_exists( public_path( 'storage/articleThumbs/' . $article->id .'/' . $article->thumb ) )) {
                //     unlink( public_path( 'storage/articleThumbs/' . $article->id . '/' . $article->thumb ) );
                // }

                if ($article->thumb != NULL) {
                    $this->client->delete($article->thumb);
                }

                // $thumbName = md5( $request->file('thumb')->getClientOriginalName() . time() ) . '.' . $request->file('thumb')->extension();
                $thumbName = Storage::disk('dropbox')->put( 'articleThumbs/' . $article->id, $request->file('thumb') );
                $thumbUrlArr = $this->client->createSharedLinkWithSettings($thumbName, [
                    "requested_visibility" => "public",
                    "audience" => "public",
                ]);

                // if ( $request->file('thumb')->storeAs( 'public/articleThumbs/' . $article->id, $thumbName ) ) {
                if ( isset($thumbUrlArr['url']) ) {
                    $article->thumb = $thumbName;
                    $article->thumb_url = str_replace('www.dropbox.com', 'dl.dropboxusercontent.com', $thumbUrlArr['url']);
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
            $article_banner = $article->banner;
            $article_thumb = $article->thumb;

            if ($article->delete()) {
                if ($article_banner != NULL) {
                    $this->client->delete($article_banner);
                }

                if ($article_thumb != NULL) {
                    $this->client->delete($article_thumb);
                }

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

    public function get_all_comments(Request $request, $article_id)
    {
        $searchterm = $request->query('searchterm');

        $comments = Comment::leftJoin('customers', 'articles_has_comments.customer_id', '=', 'customers.id');

        if ($searchterm != "") {
            $comments = $comments->where(function($query) use ($searchterm, $article_id) {
                                $query->where('articles_has_comments.comment', 'like', '%'. $searchterm . '%');
                            })
                            ->orWhere(function($query) use ($searchterm, $article_id) {
                                $query->where('customers.name', 'like', '%' . $searchterm . '%');
                            });
        }

        $comments = $comments->where('articles_has_comments.article_id', $article_id)
                            ->select([
                                'articles_has_comments.id',
                                'articles_has_comments.article_id',
                                'articles_has_comments.customer_id',
                                'articles_has_comments.comment',
                                'articles_has_comments.created_at',
                                'customers.name'
                            ])
                            ->paginate(10);

        $article = Article::find($article_id);

        return view('articles.comments')->with(['comments' => $comments->appends($request->except('page')), 'article' => $article]);
    }

    public function delete_comment(Request $request, $id)
    {
        $comment = Comment::find($id);

        if (isset($comment->id)) {
            if ($comment->delete()) {
                $notification = [
                    'message' => 'Comment deleted',
                    'alert-type' => 'success'
                ];
            } else {
                $notification = [
                    'message' => 'An unexpected error occured',
                    'alert-type' => 'error'
                ];
            }
        } else {
            $notification = [
                'message' => 'Comment does not exists',
                'alert-type' => 'error'
            ];
        }
        
        return redirect('/comments/' . $request->article_id)->with($notification);
    }
}
