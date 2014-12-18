<?php
include_once('../../../config.php');
$courseid = 4;
$context = get_context_instance(CONTEXT_COURSE, $courseid);

echo '
<html>
<head></head>
<body>
<script type="text/javascript">
						function url(u, nombre){
							win2 = window.open(u, nombre, "menubar=0,location=0,scrollbars,resizable,width=780,height=500");
							checkChild();
						}

						function checkChild() {
							if (win2.closed) {
								window.location.reload(true);
							}
							else setTimeout("checkChild()",1);
						}

					</script>
<a href=# onclick="javascript:url(\'details.php?cid='.$context->id.'&itemid=14&userid=3&popup=2\');">enlace</a>
</body></html>';
?>