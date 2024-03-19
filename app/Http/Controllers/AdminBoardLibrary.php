<?php

namespace App\Http\Controllers;

use App\Models\Library;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBoardLibrary extends Controller
{

    public function index(Request $request)
    {

        $filter = $request->input("filter");
        $keyword = $request->input("keyword");

        $filterArr = [ "subject" ];

        $query = DB::table("board_library")
            ->when($filter, function ($query, $filter) use ($keyword, $filterArr) {
                if (!in_array($filter, $filterArr)) {
                    return back()->with("error", "잘못된 접근입니다");
                }

                return $query->whereLi($filter, "like", "%{$keyword}%");
            })
            ->orderByDesc("id");

        $paging = $query->count();
        $lists = $query->paginate(15);


        return view('admin.boardArchives', [ "paging" => $paging, "lists" => $lists ]);
    }

    public function create()
    {
        return view('admin.boardArchivesWrite');
    }

    public function show(Library $id)
    {
        return view('admin.boardArchivesView', [ "post" => $id ]);
    }

    public function store(Request $request)
    {
        $user_id = User::get_user_id();
        $library = new Library();
        $library->user_id = $user_id;
        $library->subject = $request->input("subject");
        $library->content = $request->input("content");
        $library->category = 1;

        if ($request->file("file1")) {
            $upload1 = $request->file("file1")->store("upload/library");
            $library->file1name = $request->file("file1")->getClientOriginalName();
            $library->file1path = $upload1;
        }

        if ($request->file("file2")) {
            $upload2 = $request->file("file2")->store("upload/library");
            $library->file2name = $request->file("file2")->getClientOriginalName();
            $library->file2path = $upload2;
        }

        if ($library->save())
        {
            return redirect()->route("admin.board.archives.view", [ "id" => $library->id ])->with("msg", "글을 작성했습니다");
        }
        else
        {
            return back()->with("error", "글 작성에 실패했습니다. 다시 시도해 주세요");
        }

    }

    public function edit(Library $id)
    {
        return view('admin.boardArchivesWrite', [ "post" => $id, "edit" => 1 ]);
    }

    public function update(Request $request)
    {

        $update = Library::find($request->input("id"));

        $update->subject = $request->input("subject");
        $update->content = $request->input("content");

        if ($request->file("file1")) {
            $upload1 = $request->file("file1")->store("upload/library");
            $update->file1name = $request->file("file1")->getClientOriginalName();
            $update->file1path = $upload1;
        }

        if ($request->file("file2")) {
            $upload2 = $request->file("file2")->store("upload/library");
            $update->file2name = $request->file("file2")->getClientOriginalName();
            $update->file2path = $upload2;
        }


        if ($update->save())
        {
            return redirect()->route("admin.board.archives.view", [ "id" => $update->id ])->with("msg", "글을 수정했습니다");
        }
        else
        {
            return back()->with("error", "글 작성에 실패했습니다. 다시 시도해 주세요");
        }

    }


    public function destroy(Request $request)
    {
        $delete = Library::find($request->input("id"))->delete();
        if ($delete)
        {
            return redirect()->route("admin.board.archives")->with("msg", "글을 삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제에 실패했습니다. 다시 시도해 주세요");
        }
    }


    public function destroyAll(Request $request)
    {
        $ids = explode(",", $request->input("id"));

        $transaction = DB::transaction(function () use ($request, $ids) {

            $return = false;

            foreach ($ids as $id)
            {
                $return = Library::find($id)->delete();
                if (!$return) {
                    break;
                }
            }

            return $return;

        });

        if($transaction)
        {
            return redirect()->route("admin.board.archives")->with("msg", "삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제하는데 문제가 발생했습니다. 다시 시도해 주세요");
        }


    }


}
