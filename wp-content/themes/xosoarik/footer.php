<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package xosoarik
 */

?>

<footer id="footer" class="site-footer">
	<div class="container footer-main">
		<?php
		if (is_active_sidebar('footer_widget_location')) {
			dynamic_sidebar('footer_widget_location');
		}
		?>
	</div>
</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
	function openSidebar() {
		document.getElementById("sidebar").style.width = "100%";
		document.getElementById("sidebarTop").style.display = "flex"; // Thay đổi chiều rộng để hiển thị sidebar
	}

	function closeSidebar() {
		document.getElementById("sidebar").style.width = "0";
		document.getElementById("sidebarTop").style.display = "none";
	}

</script>
</body>

</html>