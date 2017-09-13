<?php

namespace Ayudat\Seolo;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Ayudat\Seolo\SeoloText;

class SeoloController extends Controller
{
    public function saveAlt(Request $request)
    {
        $request['content'] = trim(strip_tags($request['content']));
        if (strlen($request['content']))
        {
            //save or update
            $theKey = 'seolo-alt.' . $request['key'];
            $text = SeoloText::where('key', $theKey)->get();
            if (!isset($text[0]))
            {
                $text[0] = new SeoloText;
                $text[0]->key = $theKey;
            }
            $text[0]->content = $request['content'];
            $text[0]->save();
        }
        echo $request['content'];
    }

    public function saveText(Request $request)
    {
        // check html
        $request['content'] = trim($request['content']);
        $request['content'] = str_replace('<br>', '<br/>', $request['content']);
        $string = "<div>{$request['content']}</div>";
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        simplexml_load_string($string);

        if (!count(libxml_get_errors()) && strlen($request['content']))
        {
            $text = SeoloText::where('key', $request['key'])->get();
            $text[0]->content = $request['content'];
            if ('save' == $request['action']) $text[0]->save();
        } else {
            echo 'html_error';
        }
    }

    public function readFestives()
    {
        $ret = '';
        $text = SeoloText::where('key', 'seolo-festives')->get();
        if (isset($text[0])) $ret = $text[0]->content;
        return $ret;
    }

    public function saveFestives(Request $request)
    {
        $err = false;
        foreach (explode(PHP_EOL, $request['seolo_txtfestives']) as $num => $ln)
        {
            $ln = trim($ln);
            if ($ln)
            {
                $lnData = explode('#', $ln);
                $date = trim($lnData[0]);
                $comment = isset($lnData[0]) ? $lnData[0] : '';
                if (5 == strlen($date))
                {
                    @list($d, $m) = explode('/', $date);
                    $d = (int)$d;
                    $m = (int)$m;
                    for ($y = date('Y', time()); $y < date('Y', time())+10; $y++) {
                        if (!checkdate(''.$m, ''.$d, ''.$y)) $err = true;
                    }
                } elseif (10 == strlen($date)) {
                    @list($d, $m, $y) = explode('/', $date);
                    $d = (int)$d;
                    $m = (int)$m;
                    $y = (int)$y;
                    if (!checkdate(''.$m, ''.$d, ''.$y)) $err = true;
                } elseif (strlen($date)) $err = true;

                if (true === $err) $err = t('seolo::app.festives.err', 1, ['line-number' => $num + 1, 'line-content' => $ln]);
            }
        }

        if ($err)
        {
            echo $err;
        } else {
            //save or update
            $theKey = 'seolo-festives';
            $text = SeoloText::where('key', $theKey)->get();
            if (!isset($text[0]))
            {
                $text[0] = new SeoloText;
                $text[0]->key = $theKey;
            }
            $text[0]->content = $request['seolo_txtfestives'];
            $text[0]->save();
        }
    }

    public function saveTags(Request $request)
    {
        // try to obtain a route, if dont exists, throw an error
        try {
            route($request['route']);
        } catch (Exception $e) {
            echo 'route_error';
            die();
        }

        foreach (['tab', 'title', 'description'] as $key)
        {
            $request[$key] = htmlspecialchars(strip_tags(trim($request[$key])));
            $theKey = "seolo-tag.{$request['route']}.$key";
            $text = SeoloText::where('key', $theKey)->get();
            if (!isset($text[0]))
            {
                $text[0] = new SeoloText;
                $text[0]->key = $theKey;
            }
            $text[0]->content = $request[$key];
            $text[0]->save();
        }

        echo json_encode($request->all());
    }
}
