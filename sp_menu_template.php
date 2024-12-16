<?php
/**
 Template Name: SP Menu
 *
 * @package _s
 */
get_header();

require_once "sp_functions.php";

$menus = get_sp_menu();

if($menus){
    // dynamically add new menus and create a reference array of current menus (see below)
    $current_menus = [];
    foreach($menus as $index => $menu){
        $current_menus[] = $menu->id; //for the loop below
        if(!key_exists($menu->id, $main_menus)){ //if there's a new menu, add it here
            $main_menus[$menu->id] = $menu->name;
        }
    }

    //check for deleted menus among basic set, remove from list if not in sp
    foreach ($main_menus as $key => $value) {
        if(!in_array($key, $current_menus)){
            unset($main_menus[$key]);
        }
    }

    $query_menu = [];
    foreach($menus as $menu){
        $query_menu[preg_replace("/sp_/","",create_id($menu->name))] = $menu->id;
    }
}

if(isset($_GET['menu']) && $_GET['menu'] != '' && key_exists($_GET['menu'],$query_menu)){
    $default_menu = $query_menu[$_GET['menu']];
}else{
    $default_menu = "";//lunch
}
?>

<!-- Removed template code begins here -->

<div class="content-sidebar-wrap">

    <main class="content">

        <section class="subpage-container">
        
            <div class="wrap">

                <article <?php echo post_class(); ?>>

                    <div class="entry-content">

                            <?php foreach( $menus as $menu ) : ?>
                            
                                <div class="menu-items">

                                    <?php foreach($menu->sections as $index => $section): ?>
                                    
                                        <?php if(!in_array($section->id, $hide_section)): ?>

                                            <div id="<?php echo create_id($section->id) ?>" class="sp_section">

                                                <div class="sp_section_header accordion_<?php echo $section->id; ?>" id="<?php echo str_replace(array("-"," "), array("_", "_"), strtolower($section->name) ); ?>">
                                                    <div class="flex-title">
                                                        <h3 class="section-title"><?php echo menu_nav_rename( $section->name ); ?> <i class="fas fa-arrow-down"></i></h3>
                                                    </div>
                                                </div><!-- end div sp_section_header -->
                                                <div class="flex-items" id="accordion_<?php echo $section->id; ?>">
                                                    <div class="flex-inner">

                                                        <?php if( !empty( $section->description ) ) : ?>
                                                            <p class="description"><?php echo $section->description; ?></p>
                                                        <?php endif; ?>
                                                        <?php foreach( $section->items as $item ) : ?>

                                                            <div class="sp_section_items">
                                                                <h4><?php echo $item->name; ?></h4>
                                                                <?php if( !empty( $item->description ) ) : ?>
                                                                    <p><?php echo $item->description; ?></p>
                                                                <?php endif; ?>
                                                                <?php if( !empty( $item->choices ) ) : ?>
                                                                    <?php echo gather_prices($item->choices) ?>
                                                                <?php endif; ?>

                                                                <?php if($item->additions): ?>
                                                                    <?php echo gather_prices($item->additions, true) ?>
                                                                <?php endif; ?>
                                                            </div>

                                                        <?php endforeach; // end foreach $section->items as $item ?>


                                                    </div><!-- end div flex-inner -->                                                    
                                                </div><!-- end div flex-items -->
                                                    
                                            </div><!-- end div sp_section -->
                                        <?php endif; // if(!in_array($section->id, $hide_section)) ?>
                                    <?php endforeach; // foreach($menu->sections as $index => $section)  ?>
                                </div><!-- end div menu-items -->
                            <?php endforeach; // foreach( $menus as $menu ) ?>
                            
                            <?php
                                // Merge the sparkling, white, red and wine on tap menu's into one $merged array object
                                /*
                                $merged = array_merge(
                                    $menus[0]->sections[8]->items,
                                    $menus[0]->sections[9]->items,
                                    $menus[0]->sections[10]->items,
                                    $menus[0]->sections[11]->items
                                );
                                */
                            ?>
                            <?php /*  -- 10/30/19 Beer removed from SinglePlatform GW *-
                            <div class="menu-items">
                                <div id="sp_23562566" class="sp_section">
                                    <div class="sp_section_header accordion_23562566" id="not_beer">
                                        <div class="flex-title">
                                            <h3 class="section-title">Not Beer</h3>
                                        </div><!-- end div flex-title -->                                    
                                    </div><!-- end div sp_section_header accordion_23562566 -->
                                    <div class="flex-items" id="accordion_23562566">
                                    <h3 class="arbitrary-class">Tavistock Reserve Collection</h3>
                                    <p>We hope that you enjoy these wines that we have carefully selected for your pleasure</p>
                                        <div class="flex-inner">
                                        <?php for( $i = 0; $i <= count( $merged ); $i++ ) : ?>
                                            <?php foreach( $merged as $merge ) : ?>
                                                <div class="sp_section_items" id="title_<?php echo $i++; ?>">
                                                    <p><?php echo $merge->name; ?></p>
                                                    <?php if( $merge->description ) : ?>
                                                        <p class="description"><?php echo $merge->description; ?></p>
                                                    <?php endif; ?>
                                                    <?php if( $merge->choices ) : ?>
                                                        <?php echo gather_prices($merge->choices); ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; // End foreach $merged as $merge ?>
                                            <?php endfor; ?>
                                        </div><!-- end div flex-inner -->
                                    </div><!-- end div flex-items -->
                                </div><!-- end div sp_23562566 -->
                            </div><!-- end div menu-items --> ?>

                            <?php 
                                // Merge the Kegged Sangria and Cocktails menu sections into one array onject
                                // This will populate the Also Not Beer section which doesn't actually exist
                                $ksc = array_merge(
                                    $menus[0]->sections[13]->items,
                                    $menus[0]->sections[14]->items
                                );
                            ?> 
                           
                            <div class="menu-items">
                                <div id="sp_05256251" class="sp_section">
                                    <div class="sp_section_header accordion_05256251" id="also_not_beer">
                                        <div class="flex-title">
                                            <h3 class="section-title">Also Not Beer</h3>
                                        </div><!-- end div flex-title -->                                    
                                    </div><!-- end div sp_section_header accordion_23562566 -->

                                    <div class="flex-items" id="accordion_05256251">
                                        <div class="column-wrapper">
                                        <div class="column">
                                            <h4>Kegged Sangria</h4>
                                            <?php foreach( $menus[0]->sections[13]->items as $item ) : ?>
                                                <div class="sp_section_items" id="kegged_sangria">
                                                    <h6><?php echo $item->name; ?></h6>
                                                    <?php if( $item->description ) : ?>
                                                        <p class="description"><?php echo $item->description; ?></p>
                                                    <?php endif; ?>
                                                    <?php if( $item->choices ) : ?>
                                                        <?php echo gather_prices($item->choices); ?>
                                                    <?php endif; ?>
                                                </div><!-- end div sp_section_items -->
                                            <?php endforeach; ?>
                                        </div><!-- end div column -->

                                        <div class="column">
                                            <h4>Cocktails</h4>
                                            <?php foreach( $menus[0]->sections[14]->items as $mitem ) : ?>
                                                <div class="sp_section_items" id="cocktails">
                                                    <h6><?php echo $mitem->name; ?></h6>
                                                    <?php if( $mitem->description ) : ?>
                                                        <p class="description"><?php echo $mitem->description; ?></p>
                                                    <?php endif; ?>
                                                    <?php if( $mitem->choices ) : ?>
                                                        <?php echo gather_prices($mitem->choices); ?>
                                                    <?php endif; ?>
                                                </div><!-- end div sp_section_items -->
                                            <?php endforeach; ?>
                                        </div><!-- end div column -->
                                        </div><!-- end div column-wrapper -->
                                    </div><!-- end div flex-items -->

                                </div><!-- end div sp_05256251 -->
                            </div><!-- end div menu-items --> */ ?>

                    </div><!-- end div entry-content -->

                </article>
            </div><!-- end div wrap -->

        </section><!-- end section subpage-container -->

    </main><!-- end main class content -->

</div><!-- end div content-sidebar-wrap -->

<!-- Removed template code ends here -->
<?php get_footer(); ?>