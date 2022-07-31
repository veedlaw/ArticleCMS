<!doctype html>
<html lang="en">
	<head>
		<title>Article CMS</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="stylesheet" href="../templates/style.css" type="text/css">
		<script type="text/javascript" src="../index.js"></script>
	</head>
	<body>
		<h1>Article List</h1>
		<hr>
		<main>
			<dialog id="createDialog">
				<form action="https://webik.ms.mff.cuni.cz/~27992525/cms/articles/" method="POST">
					<input type="text" maxlength="32" id="articleNameInput" name="name">
					<button type="submit" id="createArticleButton" disabled>Create Article</button>
					<button type="button" id="cancelButton" onclick="hideArticlePrompt()">Cancel</button>
				</form>
			</dialog>
			<table id="articlesTable">