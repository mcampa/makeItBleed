<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('home');
});

Route::any('check', function()
{
	$host = Input::get('host');

	$port = Input::get('port');

	$bleeder = new Bleeder($host, $port);

	try {

		$results = $bleeder->check();

		Log::info('makeitbleed: '.$host);

		$n = 0;
        do {
            $n++;
            $filepath = storage_path()."/bleeds/{$host}.{$n}.bin";

        } while (file_exists($filepath));

        file_put_contents($filepath, $bleeder->data);

		return Response::json($bleeder);
		
	} catch (Hoa\Socket\Exception $e) {

		ob_get_clean();
		return Response::json(array(
			'message' => 'Whoops! There was an error.',
			'results' => $e->getFormattedMessage(),
		));
	}
});