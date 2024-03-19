<?php

namespace App\Http\Controllers;

use App\Models\QNA;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QNABoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = User::get_user_id();
        $category = $request->input("category");
        $filter = $request->input("search-filter");
        $term = $request->input("term");

        $_query = DB::table("board_qna")
            ->where("user_id", "=", $user_id)
            ->where("deleteCheck", "!=", 1)
            ->when($category, function ($query, $category) {
                return $query->where("category", "=", $category);
            })
            ->when($filter, function ($query, $filter) use ($term) {
                $raw = "";
                $condition = [];
                switch ($filter)
                {
                    case "subject_content":
                        $raw = "(subject like ? OR content like ?)";
                        $condition = [ "%{$term}%", "%{$term}%" ];
                        break;
                    default:
                        break;
                }
                return $query->whereRaw($raw, $condition);
            });

        $paging = $_query->count();
        $lists = $_query->paginate(15);

        return view("support.qna", [
            "paging" => $paging,
            "lists" => $lists,
            "page" => $request->input("page") ?? 1,
            "category" => $request->input("category")
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("support.qnaCreate");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = User::get_user_id();

        $upload1 = "";
        $file1Name = "";
        $upload2 = "";
        $file2Name = "";

        if ($request->file("file1")) {
            $upload1 = $request->file("file1")->store("upload/qna");
            $file1Name = $request->file("file1")->getClientOriginalName();
        }

        if ($request->file("file2")) {
            $upload2 = $request->file("file2")->store("upload/qna");
            $file2Name = $request->file("file2")->getClientOriginalName();
        }

        $store = DB::table("board_qna")
            ->insertGetId([
                "user_id" => $user_id,
                "category" => $request->input("category"),
                "subject" => $request->input("subject"),
                "content" => $request->input("content"),
                "file1name" => $file1Name,
                "file1path" => $upload1,
                "file2name" => $file2Name,
                "file2path" => $upload2,
            ]);

        if ($store)
        {
            return redirect()->route("support.qna.show", ["id" => $store ])->with("msg", "글을 작성했습니다.");
        }
        else
        {
            return back()->with("error", "글 작성에 실패했습니다. 다시 시도해 주세요");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(QNA $id)
    {
        return view("support.qnaView", [ "id" => $id ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(QNA $id)
    {
        return view("support.qnaCreate", [ "id" => $id, "edit" => 1 ]);
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
        $update = QNA::find($id);

        $update->category = $request->input("category");
        $update->subject = $request->input("subject");
        $update->content = $request->input("content");

        if ($request->file("file1")) {
            $upload1 = $request->file("file1")->store("upload/qna");
            $update->file1name = $request->file("file1")->getClientOriginalName();
            $update->file1path = $upload1;
        }

        if ($request->file("file2")) {
            $upload2 = $request->file("file2")->store("upload/qna");
            $update->file2name = $request->file("file2")->getClientOriginalName();
            $update->file2path = $upload2;
        }

        if ($update->save())
        {
            return redirect()->route("support.qna.show", [ "id" => $id] )->with("msg", "글을 수정했습니다");
        }
        else
        {
            return back()->with("error", "글 수정하는데 실패했습니다. 다시 시도해 주세요");
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
        $delete = QNA::find($id);
        if ($delete->delete())
        {
            return redirect()->route("support.qna", [ "page" => 1 ])->with("msg", "삭제했습니다");
        }
        else
        {
            return back()->with("msg", "삭제했습니다");
        }
    }
}
