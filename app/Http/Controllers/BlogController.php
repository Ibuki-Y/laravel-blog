<?php

namespace App\Http\Controllers;

use App\Models\Blog;
// use Illuminate\Http\Request;
use App\Http\Requests\BlogRequest;

class BlogController extends Controller {
    /**
     * View Blog List.
     *
     * @return view
     */
    public function showList() {
        $blogs = Blog::all();
        // dd($blogs);

        return view('blog.list', ['blogs' => $blogs]);
    }

    /**
     * View Blog Detail.
     *
     * @param int $id
     * @return view
     */
    public function showDetail($id) {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            \Session::flash('err_msg', 'No data available.');
            // nullのときblogs('/')に遷移
            return redirect(route('blogs'));
        }

        return view('blog.detail', ['blog' => $blog]);
    }

    /**
     * Blog Create.
     *
     * @return view
     */
    public function showCreate() {
        return view('blog.form');
    }

    /**
     * Blog Store.
     *
     * @return view
     */
    public function exeStore(BlogRequest $request) {
        $inputs = $request->all(); // dd($inputs);
        \DB::beginTransaction();
        try {
            Blog::create($inputs);
            \DB::commit();
        } catch (\Throwable $e) {
            // server error
            abort(500);
            \DB::rollback();
        }

        \Session::flash('success_msg', 'Transmission completed.');

        return redirect(route('blogs'));
    }

    /**
     * View Blog Edit.
     *
     * @param int $id
     * @return view
     */
    public function showEdit($id) {
        $blog = Blog::find($id);

        if (is_null($blog)) {
            \Session::flash('err_msg', 'No data available.');
            // nullのときblogs('/')に遷移
            return redirect(route('blogs'));
        }

        return view('blog.edit', ['blog' => $blog]);
    }

    /**
     * Blog Edit.
     *
     * @return view
     */
    public function exeUpdate(BlogRequest $request) {
        $inputs = $request->all();
        \DB::beginTransaction();
        try {
            $blog = Blog::find($inputs['id']);
            $blog->fill([
                'title' => $inputs['title'],
                'content' => $inputs['content']
            ]);
            // save(): 差分があるときのみ更新
            $blog->save();
            \DB::commit();
        } catch (\Throwable $e) {
            // server error
            abort(500);
            \DB::rollback();
        }

        \Session::flash('success_msg', 'Updated.');

        return redirect(route('blogs'));
    }

    /**
     * Blog Delete.
     *
     * @param int $id
     * @return view
     */
    public function exeDelete($id) {
        if (empty($id)) {
            \Session::flash('err_msg', 'No data available.');
            return redirect(route('blogs'));
        }

        try {
            Blog::destroy($id);
        } catch (\Throwable $e) {
            abort(500);
        }

        \Session::flash('success_msg', 'Deleted.');

        return redirect(route('blogs'));
    }
}
