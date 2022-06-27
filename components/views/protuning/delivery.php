<!-- Put this script tag to the <head> of your page -->
<script type="text/javascript" src="//vk.com/js/api/openapi.js?95"></script>

<script type="text/javascript">
  VK.init({apiId: 3673895 , onlyWidgets: true});
</script>

<!-- Put this div tag to the place, where the Comments block will be -->
<div id="vk_comments"></div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments", {limit: 15, width: "<?php echo $width?>", attach: "*"});
</script><br><br>