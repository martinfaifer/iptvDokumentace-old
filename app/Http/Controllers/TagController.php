<?php

namespace App\Http\Controllers;

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
        // some code here...
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
        // somde code here ...
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
