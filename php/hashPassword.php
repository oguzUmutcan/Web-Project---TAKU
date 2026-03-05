<?php

$salt = "KSPKKOSROKGRENGOVL6197856";

$cryptedPassword = hash('sha512', "azad123456" . $salt);

echo $cryptedPassword;