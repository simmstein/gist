<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="components/bootstrap/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="app/css/bootstrap/bootstrap.min.css" />
		<link rel="stylesheet" href="app/css/app.css" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
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
					<a class="navbar-brand" href="#">#!GIST</a>
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
							<a href="#">About</a>
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
				<li class="active">New</li>
			</ul>
			<div class="row">
				<div class="col-md-12">
					<p class="text-primary">
						<span class="glyphicon glyphicon-info-sign"></span>
						En activant le chiffrement, le code déposé ne pourra pas être forké.
					</p>
					<div class="panel panel-default">
						<div class="panel-heading">
							<input type="text" class="form-control" id="name" placeholder="Title">
						</div>
						<div class="panel-body">
							<div class="btn-toolbar">
								<div class="btn-group" id="languages">
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
											Language XML
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li>
												<a href="html">HTML/XML</a>
											</li>
											<li>
												<a href="css">CSS</a>
											</li>
											<li>
												<a href="javascript">JAVASCRIPT</a>
											</li>
											<li>
												<a href="php">PHP</a>
											</li>
											<li>
												<a href="sql">SQL</a>
											</li>
											<li>
												<a href="yaml">YAML</a>
											</li>
											<li>
												<a href="perl">PERL</a>
											</li>
											<li>
												<a href="c">C/C++</a>
											</li>
											<li>
												<a href="asp">ASP</a>
											</li>
											<li>
												<a href="python">PYTHON</a>
											</li>
											<li>
												<a href="bash">BASH</a>
											</li>
											<li>
												<a href="actionscript">ACTION SCRIPT</a>
											</li>
											<li>
												<a href="texte">TEXT</a>
											</li>
										</ul>
									</div>
									<div class="btn-group">
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
											Chiffrement : non
											<span class="caret"></span>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li>
												<a href="html">oui</a>
											</li>
											<li>
												<a href="css">non</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<p>
								<textarea rows="10" id="code" class="form-control"></textarea>
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
