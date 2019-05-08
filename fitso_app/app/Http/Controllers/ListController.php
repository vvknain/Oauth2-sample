<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ListController extends Controller
{
	private $AUTH_PATH;
	private $CLIENT_ID;
	private $REDIRECT_URL; 
	private $TOKEN_PATH; 
	private $CLIENT_SECRET;

	public function __construct(){
		$this->AUTH_PATH = 'http://localhost:8001/auth';
		$this->CLIENT_ID = 'random-client-id';
		$this->REDIRECT_URL = 'http://localhost:8000/callback';
		$this->TOKEN_PATH = 'http://localhost:8001/token';
		$this->CLIENT_SECRET = 'random-client-secret';
	}

    public function show(Request $request){

    	$access_token = $request->cookie('access_token');

    	// contact resource server with this cookie

    	return 'It is working with lots of data :D';
    }

    public function login(){
    	// prsents the login page
    	return view('login', ['dest' => $this->AUTH_PATH, 'client_id' => $this->CLIENT_ID, 'redirect_url' => $this->REDIRECT_URL]);
    }

    public function callback(Request $request){
    	// exchange authrization code for access token
    	$authorization_code = $request->get('authorization_code');

    	if (!($authorization_code)){
    		return response('No authorization code received', 500)->header('Content-Type', 'text/plain');
    	}

    	$client = new Client();
    	$res = $client->request('POST', $this->TOKEN_PATH, [
    		'form_params' => [
    			'grant_type' => 'authorization_code',
    			'authorization_code' => $authorization_code,
    			'client_id' => $this->CLIENT_ID,
    			'client_secret' => $this->CLIENT_SECRET,
    			'redirect_url' => $this->REDIRECT_URL
    		]
    	]);

    	if ($res->getStatusCode() == 200){
    		$access_token = json_decode($res->getBody(), true)['access_token'];

    		return redirect()->action('ListController@show')->withCookie('access_token', $access_token);
    	}
    	else{
    		return response('The Authorization Server returns an error' + $res->getBody(), 500)->header('Content-Type', 'text/plain');
    	}
    }
}
