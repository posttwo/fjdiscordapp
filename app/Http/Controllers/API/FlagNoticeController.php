<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Auth;
use App\FlagNotice;
use Illuminate\Support\Collection;

class FlagNoticeController extends \App\Http\Controllers\Controller
{

    public function addFlagNotice(Request $request)
    {
        logger(Auth::user()->id . " added Flag Notice", ['Request' => $request->all()]);
        $validatedData = $request->validate([
            'context' => 'required|string',
            'value' => 'required|string',
            'text' => 'required|string'
        ]);

        $notice = new FlagNotice;
        $notice->user_id = Auth::user()->id;

        $notice->context = htmlspecialchars($request->input('context'));
        $notice->value = htmlspecialchars($request->input('value'));
        $notice->text = htmlspecialchars($request->input('text'));
        $notice->revoked = false;
        $notice->save();

        return $notice;

    }

    public function deleteFlagNotice(FlagNotice $flagNotice)
    {
        logger(Auth::user()->id . " deleted Flag Notice", ['FlagNotice' => $flagNotice]);
        $flagNotice->delete();
        return $flagNotice;
    }

    public function getFlagNotices(Request $request)
    {
        logger(Auth::user()->id . " retrieved Flag Notices", ['Request' => $request->all()]);
        $contentNotices = $this->getNotices('contentId', $request->input('contentId'));
        $commentNotices = $this->getNotices('commentId', $request->input('commentId'));
        $userNotices = $this->getNotices('userId', $request->input('userId'));
        $imageNotices  = FlagNotice::where('context', 'imageId')->whereIn('value', $request->input('imageId', []))->with('poster')->get();
        
        $notices = new Collection();
        $notices = $notices->merge($contentNotices);
        $notices = $notices->merge($commentNotices);
        $notices = $notices->merge($userNotices);
        $notices = $notices->merge($imageNotices);

        return $notices;
    }

    private function getNotices($context, $value)
    {
        return FlagNotice::where('context', $context)->where('value', $value)->with('poster')->get();
    }
}
