<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    public function handle(Request $request) {
		$ch = curl_init($request->input('url'));
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$res = curl_exec($ch);
		
		$exts = [
		'png',
		'jpg',
		'jpeg',
		];
		
		$string = parse_url($request->input('url'), PHP_URL_PATH);
		$array = explode('.', $string);
		
		$current_ext = $array[count($array) -1];
		
		if ($request->input('url') === NULL || $request->input('url') === '' || curl_getinfo($ch, CURLINFO_HTTP_CODE) > 399 || !in_array($current_ext, $exts)) 
		{
			Log::info($ch);
			
			$response = Response::make('Oops! Try again..', 500);
		} else {
			$response = Response::make($res, 200);
			$response->header('Content-Type', curl_getinfo($ch, CURLINFO_CONTENT_TYPE));
		}
		return $response;
	}
}
