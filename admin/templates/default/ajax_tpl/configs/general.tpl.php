<?php
/**
 * @Author: Cavinlz
 * @Date: May 5, 2015
 *
 */
?>
<hr></br>
<?php 

if(is_object($this -> form))
    $this -> form -> setSideWidth(3,4,'md') -> render();
                             
?>
<script>
$(".webconfig").bootstrapSwitch();
</script>