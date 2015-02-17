<?php


function initialise_cron()
{
    // Initialise variables
    $feeds = dirname( __FILE__ ).'feeds.php';
    $nag_emails = dirname( __FILE__ ).'nag_emails.php';
    
    // Remove any CRON entries pointing at this directory
    shell_exec('crontab -l > oldcron');
    shell_exec('cat oldcron | grep -v "'.dirname( __FILE__ ).'" > newcron');
    shell_exec('crontab newcron');
    shell_exec('rm oldcron');
    shell_exec('rm newcron');
    
    // Add 'feeds' entry to crontab 
    shell_exec('crontab -l > feedscron');
    shell_exec('echo "*/5 * * * *" '.$feeds.' >> feedscron');
    shell_exec('crontab feedscron');
    shell_exec('rm feedscron');
    
    // Add 'nag' entry to crontab 
    shell_exec('crontab -l > nagcron');
    shell_exec('echo "0 19 * * *" '.$feeds.' >> nagcron');
    shell_exec('crontab nagcron');
    shell_exec('rm nagcron');
}

function clear_cron()
{
    // Remove any CRON entries pointing at this directory
    shell_exec('crontab -l > oldcron');
    shell_exec('cat oldcron | grep -v "'.dirname( __FILE__ ).'" > newcron');
    shell_exec('crontab newcron');
    shell_exec('rm oldcron');
    shell_exec('rm newcron');
}
    