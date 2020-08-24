<?php

namespace App\Http\Controllers;

use App\Channels;
use App\Device;
use App\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{

    /**
     * zobrazení všech tagu
     *
     * @return void
     */
    public function show()
    {
        return Tag::get();
    }

    /**
     * fn pro vytvorení nového stitku do systému pro channel
     *
     * @return void
     */
    public function createTagForChannel(Request $request)
    {

        if ($request->tagName == "" || $request->color == "") {
            return [
                'isAlert' => "isAlert",
                'stat' => "error",
                'msg' => "Štítek se nepodařilo vytvořit!"
            ];
        }

        Tag::create([
            'tagName' => $request->tagName,
            'color' => $request->color
        ]);

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Štítek vytvořen"
        ];
    }


    /**
     * fn pro vytvoreni stitku do systemu pro zarizeni
     *
     * @param Request $request
     * @return void
     */
    public function createTagFroDevice(Request $request)
    {
        // some code here ...
    }


    /**
     * fn pro odebrání stitku ze systému
     *
     * @param Request $request
     * @return void
     */
    public function remove(Request $request)
    {
        Tag::find($request->tagId)->delete();


        if (Channels::where('tags', $request->tagId)->first()) {
            Channels::where('tags', $request->tagId)->update(['tags', null]);
        }
        if (Device::where('tags', $request->tagId)->first()) {
            Device::where('tags', $request->tagId)->update(['tags', null]);
        }

        return [
            'isAlert' => "isAlert",
            'stat' => "success",
            'msg' => "Štítek byl smazán"
        ];
    }


    /**
     * fn pro editaci stitku
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        // some code here ...

    }


    /**
     * fn pro zjistení zda tag má nějakou akci na sebe vazanou
     *
     * @return void
     */
    public static function checkIfTagHasAction($tagId, $channelId)
    {

        // oevreni zda tag existuje
        if (Tag::where('id', $tagId)->first()) {
            // tag existuje, probiha zjistovani zda má na sebe vazanou akci
            $tag = Tag::where('id', $tagId)->first();
            if ($tag->action != null) {
                // zavolání tagActionControlleru na vyvolani akce
                TagActionController::actionCheck($tag->action, $channelId);
            }
        }
    }
}
