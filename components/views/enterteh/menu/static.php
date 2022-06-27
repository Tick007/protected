<div class="staticleft">

<?php
if(isset($rubrics) AND empty($rubrics)==false) {
	for($k=0; $k<count($rubrics); $k++) {
		$models = $rubrics[$k]->pages;
		?>
<div class="part">
    <span><?php echo trim($rubrics[$k]->name)?></span>
    <ul class="staticmenu">

    <?php
    for($i=0; $i<count($models); $i++) {?>
	<li <?php
    if($models[$i]->id == $this->model->id) echo 'class="active"';
	?>>
    <?php
    echo CHtml::link($models[$i]->title, array('page/byalias', 'id'=>trim(strtolower($models[$i]->alais))));
	?>
    </li>
	<?php
    }
	?>
     </ul></div>
    <?php
	}//////for($k=0; $k<count($rubrics); $k++) {
	?>
	
	<?php
	}////////if(isset($rubrics) AND empty($rubrics)==false) {
	?>
    

</div>