<?php
add_action('init', 'getXigoloGames', 1);
function getXigoloGames() {
    if (isset($_GET['sync']) && ($_GET['sync']=='xigoloGames')) {
       $gamesObj = getResponseCurl('https://api-xana.angelium.net/api/game_admin/games-data/');
       update_option('xigoloGames', $gamesObj);
    }
}

// [xigoloGames]
add_shortcode('xigoloGames', 'showXigoloGames');
function showXigoloGames($atts) {
   extract(shortcode_atts(array(
      "category" => 'default',
   ), $atts));

   if (is_page('xigolo-lp') && !is_admin()) {
      ob_start();
      $gamesObj = get_option('xigoloGames');
      if ($gamesObj) {
        ?>
        <div class="carousel-wrap">
        <div id="slider" class="owl-carousel owl-carousel<?php echo $category; ?>">
        <?php
         if($gamesObj && $gamesObj->status) {
            $data = $gamesObj->data;
            if ($data) {
               foreach($data as $dataKey => $dataValue) {
                    if (($dataKey == $category) && $dataValue) {
                        foreach($dataValue as $item) {
                          $itemName = $item->name;
                          $itemImage = $item->image;
                          $itemType = $item->type;
                          $itemProvider = $item->provider;
                          if ($itemProvider == 'evolution') {
                            $gameUrl = "https://www.xigolo.com/xigolo/#/xigolo/evolution?type=$itemType&provider=$itemProvider&name=$itemName";
                          } else {
                            $gameUrl = "https://www.xigolo.com/xigolo/#/xigolo/?type=$itemType&provider=$itemProvider&name=$itemName";
                          }
                          ?>
                          <!-- Carousel HTML -->
                            <div class="item">
                                <a target="_blank" href="<?php echo $gameUrl; ?>" class="item-link"></a>
                                <div class="item-image" style="background-image: url('<?php echo $item->image; ?>'); "></div>
                                <h4 class="eael-tm-name"><?php echo $item->name; ?> </h4>
                                <div class="eael-tm-description">
                                    <?php echo $item->provider; ?>        
                                </div>
                            </div>
                          <?php
                       }
                   }
               }
            }
         }
         ?>
        </div>
        </div>
         <script type="text/javascript">
            jQuery('.owl-carousel<?php echo $category; ?>').owlCarousel({
                    loop: true,
                    margin: 10,
                    responsiveClass:true,
                    items: 1,
                    autoplay: true,
                    slideSpeed : 2000,
                    autoplayTimeout: 1000,
                    autoWidth:true,
                    autoplayHoverPause:false,
                    items:7,
                    responsive: {
                        0: {
                          items: 3
                        },
                        768: {
                          items: 5
                        },
                        1000: {
                          items: 7
                        }
                    }
            });
        </script>
       
         <?php
      }
        return ob_get_clean();
    } else {
        return '';
    }
}

/* 
* cURL GET reponse from URL function | Site - allprograms.tech
*/
function getResponseCurl($url, $returnTransfer = true) {
   $result = '';
   if ($url) {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL,$url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, $returnTransfer);
      $response = curl_exec($ch);
      curl_close($ch);
      $response = json_decode($response);
     
   }
   return $response;
}