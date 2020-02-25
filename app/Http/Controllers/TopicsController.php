<?php

namespace App\Http\Controllers;

use App\Topics;
use Illuminate\Http\Request;

class TopicsController extends Controller
{

    /**
     * fn pro získání článku do wiki
     *
     * @param Request $request
     * @return void
     */
    public function getTopic(Request $request)
    {
        return Topics::where('kategorie_id', $request->id)->first();
    }

    public static function findbyKategoryId($kategoryId)
    {
        return  Topics::where('kategorie_id', $kategoryId)->first();
    }

    public function edit(Request $request)
    {
        $data = $this->findbyKategoryId($request->topicId);

        $update = Topics::find($data->id);
        $update->creator = $request->user;
        $update->text = $request->editedText;



        $update->save();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Článek byl z editován"
        ];
    }


    public function create(Request $request)
    {
        Topics::create([
            'kategorie_id' => $request->topicId,
            'text' => $request->newText,
            'creator' => $request->user,
        ]);
        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Založen nový uživatel"
        ];
    }

    public function remove(Request $request)
    {
        $data = $this->findbyKategoryId($request->topicId);
        Topics::find($data->id)->delete();

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Článek byl smazán"
        ];
    }
}
