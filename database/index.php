<?php
http_response_code(403);
header('Content-Type: text/plain');
exit('Forbidden: Direct access to database directory is restricted.');
