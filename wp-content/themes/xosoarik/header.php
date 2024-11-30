<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xosoarik
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css" integrity="sha512-q3eWabyZPc1XTCmF+8/LuE1ozpg5xxn7iO89yfSOd5/oKvyqLngoNGsx8jq92Y8eXJ/IRxQbEC+FGSYxtk2oiw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="sidebar" class="sidebar">
		<div id="sidebarTop" class="sidebar-top justify-content-between align-items-center">
			<span>Kết quả xổ số</span>
			<button onclick="closeSidebar()" class="btn btn-outline-light"><i class="fas fa-times"></i></button>
		</div>
		<div class="sidebar-content">
			<?php echo do_shortcode('[sidebar_widget_region]'); ?>
			<?php echo do_shortcode('[sidebar_widget_province]'); ?>
		</div>
	</div>
	<div id="page" class="site">
		<header id="masthead" class="site-header  navbar-light bg-light">
			<nav class="header-main py-2">
				<div class="container">
					<div class="d-flex justify-content-between w-100">
						<?php
						echo the_custom_logo();
						?>
						<div class="d-flex  justify-content-end align-items-center">
							<span class="d-md-block d-none">Hôm nay ngày <?php echo date('d-m-Y'); ?></span>
							<button class="d-md-none d-block btn btn-outline-danger" onclick="openSidebar()"><i class="fas fa-bars"></i></button>

						</div>
					</div>
				</div>

			</nav>
			<div class="header-bottom">
				<div class="container">
					<div class="d-flex justify-content-between align-items-center">
						<nav id="site-navigation" class="header-menu main-navigation">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-1',
									'menu_id'        => 'primary-menu',
								)
							);
							?>
						</nav>
					</div>
				</div>
			</div>
		</header><!-- #masthead -->