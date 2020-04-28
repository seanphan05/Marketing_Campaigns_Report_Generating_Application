<?php
ini_set('max_execution_time', 60);
$command = escapeshellcmd('python "path\to\ga_mc_to_sql.py"');
$output = exec($command, $output);
if ($output === 'success') { ?>
    <p><strong><?php echo 'Data has been refreshed. Reload your page to get the updated report!'; ?></strong></p>
<?php } else { ?>
    <p><strong><?php echo  'The "'.$output.'" error has been occurred during Python code execution. Please check again!'; ?></strong></p>
<?php } ?>
