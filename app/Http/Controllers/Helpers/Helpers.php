<?php
/**
 * Created by PhpStorm.
 * User: BeeTimberlake
 * Date: 1/6/2019
 * Time: 1:00 PM
 */

namespace App\Http\Controllers\Helpers;

use DateTime;
use Exception, Log;
use Carbon\Carbon;
use Image;

class Helpers
{

    public static function isValidJson($strJson): bool
    {
        json_decode($strJson);
        return (json_last_error() === JSON_ERROR_NONE);
    }

    public static function time_elapsed_string($datetime, $full = false): string
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) {
            $string = array_slice($string, 0, 1);
        }
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    public static function upLoadImage($file, $uploadPath, $post_fix = '', $includeName = false)
    {
        #Image
        $fileExt = strtolower($file->getClientOriginalExtension());
        $post_fix = (string)self::removeSpaces($post_fix);
        if ($includeName) {
            $imgOriginalName = self::subFileName($file->getClientOriginalName()) . md5('^');
            $img_filename = $imgOriginalName . md5(date('Y-m-d H:i:s') . microtime()) . time() . $post_fix . '.' . $fileExt;
        } else {
            $img_filename = md5(date('Y-m-d H:i:s') . microtime()) . time() . $post_fix . '.' . $fileExt;
        }
        if ($fileExt === 'gif' || $fileExt === 'svg') {
            $location = public_path($uploadPath);
            $file->move($location, $img_filename);
        } else {
            $img = Image::make($file);
            $location = public_path($uploadPath . $img_filename);
            $img->save($location)->destroy();
        }
        return $img_filename;
        #Image
    }

    public static function removeSpaces($str = '')
    {
        return preg_replace('/\s+/', '', (string)$str);
    }

    public static function isValidPhoneNumber($phone_number)
    {
        $phone_number = preg_replace('/\s+/', '', $phone_number);
        $matches[][] = [];
        $key = null;
        $regex = '/^\+.[\d]+$|^[\d]+$/';
        preg_match_all($regex, $phone_number, $matches);
        foreach ($matches[0] as $iValue) {
            $key = $iValue;
        }
        if (isset($key) && count($matches) > 0) {
            return ['key' => $key, 'matches' => $matches];
        }
        return false;
    }

    public static function toISO8601DateString($string): string
    {
        try {
            return Carbon::parse($string)->format('c');
        } catch (Exception $ex) {
            Log::info("toISO8601DateString: {$ex->getMessage()}");
        }
        return Carbon::now()->format('c');
    }

    public static function toFormatDateString($string, $format = 'c'): string
    {
        try {
            return Carbon::parse($string)->format($format);
        } catch (Exception $ex) {
            Log::info("toFormatDateString: {$ex->getMessage()}");
        }
        return Carbon::now()->format($format);
    }

    public static function isEngText($text): bool
    {
        return (strlen($text) === strlen(utf8_decode($text))); // is english
    }


    public static function subFileName($filename, $length_sub = 28)
    {
        $filenameLength = mb_strlen($filename, 'UTF-8');
        return ($filenameLength > $length_sub) ? mb_substr($filename, 0, 28, 'UTF-8') : $filename;
    }

    public static function isNumber($n)
    {
        $n = preg_replace("/\s+/", '', $n);
        $matches[][] = [];
        $key = null;
        $regex = '/^\d+(\.\d+)?$/';
        preg_match_all($regex, $n, $matches);
        foreach ($matches[0] as $iValue) {
            $key = $iValue;
        }
        if (isset($key) && count($matches) > 0) {
            return ['key' => $key, 'matches' => $matches];
        }
        return false;
    }

    public static function isAjax($request): bool
    {
        return $request->expectsJson() || $request->ajax() || $request->wantsJson();
    }

    public static function removeFile($fullPath = ''): bool
    {
        $file_path = public_path($fullPath);
        if ($fullPath !== '' && file_exists($file_path) && is_file($file_path)) {
            unlink($file_path);
            return true;
        }
        return false;
    }

    public static function decode64($val)
    {
        $checkBase64 = base64_decode($val);
        if (!json_encode($checkBase64)) {
            $val = base64_encode($val);
        }
        return base64_decode($val);
    }

    public static function addRequest($request, $data = []): void
    {
        $request->request->add($data);
    }

    public static function trimBase64($str): string
    {
        return strtr(rtrim(base64_encode($str), '='), '+/', '-_');
    }

    public static function getIdInArray($array, $term)
    {
        foreach ($array as $key => $value) {
            if ($value === $term) {
                return $key;
            }
        }
        throw new \UnexpectedValueException;
    }

    public static function days_to_seconds($days): int
    {
        if (is_numeric($days)) {
            return 86400 * $days;
        }
        return 0;
    }

    public static function seconds_to_minutes($seconds)
    {
        if (is_numeric($seconds)) {
            return $seconds / 60;
        }

        return 0;
    }

    public static function deleteField($obj, $prop): void
    {
        unset($obj->$prop);
    }

    public static function getDefaultConfigCookie()
    {

        return [
            'domain' => self::getServerDomainName(),
            'path' => '/',
            'httpOnly' => self::isHttps() ? 1 : 0,
        ];
    }

    public static function cookie($type = 'get', $name = '', $value = null, $time = 3600, $path = '/', $domain = '', $secure = 0, $httponly = false)
    {
        $cookie = null;
        switch ($type) {
            case 'set':
                setcookie($name, $value, time() + $time, $path, $domain, $secure, $httponly);
                break;
            case 'set_raw':
                setrawcookie($name, $value, time() + $time, $path, $domain, $secure, $httponly);
                break;
            case 'get':
                $cookie = isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
                break;
            case 'delete':
                $url = self::getServerDomainName();
                unset($_COOKIE[$name]);
                // empty value and expiration 3600 hours before
                setcookie($name, '', time() - 3600 * 60 * 60 * 1000, '/', $url, self::isHttps() ? 1 : 0);
                break;
        }
        return $cookie;
    }

    public static function isHttps(): bool
    {
        if (isset($_SERVER['HTTPS'])) {
            if ($_SERVER['HTTPS'] !== 'off') {
                return true;
            }
        }
        return false;
    }

    public static function getServerName()
    {
        return $_SERVER['SERVER_NAME'];
    }

    public static function getServerDomainName()
    {
        // if( !function_exists('apache_request_headers') ) {
        //     ///
        //     function apache_request_headers() {
        //       $arh = array();
        //       $rx_http = '/\AHTTP_/';
        //       foreach($_SERVER as $key => $val) {
        //         if( preg_match($rx_http, $key) ) {
        //           $arh_key = preg_replace($rx_http, '', $key);
        //           $rx_matches = array();
        //           // do some nasty string manipulations to restore the original letter case
        //           // this should work in most cases
        //           $rx_matches = explode('_', $arh_key);
        //           if( count($rx_matches) > 0 and strlen($arh_key) > 2 ) {
        //             foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        //             $arh_key = implode('-', $rx_matches);
        //           }
        //           $arh[$arh_key] = $val;
        //         }
        //       }
        //       return( $arh );
        //     }
        //     ///
        // }
        //$heads = apache_request_headers();
        return isset($_SERVER['HTTP_HOST']) ? parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) : $_SERVER['SERVER_NAME'];
    }

}
