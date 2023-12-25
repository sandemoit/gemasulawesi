<?php

use App\Models\Ad;
use App\Models\Posts;
use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

function getYoutubeData($url)
{
    $parsedUrl = parse_url($url);
    parse_str($parsedUrl['query'], $query_ouput);

    $videoId = $query_ouput['v'];

    $apikey = 'AIzaSyBsmJTs3VEQZB52KszlQRtdQzTtm01nZcE';
    $googleApiUrl = 'https://www.googleapis.com/youtube/v3/videos?id=' . $videoId . '&key=' . $apikey . '&part=snippet';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);

    curl_close($ch);

    $data = json_decode($response);
    return $value = json_decode(json_encode($data))->items[0];
}

function get_string_between($string, $start, $end)
{
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0) return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}

function convert_date_to_ID($date)
{
    setlocale(LC_TIME, 'id_ID');
    \Carbon\Carbon::setLocale('id');
    $converted = \Carbon\Carbon::parse($date);
    return $converted->isoFormat('D MMMM Y H:mm [WIB]');
}

function get_video_image($video_id)
{
    // Periksa apakah $video_id tidak kosong dan merupakan bilangan bulat positif
    if (empty($video_id) || !is_numeric($video_id) || $video_id <= 0) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Invalid video ID';
    }

    // Coba mencari video dengan ID yang diberikan
    $video = Video::find($video_id);

    // Periksa apakah video ditemukan
    if (!$video) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'video not found';
    }

    // Periksa apakah video memiliki properti image
    if (!$video->image) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'video does not have an image';
    }

    // Periksa apakah image memiliki properti asset
    if (!$video->image->asset) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Image does not have an asset';
    }

    // Periksa apakah asset memiliki properti file_name
    if (!$video->image->asset->file_name) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Image asset does not have a file name';
    }

    // Bangun URL dengan menggunakan Storage::url
    $url = Storage::url('public/video/' . $video->image->asset->file_name);

    return $url;
}

function get_post_image($post_id)
{
    
    
    $chachedPost = cache('post_'.$post_id);
    $post = null;

    if ($chachedPost) {
        // Gunakan data dari cache
        $post = $chachedPost;
    } else {
        // Data tidak ditemukan di cache, lakukan query dan simpan di cache
        $post = Posts::where('post_id',$post_id)->select(['post_image'])->with(['image', 'image.asset'])->first();
        cache()->put('post_'.$post_id, $post, env('CACHE_DURATION'));
    }

    // Periksa apakah post ditemukan
    if (!$post) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Post not found';
    }

    // Periksa apakah post memiliki properti image
    if (!$post->image) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Post does not have an image';
    }

    // Periksa apakah image memiliki properti asset
    if (!$post->image->asset) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Image does not have an asset';
    }

    // Periksa apakah asset memiliki properti file_name
    if (!$post->image->asset->file_name) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Image asset does not have a file name';
    }

    // Bangun URL dengan menggunakan Storage::url
    $url = Storage::url('public/photos/' . $post->image->asset->file_name);
    //$url = env('CDN_DOMAIN').'/storage/photos/' . $post->image->asset->file_name;

    return $url;
}

function get_post_thumbnail($post_id)
{

    // CACHING strategy
    $chachedPost = cache('post_'.$post_id);
    $post = null;

    if ($chachedPost) {
        // Gunakan data dari cache
        $post = $chachedPost;
    } else {
        // Data tidak ditemukan di cache, lakukan query dan simpan di cache
        $post = Posts::where('post_id',$post_id)->select(['post_image'])->with(['image', 'image.asset'])->first();
        cache()->put('post_'.$post_id, $post, env('CACHE_DURATION'));
    }

    // Coba mencari post dengan ID yang diberikan

    // Periksa apakah post ditemukan
    if (!$post) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Post not found';
    }

    // Periksa apakah post memiliki properti image
    if (!$post->image) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Post does not have an image';
    }

    // Periksa apakah image memiliki properti asset
    if (!$post->image->asset) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Image does not have an asset';
    }

    // Periksa apakah asset memiliki properti file_name
    if (!$post->image->asset->file_name) {
        // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
        return 'Image asset does not have a file name';
    }

    // Bangun URL dengan menggunakan Storage::url
    $url = 'public/photos/thumbnails/' . $post->image->asset->file_name;
    // Validasi apakah URL kosong
    if (!Storage::disk('local')->exists($url)) {
        // Lakukan resize_image jika URL kosong
        $file_path = 'public/photos/' . $post->image->asset->file_name;
        $resizedImagePath = resize_image($file_path, 129, 100);

        // Jika terdapat kesalahan, tangani sesuai kebutuhan
        if (is_string($resizedImagePath)) {
            return 'Error: ' . $resizedImagePath;
        }

        return $resizedImagePath;
    } else {
        return Storage::url($url);
    }
}

if (!function_exists('resize_image')) {
    function resize_image($imagePath, $width, $height)
    {
        // Pengecekan apakah file gambar ada
        if (!Storage::disk('local')->exists($imagePath)) {
            // Anda dapat mengganti pesan kesalahan sesuai kebutuhan
            return 'Image not found';
        }

        // Membaca file gambar menggunakan Intervention Image
        $imagePath = Storage::path($imagePath);
        $image = Image::make($imagePath);
        // Melakukan resize sesuai dengan lebar dan tinggi yang diinginkan
        $image->resize($width, $height);

        // Menyimpan gambar yang telah diresize (Anda dapat menyesuaikan path tujuan)
        $destinationPath = '/storage/photos/thumbnails/';
        $resizedImagePath = $destinationPath . basename($imagePath);
        // Membuat direktori jika belum ada
        // if (!file_exists($destinationPath)) {
        //     mkdir($destinationPath, 0755, true);
        // }

        // Simpan gambar yang telah diresize 
        $image->save(public_path($resizedImagePath));

        // Mengembalikan path gambar yang telah diresize
        return $resizedImagePath;
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null)
    {

        $cachedSetting = cache('settingCache_'.$key);
        if($cachedSetting){
            $setting = $cachedSetting;
        }else{
            // Ambil data setting dari database berdasarkan kunci
            $setting = \App\Models\Setting::where('key', $key)->first();
            cache()->put('settingCache_'.$key, $setting, 60*10);
        }

        // Kembalikan nilai setting atau default jika tidak ditemukan
        return $setting ? $setting->value : $default;
    }
}


function get_ad_content()
{
   $ad = Ad::where('position', 'content')->get();
   if($ad->count()<1){
    return null;
   }else{
       return $ad[0];
    }
}

function isValidLink($url) {
    $headers = @get_headers($url);

    return $headers && strpos($headers[0], '200') !== false;
}