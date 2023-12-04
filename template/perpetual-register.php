<?php 
$complete_list = sort_perpetual_register();
if ( $complete_list ) {
?>
    <section class="prs_prstabs">
        <div class="prstab">
        <button class="prstablinks" onclick="openCity(event, 'move_to_A')" id="prsdefaultOpen">A</button>
            <?php foreach(range('B','Z') as $v){ ?>
                <button class="prstablinks" onclick="openCity(event, 'move_to_<?php echo $v ?>')" id="prsdefaultOpen"><?php echo $v ?></button>
            <?php } ?>
        </div>
        <?php 
        foreach(range('A','Z') as $v){ ?>
            <div id="move_to_<?php echo $v ?>" class="prstabcontent">
                <?php
                if( ! isset( $complete_list[ $v ] )  ){
                    echo '<h4>No names found</h4>';
                }else{
                    foreach( $complete_list[ $v ] as $sub_key => $list ) { ?>
                        <h4>
                            <?php echo $sub_key; ?>
                        </h4>
                        <?php foreach( $list as $post_detail ) { ?>
                            <h5><?php echo $post_detail[ 'title' ]; ?></h5>
                            <h6><?php echo $post_detail['lifestat']; ?></h6>
                        <?php } ?>
                        <?php
                    }
                } ?>
            </div>  
        <?php } ?>
    </section>
    <script>
        function openCity(evt, cityName) {
            var i, prstabcontent, prstablinks;
            prstabcontent = document.getElementsByClassName("prstabcontent");
            for (i = 0; i < prstabcontent.length; i++) {
                prstabcontent[i].style.display = "none";
            }
            prstablinks = document.getElementsByClassName("prstablinks");
            for (i = 0; i < prstablinks.length; i++) {
                prstablinks[i].className = prstablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }
        document.getElementById("prsdefaultOpen").click();
    </script>
    <style>
        .prstabcontent{
            column-count: 3;
        }
        .prstablinks{
            margin : 5px;
            padding : 10px 15px;
        }
    </style>
<?php } ?>
