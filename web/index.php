<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="components/bootstrap/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="app/css/bootstrap.min.css" />
		<link rel="stylesheet" href="app/css/app.css" />
		<title>GIST</title>
	</head>
	<body>
		<nav class="navbar navbar-inverse">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-menu">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">XBT</a>
				</div>
				<div class="collapse navbar-collapse" id="main-menu">
					<ul class="nav navbar-nav">
						<li class="active">
							<a href="#">
								Home
								<span class="sr-only">(current)</span>
							</a>
						</li>
						<li>
							<a href="#">Upload</a>
						</li>
						<li>
							<a href="#">Profile</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
		<div class="container-fluid" id="container">
			<ul class="breadcrumb">
				<li>
					<a href="#">Home</a>
				</li>
				<li class="active">New gist</li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<input type="text" class="form-control" id="name" placeholder="Title">
						</div>
						<div class="panel-body">
							<div class="btn-toolbar">
								<div class="btn-group" id="languages">
									<button class="btn btn-default" value="html">HTML/XML</button>
									<button class="btn btn-primary" value="css">CSS</button>
									<button class="btn btn-default" value="javascript">JAVASCRIPT</button>
									<button class="btn btn-default" value="php">PHP</button>
									<button class="btn btn-default" value="sql">SQL</button>
									<button class="btn btn-default" value="yaml">YAML</button>
									<button class="btn btn-default" value="perl">PERL</button>
									<button class="btn btn-default" value="c">C/C++</button>
									<button class="btn btn-default" value="asp">ASP</button>
									<button class="btn btn-default" value="python">PYTHON</button>
									<button class="btn btn-default" value="bash">BASH</button>
									<button class="btn btn-default" value="actionscript">ACTION SCRIPT</button>
									<button class="btn btn-default" value="texte">TEXT</button>
								</div>
							</div>
							<p>
								<textarea rows="10" id="code" class="form-control"></textarea>
							</p>
							<p>
								<label for="crypt">
									Chiffrer le contenu
								</label>
								<input type="checkbox" id="crypt" />
							</p>
							<p>
								<input type="submit" class="btn btn-primary" value="Envoyer">
							</p>
						</div>
					</div>
				</div>
			</div>
			<script src="components/jquery/dist/jquery.min.js"></script>
			<script src="components/bootstrap/dist/js/bootstrap.min.js"></script>
			<script src="components/select2-dist/dist/js/select2.full.min.js"></script>
			<script src="app/js/app.js"></script>
		</div>
	</body>
</html>
