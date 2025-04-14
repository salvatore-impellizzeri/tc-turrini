<?php	
	foreach($fieldNames as $field => $label) {
		if(!empty($data[$field])) {
            ?>
            <div class="row">
                <b><?php echo $label ?></b>
                <span><?php echo $data[$field]; ?></span>
            </div>
            <?php
		}
	}
?>