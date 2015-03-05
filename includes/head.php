<?php include 'includes/data/settings.php'; if($css == "Light") { $csslink = "//maxcdn.bootstrapcdn.com/bootswatch/3.3.2/flatly/bootstrap.min.css"; } elseif($css == "Dark") { $csslink = "//maxcdn.bootstrapcdn.com/bootswatch/3.3.2/darkly/bootstrap.min.css"; } else { $csslink = "//maxcdn.bootstrapcdn.com/bootswatch/3.3.2/flatly/bootstrap.min.css"; }?>
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta name="description" content="Ban's list for our server.">
   <meta name="author" content="Yive">
   <link rel="shortcut icon" href="includes/img/minecraft.ico">
   <link href=<?php echo '"'.$csslink.'"'; ?> rel="stylesheet">
   <link href="includes/css/navbar-fixed-top.css" rel="stylesheet">
   <style> body { background-image: <?php echo 'url("includes/img/'.strtolower($css).'.png");'; ?> } 
   .content {background: <?php if($css == 'Light') { echo 'rgba(200,200,200,0.5)'; } elseif($css == 'Dark') { echo 'rgba(0,0,0,0.5)'; } else { echo 'rgba(200,200,200,0.5)'; }?>;margin-bottom:25px;} .page-header {margin-top: 21px; margin-bottom: 10.5px;} </style>
</head>