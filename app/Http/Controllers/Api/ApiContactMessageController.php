<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Lib\MyHelper;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApiContactMessageController extends Controller
{

    public function index(ContactMessage $contact_message): JsonResponse
    {
        return $this->ok('success', $contact_message->all());
    }

    public function list(Request $request): JsonResponse
    {
        $query = ContactMessage::orderBy('created_at', 'DESC');
        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('created_at', function($row) {
                return MyHelper::indonesian_date($row->created_at);
            })
            ->addColumn('action', function ($row) {
                return ' <a class="btn btn-sm btn-info" href="' . route('landing_page.contact_message.detail', ['id' => $row->id]) . '">
                            <li class="fa fa-search" aria-hidden="true"></li>
                        </a>';
            })
            ->rawColumns(['action'])->make(true);
    }

    public function show(Request $request, $id): JsonResponse
    {
        $contact_message = ContactMessage::find($id);
        return $this->ok("success", $contact_message);
    }

}