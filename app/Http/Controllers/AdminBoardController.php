<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use App\Models\QNA;
use App\Models\QNAAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $lib = DB::table("board_library")->selectRaw("*, '자료실' as `table`, '/support/lib/view' as `url`  ");
        $video = DB::table("board_video")->selectRaw("*, '동영상' as `table`, '/support/video/view' as `url` ");
        $faq = DB::table("board_faq")->selectRaw("*, 'FAQ' as `table`, '/support/faq/list' as `url` ");

        $query = DB::table("board_qna")
            ->selectRaw("*, '온라인문의' as `table`, '/support/qna/view' as `url` ")
            ->unionAll($lib)
            ->unionAll($video)
            ->unionAll($faq)
            ->orderByDesc("created_at");

        $paging = $query->count();
        $lists = $query->paginate(15);

        return view('admin.board', [ "lists" => $lists, "paging" => $paging ]);
    }

    public function inquiry(Request $request)
    {
        $category = $request->input("category");
        $filter = $request->input("filter");
        $keyword = $request->input("keyword");

        $query = DB::table("board_qna")
            ->when($category, function ($query, $category) {
                return $query->where("category", "=", $category);
            })
            ->when($filter, function ($query, $filter) use ($keyword) {
                return $query->where($filter, "=", $keyword);
            })
            ->orderByDesc("id");

        $paging = $query->count();
        $lists = $query->paginate(15);

        return view('admin.boardOnlineInquiry', [
            "lists" => $lists,
            "paging" => $paging ,
            "category" => $request->input("category")
        ]);
    }

    public function boardArchives()
    {
        return view('admin.boardArchives');
    }
    public function boardVideoManagement()
    {
        return view('admin.boardVideoManagement');
    }
    public function boardPayment()
    {
        return view('admin.boardPayment');
    }
    public function boardPaymentProduct()
    {
        return view('admin.boardPaymentProduct');
    }
    public function boardManagementModify()
    {
        return view('admin.boardManagementModify');
    }

    public function boardOnlineInquiryStore($id, Request $request)
    {
        $user_id = User::get_user_id();

        $transaction = DB::transaction(function () use ($user_id, $request, $id) {

            DB::table("board_qna_answer")
                ->updateOrInsert(
                    [ "board_id" => $id ],
                    [ "user_id" => $user_id, "contents"=> $request->input("contents") ]
                );

            $qna = QNA::find($id);
            $qna->answerCheck = 1;

            return $qna->save();
        });


        if ($transaction)
        {
            return redirect()->route("admin.board.inquiry.view", [ "id" => $id ])->with("msg", "답변을 작성하였습니다");
        }
        else
        {
            return back()->with("error", "답변 작성에 실패했습니다. 다시 시도해 주세요.");
        }
    }

    public function boardOnlineInquiryModify(QNA $id)
    {
        $answer = QNAAnswer::where("board_id", "=", $id->id)->first();
        return view('admin.boardOnlineInquiryModify', [ "post" => $id, "answer" => $answer ]);
    }

    public function boardOnlineInquiryView(QNA $id)
    {
        $answer = QNAAnswer::where("board_id", "=", $id->id)->first();
        return view('admin.boardOnlineInquiryView', [ "post" => $id, "answer" => $answer ]);
    }
    
    public function boardOnlineInquiryDestroy(QNA $id)
    {
        $transaction = DB::transaction(function () use ($id) {
            QNAAnswer::where("board_id","=",$id->id)->delete();
            return $id->delete();
        });

        if($transaction)
        {
            return redirect()->route("admin.board.inquiry")->with("msg", "삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제하는데 문제가 발생했습니다. 다시 시도해 주세요");
        }
    }



    public function boardFAQManagement(Request $request)
    {
        $filter = $request->input("filter");
        $keyword = $request->input("keyword");

        $filter_arr = [ "subject" ];

        $query = DB::table("board_faq")
            ->when($filter, function ($query, $filter) use ($keyword, $filter_arr) {

                if (!in_array($filter, $filter_arr)) {
                    return back()->with("잘못된 접근입니다");
                }

                return $query->where($filter, "like", "%{$keyword}%");
            });

        $paging = $query->count();
        $lists = $query->paginate(15);


        return view('admin.boardFAQManagement', [ "lists" => $lists, "paging" => $paging ]);
    }

    public function boardFAQManagementWrite()
    {
        return view('admin.boardFAQManagementWrite');
    }

    public function boardFAQManagementReply()
    {
        return view('admin.boardFAQManagementReply');
    }
    public function boardFAQManagementView(FAQ $id)
    {
        return view('admin.boardFAQManagementView', [ "post" => $id ]);
    }

    public function boardFAQManagementStore(Request $request)
    {
        $user_id = User::get_user_id();

        $insert = DB::table("board_faq")->insertGetId([
            "subject" => $request->input("subject"),
            "content" => $request->input("content"),
            "user_id" => $user_id,
            "category" => 1,

        ]);

        if ($insert)
        {
            return redirect()->route("admin.board.faq.view", [ "id" => $insert ])->with("msg", "글을 작성했습니다");
        }
        else
        {
            return back()->with("error", "글 작성에 실패했습니다. 다시 시도해 주세요");
        }
    }

    public function boardFAQManagementEdit(FAQ $id)
    {
        return view('admin.boardFAQManagementWrite', [ "post" => $id, "edit" => 1 ]);
    }

    public function boardFAQManagementUpdate(Request $request)
    {
        $faq = FAQ::find($request->input("id"));

        $faq->subject = $request->input("subject");
        $faq->content = $request->input("content");
        if ($faq->save())
        {
            return redirect()->route("admin.board.faq.view", [ "id" => $request->input("id") ])->with("msg", "글을 수정했습니다.");
        }
        else
        {
            return back()->with("error", "글 수정에 실패했습니다. 다시 시도해 주세요");
        }

    }

    public function boardFAQManagementDestroy(Request $request)
    {
        $faq = FAQ::find($request->input("id"))->delete();
        if ($faq)
        {
            return redirect()->route("admin.board.faq")->with("msg", "성공적으로 삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제하는데 실패했습니다. 다시 시도해 주세요");
        }
    }

    public function boardFAQManagementDestroyAll(Request $request)
    {
        $ids = explode(",", $request->input("id"));

        $transaction = DB::transaction(function () use ($request, $ids) {

            $return = false;

            foreach ($ids as $id)
            {
                $return = FAQ::find($id)->delete();
                if (!$return) {
                    break;
                }
            }

            return $return;

        });

        if ($transaction)
        {
            return redirect()->route("admin.board.faq")->with("msg", "선택한 게시글을 삭제했습니다");
        }
        else
        {
            return back()->with("error", "삭제하는데 실패했습니다. 다시 시도해 주세요");
        }
    }


    public function boardArchivesWrite()
    {
        return view('admin.boardArchivesWrite');
    }



    public function boardArchivesView()
    {
        return view('admin.boardArchivesView');
    }
    public function boardVideoManagementWrite()
    {
        return view('admin.boardVideoManagementWrite');
    }
    public function boardPaymentProductWrite()
    {
        return view('admin.boardPaymentProductWrite');
    }







    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
