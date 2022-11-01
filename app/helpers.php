<?php

use App\Models\RequisitionProduct;
use App\Models\WebsiteParameter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;


function showPermission($permission)
{
    $array = explode('-', $permission);
    $string = '';
    foreach ($array as  $value) {
        $string .= " " . ucfirst($value);
    }
    return $string;
}
//////For News

function has_in_array($arrayTypeStringWithComaSepareted, $machedValue)
{
    $arry = explode(',', $arrayTypeStringWithComaSepareted);
    if (in_array($machedValue, $arry)) {
        return true;
    } else {
        return false;
    }
}
function checked_if_have_this_product($product_id, $requisition_id)
{
    $req_product = RequisitionProduct::where('product_id', $product_id)->where('requisition_id', $requisition_id)->first();
    return $req_product;
    // dd($req_product);
    if (!$req_product) {
        return false;
    }
    return true;
}

function getLang()
{
    if (Auth::check()) {
        $user = Auth::user();
        return $user->language;
    } elseif (Cookie::has('lang')) {
        return Cookie::get('lang');
    } else {
        return config('locale');
    }
}
function setLang($lang)
{
    Cookie::queue('lang', $lang, 1000000);
    if (Auth::check()) {
        $user = Auth::user();
        $user->language = $lang;
        $user->save();
    }
}
function langRoute($route)
{
    return route($route, ['lan' => getLang()]);
}


/**
 * Return sizes readable by humans
 */
function human_filesize($bytes, $decimals = 2)
{
    $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];
    $factor = floor((strlen($bytes) - 1) / 3);

    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) .
        @$size[$factor];
}

/**
 * Is the mime type an image
 */
function is_image($mimeType)
{
    return starts_with($mimeType, 'image/');
}

// function human_time()
// {
//     $time = ['seconds', 'minutes', 'hours', 'days', 'months', 'years'];
//     $factor = floor()
// }
function timestamToTimeDiffarece($star_date, $end_date)
{

    $start =  Carbon::create($star_date);
    $end =  Carbon::create($end_date);
    $minutes = $end->diffInMinutes($start, true);
    $hours = floor($minutes / 60);
    $min = $minutes - ($hours * 60);

    $hourMunite = $hours . "h " . $min . "m ";
    return $hourMunite;
}
function timestamToHoursDiffarece($star_date, $end_date)
{
    $start =  Carbon::create($star_date);
    $end =  Carbon::create($end_date);
    $Hours = $end->diffInHours($start, true);
    return $Hours;
}
function editions()
{
    return explode(',', WebsiteParameter::first()->news_editions);
}
//SEO

function embededURL($url)
{
    if (strpos($url, "watch?v=")) {
        return str_replace("watch?v=", "embed/", $url);
    } else {
        return $url;
    }
}
function image_slug($text)
{
    $string = Str::slug($text);
    return $string;
}
function alt($text)
{
    if ($text) {
        $text = $text;
    } else {
        $text = 'Standard-News-CMS_MF';
    }
    $alt = explode('_', $text);
    return $alt[0];
    // return $string;
}

// SEO

function custom_slug($text)
{
    $date = date('ynjGis');
    $string = str_slug($text);
    $rand = strtolower(str_random(8));
    $string = substr($string, 0, 100);
    return $date . '-' . $rand . '-' . $string;
}
function mySlug($text)
{
    $str = str_replace(' ', '-', $text);
    $smallLatter = strtolower($str);
    $string = substr($smallLatter, 0, 100);
    return $string;
}


function custom_name($text, $limit)
{
    if (strlen($text) > $limit) {
        return str_pad(substr($text, 0, ($limit - 2)), ($limit + 1), '.');
    } else {
        return $text;
    }
}

function custom_title($text, $limit)
{
    if (strlen($text) > $limit) {
        return substr($text, 0, $limit);
    } else {
        return $text;
    }
}

function new_slug($text = '')
{
    // $string = str_slug($text);
    // if($string){
    //   return $string;
    // }else{

    //   // return strtolower(preg_replace('/\s+/u', '-', $text));
    //   return strtolower(preg_replace('/[\W\s\/]+/', '-', $text));
    //   # return preg_replace('/\s+/u', '-', trim($string));
    // }

    $text = $text ?: '-';

    $generator = new \Vnsdks\SlugGenerator\SlugGenerator;
    return $generator->generate($text);
}

function en2bnNumber($number)
{
    $search_array = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
    $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
    $en_number = str_replace($search_array, $replace_array, $number);

    return $en_number;
}

function en2bnMonthName($name)
{
    $search_array = array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
    $replace_array = array("জানুয়ারি", "ফেব্রুয়ারি", "মার্চ", "এপ্রিল", "মে", "জুন", "জুলাই", "আগস্ট", "সেপ্টেম্বর", "অক্টোবর", "নভেম্বর", "ডিসেম্বর");
    $result = str_replace($search_array, $replace_array, $name);

    return $result;
}

function en2bnDate($date)
{
    // $date = date("l, j F Y");
    $engDATE = array(
        '1', '2', '3', '4', '5', '6', '7', '8', '9', '0', 'January', 'February', 'March', 'April',
        'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Saturday', 'Sunday',
        'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'
    );
    $bangDATE = array(
        '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯', '০', 'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে',
        'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর', 'শনিবার', 'রবিবার', 'সোমবার', 'মঙ্গলবার', '
বুধবার', 'বৃহস্পতিবার', 'শুক্রবার'
    );
    $convertedDATE = str_replace($engDATE, $bangDATE, $date);
    return "$convertedDATE";

    // {{date("d,M,y", strtotime("now"))}},


}

