<?php

    // calling the header.php
    get_header();

    // action hook for placing content above #container
    thematic_abovecontainer();

?>

	<div id="container">
		<div id="content">

			<?php 
            
            // create the navigation above the content
            thematic_navigation_above();
			
            // calling the widget area 'index-top'
            get_sidebar('index-top');

            // action hook for placing content above the index loop
            thematic_above_indexloop();

            // action hook creating the index loop
            thematic_indexloop();
						
						?> <div id="post-0" class="post-content errormsg span-12">
						
						<p><strong>Sorry, page was not found.</strong><br/><br/>Please click back or use the search bar above to find what you are looking for.</p>
			
						</div><!-- .post --> <?php

            // action hook for placing content below the index loop
            thematic_below_indexloop();

            // calling the widget area 'index-bottom'
            get_sidebar('index-bottom');

            // create the navigation below the content
            thematic_navigation_below();
            
				?>

		</div><!-- #content -->
	</div><!-- #container -->

<?php 

    // action hook for placing content below #container
    thematic_belowcontainer();

    // calling the standard sidebar 
    thematic_sidebar();
    
    // calling footer.php
    get_footer();

?>