<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use ReallySimpleJWT\Token;

class AuthController extends Controller
{
	private $CODE_LIFE_SPAN;
	private $JWT_LIFE_SPAN;
	private $ISSUER;
	private $authoriation_codes;

	public function __construct(){
		$this->CODE_LIFE_SPAN = 600;
		$this->JWT_LIFE_SPAN = 1000;
		$this->ISSUER = 'Oauth-server';
	}

	private function verify_client_info($cl_id, $red_url){
		return true;
	}

	private function generate_authorization_code($cl_id, $red_url){
		// encrypted using laravel encrypter, based on env('APP_KEY')
		$authorization_code = Crypt::encryptString($cl_id);

		$expiration_date = Carbon::now()->addSeconds($this->CODE_LIFE_SPAN);

		$this->authoriation_codes = [                   // on real server redis should be used
			$authorization_code => [
				'client_id' => $cl_id,
				'redirect_url' => $red_url,
				'exp' => $expiration_date
			]
		];

		return $authorization_code;
	}

	private function authentic_user_credentials($usr, $psswrd){
		return true;
	}

	private function process_redirect_url($red_url, $auth_code){
		return "$red_url?authorization_code=$auth_code";
	}

	private function authenticate_client($cl_id, $cl_scrt){
		return true;
	}

	private function verify_authorization_code($auth_code, $cl_id, $red_url){
		$record_code = $this->authoriation_codes[$auth_code];
		if (! $record_code){
			return true;
		}
		
		$client_id_in_record = $record_code['client_id'];
		$redirect_url_in_record = $record_code['redirect_url'];
		$exp = $record_code['exp'];

		if ($cl_id != $client_id_in_record || $redirect_url_in_record != $red_url){
			return false;
		}

		if ($exp->lt(Carbon::now())){
			return false;
		}

		unset($this->authoriation_codes[$auth_code]);

		return true;
	}

	private function generate_access_token($issuer){
		$payload = [
			'iss' => $issuer,
			'exp' => Carbon::now()->addSeconds($this->JWT_LIFE_SPAN)
		];

		$secret = 'hbhbijniubihbihbi'; // copy paste your own openssl rsa private key

		$token = 'anjhdbvdshbfshdv';        // Token::customPayload($payload, $secret); // uncomment this line
		return $token;
	}

    public function show(){
    	return 'Auth Server is working';
    }

    public function auth(Request $request){
    	$client_id = $request->get('client_id');
    	$redirect_url = $request->get('redirect_url');

    	if ($client_id && $redirect_url) {
    		if (!($this->verify_client_info($client_id, $redirect_url))){
    			return response('Invalid CLient', 401)->header('Content-Type', 'text/plain');
    		}

    		return view('AC_grant_access', ['client_id' => $client_id, 'redirect_url' => $redirect_url]);
    	}
    	else{
    		return response('Invalid Request', 400)->header('Content-Type', 'text/plain');
    	}
    }

    public function signin(Request $request){
    	$username = $request->input('username');
    	$password = $request->input('password');
    	$client_id = $request->input('client_id');
    	$redirect_url = $request->input('redirect_url');

    	if (!($username && $password && $client_id && $redirect_url)){
    		return response('Invalid Request', 400)->header('Content-Type', 'text/plain');
    	}

    	if (!($this->verify_client_info($client_id, $redirect_url))){
    		return response('Invalid CLient for Signin', 401)->header('Content-Type', 'text/plain');
    	}

    	if (!($this->authentic_user_credentials($username, $password))){
    		return response('Access Denied', 401)->header('Content-Type', 'text/plain');
    	}

    	$authorization_code = $this->generate_authorization_code($client_id, $redirect_url);

    	$url = $this->process_redirect_url($redirect_url, $authorization_code);

    	return redirect()->away($url);

    }

    public function exchange_code_for_token(Request $request){
    	$authorization_code = $request->input('authorization_code');
    	$client_id = $request->input('client_id');
    	$client_secret = $request->input('client_secret');
    	$redirect_url = $request->input('redirect_url');

    	if (!($authorization_code && $client_secret && $client_id && $redirect_url)){
    		return response("Invalid Request for token exchange", 400)->header('Content-Type', 'text/plain');
    	}

    	if (!($this->authenticate_client($client_id, $client_secret))){
    		return response('Invalid Client for token', 401)->header('Content-Type', 'text/plain');
    	}

    	if (!($this->verify_authorization_code($authorization_code, $client_id, $redirect_url))){
    		return response('Access Denied for wrong authorization code', 401)->header('Content-Type', 'text/plain');
    	}

    	$access_token = $this->generate_access_token($this->ISSUER);

    	return response()->json([
    		'access_token' => $access_token,
    		'token_type' => 'JWT',
    		'expires_in' => $this->JWT_LIFE_SPAN
    	]);
    }
}