//bangladate


function menuSubmenu($menu, $submenu)
{
    $request = request();
    $request->session()->forget(['lsbm', 'lsbsm']);
    $request->session()->put(['lsbm' => $menu, 'lsbsm' => $submenu]);
    return true;
}
function menuSubmenuSubsubMenu($menu, $submenu, $subsubmenu)
{
    $request = request();
    $request->session()->forget(['lsbm', 'lsbsm', 'lsbssm']);
    $request->session()->put(['lsbm' => $menu, 'lsbsm' => $submenu, 'lsbssm' => $subsubmenu]);
    return true;
}
function number_to_word( $num = '' )
{
    $num    = ( string ) ( ( int ) $num );

    if( ( int ) ( $num ) && ctype_digit( $num ) )
    {
        $words  = array( );

        $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );

        $list1  = array('','one','two','three','four','five','six','seven',
            'eight','nine','ten','eleven','twelve','thirteen','fourteen',
            'fifteen','sixteen','seventeen','eighteen','nineteen');

        $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
            'seventy','eighty','ninety','hundred');

        $list3  = array('','thousand','lac','crore','trillion',
            'quadrillion','quintillion','sextillion','septillion',
            'octillion','nonillion','decillion','undecillion',
            'duodecillion','tredecillion','quattuordecillion',
            'quindecillion','sexdecillion','septendecillion',
            'octodecillion','novemdecillion','vigintillion');

        $num_length = strlen( $num );
        $levels = ( int ) ( ( $num_length + 2 ) / 3 );
        $max_length = $levels * 3;
        $num    = substr( '00'.$num , -$max_length );
        $num_levels = str_split( $num , 3 );

        foreach( $num_levels as $num_part )
        {
            $levels--;
            $hundreds   = ( int ) ( $num_part / 100 );
            $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
            $tens       = ( int ) ( $num_part % 100 );
            $singles    = '';

            if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } $commas = count( $words ); if( $commas > 1 )
        {
            $commas = $commas - 1;
        }

        $words  = implode( ', ' , $words );

        $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
        if( $commas )
        {
            $words  = str_replace( ',' , ' and' , $words );
        }

        return $words;
    }
    else if( ! ( ( int ) $num ) )
    {
        return 'Zero';
    }
    return '';
}
function bdMobile($mobile)
{
    $number = trim($mobile);
    $c_code = '880';
    $cc_count = strlen($c_code);

    if (substr($number, 0, 2) == '00') {
        $number = ltrim($number, '0');
    }
    if (substr($number, 0, 1) == '0') {
        $number = ltrim($number, '0');
    }
    if (substr($number, 0, 1) == '+') {
        $number = ltrim($number, '+');
    }
    if (substr($number, 0, $cc_count) == $c_code) {
        $number = substr($number, $cc_count);
    }
    if (substr($c_code, -1) == 0) {
        $number = ltrim($number, '0');
    }
    $finalNumber = $c_code . $number;

    return $finalNumber;
}

function bdMobileWithCode($mobile)
{
    $number = trim($mobile);
    $c_code = '+880';
    $cc_count = strlen($c_code);

    if (substr($number, 0, 2) == '00') {
        $number = ltrim($number, '0');
    }
    if (substr($number, 0, 1) == '0') {
        $number = ltrim($number, '0');
    }
    if (substr($number, 0, 1) == '+') {
        $number = ltrim($number, '+');
    }
    if (substr($number, 0, $cc_count) == $c_code) {
        $number = substr($number, $cc_count);
    }
    if (substr($c_code, -1) == 0) {
        $number = ltrim($number, '0');
    }
    $finalNumber = $c_code . $number;

    return $finalNumber;
}

function bdMobileWithoutCode($mobile)
{
    $number = trim($mobile);
    $c_code = '0';
    $cc_count = strlen($c_code);
    if (substr($number, 0, 2) == '00') {
        $number = ltrim($number, '0');
    }
    if (substr($number, 0, 1) == '0') {
        $number = ltrim($number, '0');
    }
    if (substr($number, 0, 1) == '+') {
        $number = ltrim($number, '+');
    }
    if (substr($number, 0, $cc_count) == $c_code) {
        $number = substr($number, $cc_count);
    }
    if (substr($c_code, -1) == 0) {
        $number = ltrim($number, '0');
    }
    $finalNumber = $c_code . $number;
    return $finalNumber;
}

function smsUrl($to, $msg)
{
    $userId = config('parameter.smsUserId');
    $pass = config('parameter.smsPassword');
    $masking = config('parameter.MaskingID');

    // return "http://66.45.237.70/maskingapi.php?username={$userId}&password={$pass}&number={$to}&message={$msg}&senderid={$masking}";
    //   return "https://enterprise.messageanalytica.com/api/sms/v1/send?userid={$userId}&body={$msg}&recipient={$to}&sender={$masking}&password={$pass}";


    return "https://enterprise-api.messageanalytica.com/api/v1/channels/sms?apiKey=4F165DC3771A5ED45A3DA7FBC3F4&recipient={$to}&from=8809612737373&message={$msg}";
}

// https://enterprise.messageanalytica.com/api/sms/v1/send?userid=softcodeint@gmail.com&body=test&recipient=8801518643843&sender=8809612737373&password=newSSEE__2020

    //for custom package
    //https://github.com/gocanto/gocanto-pkg
    //https://laravel.com/docs/5.2/packages
    //http://stackoverflow.com/questions/19133020/using-models-on-packages
    //http://kaltencoder.com/2015/07/laravel-5-package-creation-tutorial-part-1/
    //http://laravel-recipes.com/recipes/50/creating-a-helpers-file
