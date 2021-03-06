<?php

namespace App\Http\Controllers\admin;

use App\CategoryPortfolio;

use App\Seo;
use App\Vw_CategoryPortfolio;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class CategoryPortfolioController extends MainController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $items = Vw_CategoryPortfolio::all();
        return view('admin.portfolio.category.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $category_portfolio = Vw_CategoryPortfolio::all();
//        dd($category_services);
//        $parents = Vw_CategoryServices::where('status',1)->select(['title','id'])->where('parent_id',0)->orderbyDesc('parent_id')->get();
        /*if (count($parents)){
            foreach ($parents as $parent){
                $subparents = Vw_CategoryServices::where('status',1)->select(['title','id'])->where('parent_id',$parent->id)->orderbyDesc('parent_id')->get();
                if (count($subparents)){

                }
            }
        }*/

        return view('admin.portfolio.category.create', compact('category_portfolio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);
        $status = 0;
        if (isset($request['status'])) {
            $status = 1;
        }
        CategoryPortfolio::create([
            'title' => $request['title'],
            'tags' => $request['tags'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent_id'],
            'lang' => $request['lang'],
            'status' => $status,
            'usersID_FK' => auth()->user()->id
        ]);
        return redirect(route('category_portfolio.index'))->with('message', 'عملیات موفقیت آمیز بود');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\CategoryPortfolio $categoryPortfolio
     * @return \Illuminate\Http\Response
     */
    public function show(CategoryPortfolio $categoryPortfolio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\CategoryPortfolio $categoryPortfolio
     * @return \Illuminate\Http\Response
     */
    public function edit(CategoryPortfolio $categoryPortfolio)
    {
        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $category_portfolios = Vw_CategoryPortfolio::where(['status' => 1])->where('parent_id', '!=', $categoryPortfolio->id)->select(['title', 'id'])->orderbyDesc('parent_id')->get();
        $seo = $categoryPortfolio->seo;
        return view('admin.portfolio.category.update', compact('category_portfolios', 'categoryPortfolio','seo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\CategoryPortfolio $categoryPortfolio
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CategoryPortfolio $categoryPortfolio)
    {
        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $request->validate([
            'title' => 'required',
            'slug' => 'required',
        ]);
        $status = 0;
        if (isset($request['status'])) {
            $status = 1;
        }
        $categoryPortfolio->update([
            'title' => $request['title'],
            'tags' => $request['tags'],
            'parent_id' => $request['parent_id'],
            'lang' => $request['lang'],
            'slug' => $request['slug'],
            'status' => $status,
        ]);
        return redirect(route('category_portfolio.index'))->with('message', 'عملیات موفقیت آمیز بود');;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\CategoryPortfolio $categoryPortfolio
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryPortfolio $categoryPortfolio)
    {
        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }

        $subcategories = CategoryPortfolio::where('parent_id', $categoryPortfolio->id)->get();
        if (count($subcategories)) {
            foreach ($subcategories as $category) {
                $this->updatePortfolioToNonCategory($category);
                $category->delete();
            }
        }
        $this->updatePortfolioToNonCategory($categoryPortfolio);
        $categoryPortfolio->delete();
        return back()->with('message', 'عملیات موفقیت آمیز بود');
    }


    public function remove_all(Request $request)
    {
        try {
            if (!$this->authorize('portfolio')) {
                abort(403);
            }
        } catch (AuthorizationException $e) {
            abort(403);
        }
        $ids = $request['ids'];
        $ids = explode(',', $ids);
        foreach ($ids as $id) {
            $categoryArticle = CategoryPortfolio::find($id);
            $subcategories = CategoryPortfolio::where('parent_id', $categoryArticle->id)->get();
            if (count($subcategories)) {
                foreach ($subcategories as $category) {
                    $this->updatePortfolioToNonCategory($category);
                    $category->delete();
                }
            }
            $this->updatePortfolioToNonCategory($categoryArticle);
            $categoryArticle->delete();
        }
        return redirect(route('category_portfolio.index'))->with('message', 'عملیات موفقیت آمیز بود');
    }

    public function save_seo_portfolio_cat(Request $request)
    {
        $id = $request['object'];
        $item = CategoryPortfolio::find($id);
        $seo = Seo::find($item->seo_id);
        if (isset($object)) {
            $index = 0;
            $follow = 0;
            if (isset($request['index'])) {$index = 1;}
            if (isset($request['follow'])) {$follow = 1;}
            $seo->update([
                'index'=>$index,
                'follow'=>$follow,
                'title'=>$request['title'],
                'description'=>$request['description'],
                'keywords'=>$request['keywords'],
                'seo_url'=>$request['seo_url'],
                'canonical'=>$request['canonical'],
            ]);
        } else {
            $index = 0;
            $follow = 0;
            if (isset($request['index'])) {$index = 1;}
            if (isset($request['follow'])) {$follow = 1;}
            $seo = Seo::create([
                'index'=>$index,
                'follow'=>$follow,
                'title'=>$request['title'],
                'description'=>$request['description'],
                'keywords'=>$request['keywords'],
                'seo_url'=>$request['seo_url'],
                'canonical'=>$request['canonical'],
            ]);
        }
        $item->update([
            'seo_id'=> $seo->id
        ]);
        return back()->with('message', 'تغییرات صورت گرفت');
    }
}
