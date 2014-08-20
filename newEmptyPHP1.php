
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

<?php get_header(); ?>
 
  <div id="primary" class="site-content">
    <div id="content" role="main">
 
      <?php while ( have_posts() ) : the_post(); ?>
 
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
 
            <header class="entry-header">
              <h1 class="entry-title"><?php the_title(); ?></h1>
            </header>
 
            <div class="entry-content">
              <?php the_content(); ?>
 
              <p>FORM CODE GOES HERE</p>
 
            </div><!-- .entry-content -->
 
          </article><!-- #post -->
 
      <?php endwhile; // end of the loop. ?>
 
    </div><!-- #content -->
  </div><!-- #primary -->
 
<?php get_sidebar(); ?>
<?php get_footer(); ?>