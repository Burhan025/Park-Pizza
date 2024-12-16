<?php
/*
Template Name: Brewery Single Platform
*/
get_header();
require_once "sp_functions.php";
$menus = get_sp_menu();
?>
<?php if( have_posts() ) : while( have_posts() ) : the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; wp_reset_postdata(); endif; ?>
<div class="entry-content">
    <div class="menu-items">
        <div id="sp_23562566" class="sp_section">
            <div class="sp_section_header accordion_25461125" id="beer_on_tap">
                <div class="flex-title">
                    <h3 class="section-title">Beer On Tap <i class="fas fa-arrow-down"></i></h3>
                </div><!-- end div flex-title -->                                    
            </div><!-- end div sp_section_header accordion_23562566 -->
            <div class="flex-items" id="accordion_25461125" style="display:block !important;">
                <div class="flex-inner">
                <?php for( $i = 0; $i <= count( $menus[0]->sections[0]->items ); $i++ ) : ?>
                    <?php foreach( $menus[0]->sections[0]->items as $beer_on_tap ) : ?>
                        <div class="sp_section_items" id="bat_<?php echo $i++; ?>">
                            <h4><?php echo $beer_on_tap->name; ?></h4>
                            <?php if( $beer_on_tap->description ) : ?>
                                <p class="description"><?php echo $beer_on_tap->description; ?></p>
                            <?php endif; ?>
                            <?php if( $beer_on_tap->choices ) : ?>
                                <?php echo gather_prices( $beer_on_tap->choices ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; // End foreach $merged as $merge ?>
                    <?php endfor; ?>
                </div><!-- end div flex-inner -->
            </div><!-- end div flex-items -->
        </div><!-- end div sp_23562566 -->
    </div><!-- end div menu-items -->

    <div class="menu-items" style="display:none;">
        <div id="sp_25461126" class="sp_section">
            <div class="sp_section_header accordion_25461126" id="beer_flight">
                <div class="flex-title">
                    <h3 class="section-title">Collaborations <i class="fas fa-arrow-down"></i></h3>
                </div><!-- end div flex-title -->                                    
            </div><!-- end div sp_section_header accordion_23562566 -->
			<!--<div class="flex-items" id="accordion_23562566">-->
            <div class="flex-items" id="accordion_25461126">
                <div class="flex-inner">
                <?php for( $i = 0; $i <= count( $menus[1]->sections[1]->items ); $i++ ) : ?>
                    <?php foreach( $menus[1]->sections[1]->items as $beer_flight ) : ?>
                        <div class="sp_section_items" id="bf_<?php echo $i++; ?>">
                            <h4><?php echo $beer_flight->name; ?></h4>
                            <?php if( $beer_flight->description ) : ?>
                                <p class="description"><?php echo $beer_flight->description; ?></p>
                            <?php endif; ?>
                            <?php if( $beer_flight->choices ) : ?>
                                <?php echo gather_prices( $beer_flight->choices ); ?>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; // End foreach $merged as $merge ?>
                    <?php endfor; ?>
                </div><!-- end div flex-inner -->
            </div><!-- end div flex-items -->
        </div><!-- end div sp_23562566 -->
    </div><!-- end div menu-items -->

</div><!-- end div entry-content -->
<?php get_footer(); ?>