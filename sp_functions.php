<?php // Single Platform functions for Park Pizza

$location = 'park-pizza--brewing-company';

$main_menus = [
	"25326251" => "Pre-Pizza",
	"25326252" => "Not Pizza",
	"25326253" => "Pizza",
  "25326254" => "Your Pizza",
  "25326256" => "Post Pizza",
  "25326255" => "Toppings",
  "25326257" => "Kids",
  "25461124" => "Refreshments"
];

$hide_section = [
  "25461126", // => "Beer Flight",
  "25461125", // => "Beer On Tap",
  "25461115", // => "Not Beer",
  "25461121", // => "Also Not Beer"
  "25461117", // => "Sparkling",
  "25461118", // => "White",
  "25461119", // => "Red",
  "25461120", // => "Wine On Tap",
  "25461116", // => "Tavistock Reserve Collection",
  "25461122", // => "Kegged Sangria",
  "25461123" // => "Cocktails" // => "Toppings"
];

$show_next_section = [
    // "5407267", //Brunch 
];

$hide_nav = [
    
];

//don't add to section nav, and always show section
$no_side_nav = [
    
];

$section_header = [
    // "section_id goes here" lunch
    
];

$reset_indent = [
	// "section_id goes here"
];

$wine_book = [
    // "1964628" 
];

function get_sp_menu(){
    global $location;
    return get_sp_data($location,true);
}

function get_sp_location_info(){
    global $location;
    return get_sp_data($location,false);
}

function get_sp_data($location, $menus = true){

    $client_id = '';
    $client_secret = '';

    if($menus){
        $base_url = '/locations/'.$location.'/menus/?client='. $client_id; //for menu data
    }else{
        $base_url = '/locations/'.$location.'/?client='. $client_id; //for store info
    }

    $url = hash_hmac('sha1',$base_url, $client_secret, true);
    $str = urlencode(base64_encode($url));
    $fnl_url = 'http://publishing-api.singleplatform.com' . $base_url . '&signature=' .$str;
    $ch = curl_init($fnl_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec( $ch );
    curl_close($ch);

    $result = json_decode($response);

	if(!empty($result)){
	    if($result->code == '404'){
	        return false;
	    }else{
	        return  $result->data;
	    }
	}
}

function default_menu($id){ //for menu nav and menu itself
    global $default_menu;
    if($id == $default_menu){//Dinner
        return "active";
    }
	return false;
}

function price_format($price){
	// Add trailing 0 if price has only 1 decimal place
	if(preg_match("/\.\d$/", $price)){
		return $price . "0";
	}
	return $price;
}

function gather_prices($choices, $is_addons=false){

    $prices = '';
	$line_break = '';

    if(!empty($choices)){

        foreach($choices as $index => $choice){

			$prices .= "<div class='price_wrap'>";

            if($choice->name || $choice->unit){

                if($is_addons){
                    $choice_name = $choice->name ? "Add: ".$choice->name : "";
                }else{
                    $choice_name = $choice->name ?: "";
                }
                
                $unit_txt = $choice->unit ?: "";

				$prices .= ($index > 0) ? $line_break : "";

                if($choice->prices->min){
                    $prices .= '<span class="price_num"><span class="dollar">$</span>' . price_format($choice->prices->min) . '</span>';
                }

                $prices .= '<span class="price_txt">' . $choice_name . '</span>';
                $prices .= '<span class="price_txt">' . $unit_txt . '</span>';

            }elseif($choice->prices->min){
				$prices .= ($index > 0) ? $line_break : ""; //if we're not using the br, add the pipe
                $prices .= '<span class="price_num"><span class="dollar">$</span>' . price_format($choice->prices->min) . '</span>';
            }

			$prices .= "</div>";
        }
    }


    return $prices;
}

function prices_have_names($choices){

	if(!empty($choices)){
        foreach($choices as $choice){
            if($choice->name){
				return true;
            }
        }
    }

	return false;
}



function get_nav_items($sections){

	global $hide_section, $no_nav_hex;

	$nav_items = [];
	$count = 0;

	foreach ($sections as $section) {
		if(!in_array($section->id, $hide_section) && !in_array($section->id, $no_nav_hex)){
			$nav_items[$section->id] = $section->name;
			$count++;
		}
		if($count == 12){ //12 items in nav max
			break;
		}
	}

	return $nav_items;

}

// ===================== functions for formatting hours of operation ijd 10/11/16 =====================

$weekday_names = array(
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
    "Sunday",
);

function weekdaysort($a, $b){

    $weekdays = array(
        "Monday" => 0,
        "Tuesday" => 1,
        "Wednesday" => 2,
        "Thursday" => 3,
        "Friday" => 4,
        "Saturday" => 5,
        "Sunday" => 6
    );

    $aday = $weekdays[$a];
    $bday = $weekdays[$b];

    if ($aday == $bday) {
        return 0;
    }

    return ($aday > $bday) ? 1 : -1;
}

// remove leading 0s
function clean_hours($str){

    $result = $str;

    if($str[0] == '0'){
        $result = substr($str,1);
    }
    return $result;
}

//get full range of hours
function start_end_hrs($the_hours){
    $last_index = count($the_hours) - 1;
    return clean_hours($the_hours[0]->opening) . " - " . clean_hours($the_hours[$last_index]->closing);
}


function hours_text_block($hours){
    global $weekday_names;

    $wkdays = []; //weekday hours only
    $special = []; //other hours

    //filter out any non-weekday custom hours
    foreach($hours as $day => $stuff){
        if(in_array($day, $weekday_names)){
            $wkdays[$day] = $stuff;
        }else{
            $special[$day] = $stuff;
        }
    }

    //sort the days (sp has them out of order)
    uksort($wkdays, "weekdaysort");

    $hour_groupings = [];
    $group_index = 0;

    foreach($wkdays as $day => $info){

        if(!empty($oldhrs)){
            $newhrs = start_end_hrs($info);
            if($newhrs == $oldhrs){
                $hour_groupings[$group_index][$day] = $newhrs;
            }else{
                $group_index++; //start a new group
                $hour_groupings[$group_index][$day] = $newhrs;
            }
            $oldhrs = $newhrs;
        }else{
            $oldhrs = start_end_hrs($info);
            $hour_groupings[$group_index][$day] = $oldhrs;
        }
    }

    $strHours = '';

    foreach($hour_groupings as $index => $group){

        $days = '';
        if(count($group) == 1){
            $days .= key($group);
        }else{
            $days .= key($group); //get first key (day)
            $days .= ' - ';
            end($group); //move to last key (day)
            $days .= key($group);
        }

        $strHours .= "<p class='hours'>"
            . "<strong>" . $days . "</strong>". "<br>"
            . array_shift($group)
        . "</p>";
    }

    foreach($special as $special_hours){
        foreach($special_hours as $entry){
            $strHours .= $entry;
        }
    }

    return $strHours;
} //hours_text_block


function create_id($name){

    $result = preg_replace("/ /", "_", $name);
	$result = "sp_" . strtolower(preg_replace("/[^A-Za-z0-9_]/", "", $result));

    return $result;
}


function menu_nav_rename($menu_name){
    $search = [
        "Kids",
        "Kegged Sangria",
        "Tavistock Reserve Collection"
    ];
    $replace = [
        "Kids Menu",
        "Also Not Beer",
        "Not Beer"
    ];

    $result = str_ireplace($search, $replace, $menu_name);

    return $result;
}
?>