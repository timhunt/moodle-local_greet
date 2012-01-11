<?php
$capabilities = array('local/greet:begreeted' => array(
    'captype' => 'read',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => array('guest' => CAP_ALLOW, 'user' => CAP_ALLOW)
));
