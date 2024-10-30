<html>
<head>
<title><?php the_title(); ?></title>
</head>
<body>
<?php while (have_posts()) : the_post(); ?>
<h1><?php the_title(); ?></h1>
<?php the_content(); ?>
<?php endwhile; ?>
</body>
</html>