<?php

namespace App\Http\Controllers;

use App\TopicCategorie;
use Illuminate\Http\Request;

class TopicCategorieController extends Controller
{
    /**
     * fn pro výpis stromu
     *$data[] = array(
                        'id' => $id,
                        'name' => $name,
                        'children' => [
                            'id' => $subId,
                            'name' => $subname
                        ]
                    );
     * @return void
     */
    public function get()
    {
        $data = array();
        $mainId = "false";
        $sudData = array();
        $childOfChild = array();
        $lastPart = array();

        $kategorie = TopicCategorie::where('subKategorie', "false")->get();
        foreach ($kategorie as  $k) {

            // zpracování pole hlavních kategorií, vyhledání sub kategorií
            $subKategorie = TopicCategorie::where('id_hlavniKategorie', $k->id)->get();
            foreach ($subKategorie as $s) {

                // overeni zda sub kategorie ma dalsi sub kategorii
                $anotherSubKategorie = TopicCategorie::where('id_hlavniKategorie', $s->id)->get();
                foreach ($anotherSubKategorie as $another) {
                    // posledni vetev stromu, kdy se bude jednat o celkové návody -> lazení, editace ...
                    $lastPartOfTree = TopicCategorie::where('id_hlavniKategorie', $another->id)->get();

                    foreach ($lastPartOfTree as $last) {
                        $lastPart[] = array(
                            'id' => $last->id,
                            'name' => $last->subKategorie,
                            'to' => $last->id
                        );
                    }
                    // 3. vetev
                    $childOfChild[] = array(
                        'id' => $another->id,
                        'name' => $another->subKategorie,
                        'to' => $another->id,
                        'children' => $lastPart
                    );

                    $lastPart = array();
                }

                $sudData[] = array(
                    'id' => $s->id,
                    'name' => $s->subKategorie,
                    'to' => $s->id,
                    'children' => $childOfChild
                );

                $childOfChild = array();
            }

            // output
            $data[] = array(
                'id' => $k->id,
                'name' => $k->hlavniKategorie,
                'children' => $sudData
            );
        }

        return $data;
    }
}
