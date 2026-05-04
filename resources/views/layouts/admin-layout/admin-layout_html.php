<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogPHP2026 - <?= $pageTitle ?> </title>
  	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<link rel="stylesheet" href="/resources/css/add-update.css">

    <link rel="stylesheet" href="resources/css/style.css"> <!-- Lien vers votre fichier CSS -->
    
</head>
<body>
	<!-- SIDEBAR -->
   <?php  include 'admin-sidebar_html.php' ?>
	<!-- SIDEBAR -->
	<section id="content">
	<!-- NAVBAR -->
       <?php  include 'admin-navbar_html.php' ?>
		<!-- NAVBAR -->
     <!-- MAIN -->
     <main>
       <?= $pageContent ?>
     </main> 
  		<!-- MAIN -->
	</section>
  		<!-- FOOTER -->
  <?php  include 'admin-footer_html.php' ?>
</body>
</html>
