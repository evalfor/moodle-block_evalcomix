<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
require_once('../../../config.php');
require_login();
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