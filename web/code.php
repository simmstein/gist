<?php

require '../vendor/autoload.php';

$geshi = new GeSHi(file_get_contents(__FILE__), 'xml');
$geshi->enable_classes();
$geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="components/bootstrap/dist/css/bootstrap.min.css" />
		<link rel="stylesheet" href="app/css/bootstrap/bootstrap.min.css" />
		<link rel="stylesheet" href="app/css/geshi/twilight.css" />
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
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#view" data-toggle="tab">View</a>
						</li>
						<li>
							<a href="#revisions" data-toggle="tab">
								Révisions
								<span class="badge">4</span>
							</a>
						</li>
					</ul>	
					<div class="panel panel-default">
						<div class="panel-heading">
							<div class="pull-right actions">
								<a class="btn btn-default btn-sm">
									<span class="glyphicon glyphicon-eye-open"></span>
									RAW
								</a>
								<a class="btn btn-default btn-sm">
									<span class="glyphicon glyphicon-save-file"></span>
									Download
								</a>
								<a class="btn btn-success btn-sm">
									<span class="glyphicon glyphicon-copy"></span>
									Fork
								</a>
							</div>

							Untitled
						</div>
						<div class="panel-body">
							<div class="tab-content">
								<div id="view" class="tab-pane active in">
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
										</div>
									</div>

									<div id="render">
										<?php
											echo $geshi->parse_code();
										?>	
									</div>
								</div>
								<div id="revisions" class="tab-pane out">
									<div class="commit">
										<p>
											Commit <strong class="text-warning">82110bbb53fbc5bf010ea4a50d999ab7cb22ae4f</strong>
											<br/>
											Date:   Mon May 4 23:09:44 2015 +0200
										</p>

										<p>
											<a class="btn btn-primary">
												Afficher
											</a>
											<a class="btn btn-default">
												DIFF
											</a>
										</p>
									</div>
									<hr>
									<div class="commit">
										<p>
											Commit <strong class="text-warning">82110bbb53fbc5bf010ea4a50d999ab7cb22ae4f</strong>
											<br/>
											Date:   Mon May 4 23:09:44 2015 +0200
										</p>

										<p>
											<a class="btn btn-primary">
												Afficher
											</a>
											<a class="btn btn-default">
												DIFF
											</a>
										</p>
									</div>
									<hr>
									<div class="commit">
										<p>
											Commit <strong class="text-warning">82110bbb53fbc5bf010ea4a50d999ab7cb22ae4f</strong>
											<br/>
											Date:   Mon May 4 23:09:44 2015 +0200
										</p>

										<p>
											<a class="btn btn-primary">
												Afficher
											</a>
											<a class="btn btn-default">
												DIFF
											</a>
										</p>
									</div>
									<hr>
									<div class="commit">
										<p>
											Commit <strong class="text-warning">82110bbb53fbc5bf010ea4a50d999ab7cb22ae4f</strong>
											<br/>
											Date:   Mon May 4 23:09:44 2015 +0200
										</p>

										<p>
											<a class="btn btn-primary">
												Afficher
											</a>
											<a class="btn btn-default">
												DIFF
											</a>
										</p>
									</div>
								</div>
							</div>
						</div>
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
