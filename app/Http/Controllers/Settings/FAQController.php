<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Settings\FrequentlyAskedQuestion\UpdateOrderFAQRequest;
use App\Http\Requests\Settings\FrequentlyAskedQuestion\CreateFAQRequest;
use App\Http\Requests\Settings\FrequentlyAskedQuestion\UpdateDetailFAQRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Lib\MyHelper;
use Exception;
use Illuminate\Http\UploadedFile;

class FAQController extends Controller
{
    const SOURCE = 'core-api';
    const ROOTPATH = 'faq';

    const STEP = "step_by_step";
    const REGULAR = "regular";
    const EXAMPLE = "with_example";

    public function getFAQSetting()
    {
        $data = [
            'title'   => 'Frequently Asked Questions',
            'sub_title'   => 'Sorting FAQ',
            'menu_active'    => 'faq',
            'submenu_active' => 'faq'
        ];

        $faq = MyHelper::get(self::SOURCE, 'v1/faq');

        $existing_image = [];
        foreach ($faq['data'] as $value) {
            if (!empty($value['step_by_step_answer'])) {
                foreach ($value['step_by_step_answer'] as $step) {
                    array_push($existing_image, $step['image']);
                }
            }
        }

        //Delete image file that deleted images from database
        MyHelper::deleteImageNotExist('faq/image/answer', $existing_image);

        if (isset($faq['status']) && $faq['status'] == "success") {
            $data['result'] = $faq['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('settings.faq.faq_list', $data);
    }

    public function updateFAQSetting(UpdateOrderFAQRequest $request)
    {
        $questions = [];
        foreach ($request->id as $key => $id) {
            array_push($questions, ['id' => $id, 'order_no' => $key]);
        }

        $payload['data'] = $questions;
        $save = MyHelper::post(self::SOURCE, 'v1/faq', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/faq')->withSuccess(['Question order has been updated.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function getDetialFAQSetting($id)
    {
        $data = [
            'title'   => 'Frequently Asked Questions',
            'sub_title'   => 'Detail',
            'menu_active'    => 'faq',
            'submenu_active' => 'faq'
        ];

        $faq = MyHelper::get(self::SOURCE, 'v1/faq/' . $id);

        if (isset($faq['status']) && $faq['status'] == "success") {
            $data['detail'] = $faq['data'];
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }

        return view('settings.faq.faq_detail', $data);
    }

    public function updateDetialFAQSetting(UpdateDetailFAQRequest $request)
    {
        $path = 'image/answer';
        $step_by_step_answer = [];
        $new_step_by_step_answer = [];
        $uploadedFile['path'] = null;
        if (isset($request->id_step) && count($request->id_step) > 0) {
            foreach ($request->id_step as $key => $step) {
                try {
                    if (isset($request->file[$key])) {
                        $fileName = MyHelper::createFilename($request->file[$key]);

                        $uploadedFile = MyHelper::uploadFile($request->file[$key], self::ROOTPATH, $path, $fileName);

                        if ($uploadedFile['path'] == null) {
                            return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
                        }
                    }
                } catch (Exception $error) {
                    return back()->withErrors($error->getMessage())->withInput();
                }

                $detail_step = [
                    "id"            => $step,
                    "order_no"      => $key,
                    "image"         => $uploadedFile['path'],
                    "title"         => $request->answer_title[$key],
                    "description"   => $request->answer_description[$key]
                ];

                if ($step == null) {
                    $new_step_by_step_answer[] = $detail_step;
                } else {
                    $step_by_step_answer[] = $detail_step;
                }
                $uploadedFile['path'] = null;
            }
        }

        $answer_header = "";
        if (!empty($request->answer_header)) {
            $answer_header = $request->answer_header;
        }

        $payload = [
            'id'                    => $request->id,
            'question'              => $request->question,
            'header_text'           => $request->header,
            'answer_title'          => $answer_header,
            'question_type'         => $request->type,
            'answer'                => $request->answer,
            'step_by_step_answer'   => $step_by_step_answer,
            'new_step_by_step_answer' => $new_step_by_step_answer
        ];

        $save = MyHelper::post(self::SOURCE, 'v1/faq/update', $payload);
        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/faq/' . $request->id)->withSuccess(['Question and answer has been updated.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function newFAQSetting()
    {
        $data = [
            'title'   => 'Frequently Asked Questions',
            'sub_title'   => 'New FAQ',
            'menu_active'    => 'faq',
            'submenu_active' => 'faq'
        ];

        return view('settings.faq.faq_create', $data);
    }

    public function createFAQSetting(CreateFAQRequest $request)
    {
        $path = 'image/answer';
        $step_by_step_answer = [];
        if (!empty($request->file)) {
            foreach ($request->file as $key => $step) {
                try {
                    $fileName = MyHelper::createFilename($step);

                    $uploadedFile = MyHelper::uploadFile($step, self::ROOTPATH, $path, $fileName);

                    if ($uploadedFile['path'] == null) {
                        return back()->withErrors(['Something went wrong when uploading image. Please try again.'])->withInput();
                    }
                } catch (Exception $error) {
                    return back()->withErrors($error->getMessage())->withInput();
                }

                $detail_step = [
                    "order_no" => $key,
                    "image" => $uploadedFile['path'],
                    "title" => $request->answer_title[$key],
                    "description" => $request->answer_description[$key]
                ];

                $step_by_step_answer[] = $detail_step;
            }
        }

        $answer_header = "";
        if (!empty($request->answer_header)) {
            $answer_header = $request->answer_header;
        }

        $payload = [
            'question'              => $request->question,
            'header_text'           => $request->header,
            'answer_title'          => $answer_header,
            'question_type'         => $request->type,
            'answer'                => $request->answer,
            'step_by_step_answer'   => $step_by_step_answer
        ];

        $save = MyHelper::post(self::SOURCE, 'v1/faq/add', $payload);

        if (isset($save['status']) && $save['status'] == "success") {
            return redirect('setting/faq')->withSuccess(['New question and answer successfully added.']);
        } else {
            return back()->withErrors(['Something went wrong. Please try again.'])->withInput();
        }
    }

    public function deleteFAQSetting($id)
    {
        $delete = MyHelper::get(self::SOURCE, 'v1/faq/' . $id . '/delete/');

        if (isset($delete['status']) && $delete['status'] == "success") {
            return response()->json(['status' => 'success', 'messages' => ['Question and Answer deleted successfully']]);
        } else {
            return response()->json(['status' => 'fail', 'messages' => [$delete['message']]]);
        }
    }
}
