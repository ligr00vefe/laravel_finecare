<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminVideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input("filter");
        $keyword = $request->input("keyword");

        $filterArr = [ "subject" ];

        $query = DB::table("board_video")
            ->when($filter, function ($query, $filter) use ($keyword, $filterArr) {
                if (!in_array($filter, $filterArr)) {
                    return back()->with("error", "잘못된 접근입니다");
                }

                return $query->where($filter, "like", "%{$keyword}%");
            })
            ->orderByDesc("id");

        $paging = $query->count();
        $lists = $query->paginate(12);

        return view('admin.video.index', [ "paging" => $paging, "lists" => $lists ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.video.create');
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

        $transaction = DB::transaction(function () use ($user_id, $request) {

            $insert = DB::table("board_video")
                ->insertGetId([
                    "user_id" => $user_id,
                    "category" => 1,
                    "subject" => $request->input("subject"),
                    "content" => $request->input("content"),
                ]);

            if (!$insert) return false;

            $upload = false;

            if ($request->file("file1"))
            {
                $upload = $request->file("file1")->store("upload/video");
            }

            $details = DB::table("video_details")
                ->insert([
                    "video_id" => $insert,
                    "link" => $request->input("link"),
                    "thumbnail" => $request->input("thumbnail") ?? "",
                    "thumbnail_path" => $upload
                ]);

            if (!$details) return false;

            return $insert;

        });



        if ($transaction)
        {
            return redirect()->route("admin.board.video.show", [ "id" => $transaction ])->with("msg", "글을 작성했습니다");
        }
        else
        {
            return back()->with("error", "긇 작성에 실패했습니다. 다시 시도해 주세요");
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Video $id)
    {
        $detail = DB::table("video_details")->where("video_id", "=", $id->id)->first();
        return view('admin.video.show', [ "post" => $id, "detail" => $detail ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $id)
    {
        $detail = DB::table("video_details")->where("video_id", "=", $id->id)->first();
        return view('admin.video.create', [ "post" => $id, "detail" => $detail, "edit" => 1 ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $id = $request->input("id");

        $transaction = DB::transaction(function () use ($request, $id) {

            $video = Video::find($id);
            $video->subject = $request->input("subject");

            if (!$video->save()) return false;

            $details = [ "link" => $request->input("link") ];

            if ($request->file("file1"))
            {
                $upload = $request->file("file1")->store("upload/video");
                $details["thumbnail"] = $request->input("thumbnail");
                $details["thumbnail_path"] = $upload;
            }

            return DB::table("video_details")
                ->updateOrInsert(
                    [ "video_id" => $id ],
                    $details
                );

        });

        if ($transaction)
        {
            return redirect()->route("admin.board.video.show", [ "id" => $transaction ])->with("msg", "글을 수정했습니다");
        }
        else
        {
            return back()->with("error", "글 수정에 실패했습니다. 다시 시도해 주세요. 바뀐 내용이 없을수도 있습니다");
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
        $transaction = DB::transaction(function () use ($id) {
            if (!DB::table("video_details")->where("video_id","=",$id)->delete()) return false;
            if (!DB::table("board_video")->where("id", "=", $id)->delete()) return false;
            return true;
        });

        if ($transaction)
        {
            return redirect()->route("admin.board.video")->with("msg", "글을 삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제에 실패했습니다. 다시 시도해 주세요");
        }
    }

    public function deleteAll(Request $request)
    {


        $transaction = DB::transaction(function () use ($request) {

            $ids = explode(",", $request->input("id"));

            foreach ($ids as $id)
            {
                if (!DB::table("video_details")->where("video_id","=",$id)->delete()) return false;
                if (!DB::table("board_video")->where("id", "=", $id)->delete()) return false;
            }

            return true;
        });

        if ($transaction)
        {
            return redirect()->route("admin.board.video")->with("msg", "글을 삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제에 실패했습니다. 다시 시도해 주세요");
        }

    }

}
