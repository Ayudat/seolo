<?php

use Ayudat\Seolo\SeoloText;
use Illuminate\Support\Facades\Auth;

function asset_($asset)
{
    if (config('seolo.public-path'))
    {
        $path = config('seolo.public-path');
    } else {
        $path = public_path();
    }
    return asset($asset) .'?_=' . filemtime($path.'/'.$asset);
}

/**
 * Shortener for "trans_choice", including use of table "texts"
 *
 * @param  string $key Key of the string to be tranlated.
 * @param  integer $amount Number of elements to determine singular or plural.
 * @param  array $replace [Key => Value] pairs to be replaced in the i18n string.
 * @param  bool $editable put this to false avoid to add the editable span tab wrapper
 * @return \Illuminate\Http\Response
 */
function t($key, $amount = 1, $replace = [], $editable = true)
{
    $ret = trans_choice($key, $amount, $replace);

    if ($key == $ret)
    {
        $text = SeoloText::where('key', $key)->get();
        if (isset($text[0]))
        {
            $ret = str_replace(PHP_EOL, '<br>', $text[0]->content);

            if (Auth::user() && $editable) $ret = "<span class='seolo-text' data-key='$key'>$ret</span>";
        }
    }

    return $ret;
}

//key = [tab|title|description]
function tag($routeName, $key, $default = '')
{
    $theKey = "seolo-tag.$routeName.$key";
    $ret = t($theKey, 0,[],0);
    if ($ret == $theKey) $ret = $default;
    return $ret;
}

function alt($key, $default = '')
{
    $theKey = "seolo-alt.$key";
    $ret = t($theKey, 0,[],0);
    if ($ret == $theKey) $ret = $default;
    return $ret;
}

function inSchedule()
{
    $file = resource_path('festives.txt');
    if (!file_exists($file)) abort(404, 'resources/festives.txt');

    $workDate = true;
    foreach (explode(PHP_EOL, file_get_contents($file)) as $ln)
    {
        $ln = trim($ln);
        if ($ln)
        {
            $lnData = explode('#', $ln);
            $date = trim($lnData[0]);
            if (5 == strlen($date)) $date = $date .'/'. date('Y', time());

            if (date('d/m/Y') == $date) $workDate = false;
        }

    }

    return (
        $workDate &&
        (date('N',time())>=1 && date('N',time())<=5) && //mon to fry
        (date('H',time())>=9 && date('H',time())<20) //9 to 20 hours (19:59:59)
    );
}
