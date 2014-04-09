<!DOCTYPE html>
<html lang="en" ng-app="makeItBleed">
<head>
<meta charset="UTF-8">
<title>Make It Bleed</title>
<link rel="icon" type="image/png" href="/favicon.png" />
<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.js"></script>
<style>
	@import url(//fonts.googleapis.com/css?family=Lato:700);

	body {
		margin:0;
		font-family:'Lato', sans-serif;
		color: #555;
	}
	input[type="text"] {
		font-size: 30px;
		height: 40px;
		line-height: 40px;
		width: 100%;
	}
	input[type="submit"] {
		font-size: 25px;
		height: 40px;
		line-height: 40px;
		width: 100%;
	}
	pre	{
		font-size: 20px;
	}
	label {
		font-size: 30px;
		line-height: 40px;	
	}
	form {
		margin: 40px 0;
	}
</style>
</head>
<body>
<div class="container" ng-controller="bleedController">

	<form>
		<div class="row">

			<div class="col-md-1">
				<label for="host">Host:</label>
			</div>

			<div class="col-md-8">
				<input type="text" ng-model="host" id="host" name="host" placeholder="example.com">
			</div>

			<div class="col-md-3">
				<input type="submit" ng-click="bleed(host, 443)" value="Make it Bleed">
			</div>

		</div>
		
	</form>

	<p class="bg-warning">{{message}}</p>


	<pre ng-hide="wait">{{results}}</pre>
	<pre ng-show="wait">> Connecting...<i class="fa fa-cog fa-spin"></i></pre>

</div>

<script type="text/javascript">
var app = angular.module('makeItBleed', []);

app.service('bleeder', [ '$rootScope', '$http', 
	function($rootScope, $http, $upload) 
	{
		return {

			check: function(host, port)
			{
				return $http({method: 'POST', url: '/check', data: {host:host, port: port}})
				.then(function(response) {
					return response.data;
				});
			},

		};
	}
]);

app.controller('bleedController', ['$scope', 'bleeder',
	function($scope, bleeder) 
	{
		$scope.bleed = function(host, port)
		{
			$scope.wait = true;
			$scope.results = "";
			bleeder.check(host, port).then(function(response) {

				$scope.wait = false;
				$scope.results = response.results;
				$scope.message = response.message;
			}, function() {

				$scope.wait = false;
				$scope.results = "";
				$scope.message = "An error has occured, please try again later.";
			});
		}
	}
]);


</script>
</body>
</html>
