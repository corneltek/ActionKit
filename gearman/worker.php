<?php
echo "Starting\n";

# Create our worker object.
$gmworker= new GearmanWorker();

# Add default server (localhost).
$gmworker->addServer();

# Register function "reverse" with the server. Change the worker function to
# "reverse_fn_fast" for a faster worker with no output.
$gmworker->addFunction("reverse", "reverse_fn");

print "Waiting for job...\n";
while($gmworker->work())
{
    if ($gmworker->returnCode() != GEARMAN_SUCCESS)
    {
        echo "return_code: " . $gmworker->returnCode() . "\n";
        break;
    }
}

function reverse_fn($job)
{
    echo "Received job: " . $job->handle() . "\n";

    $workload = $job->workload();
    $workload_size = $job->workloadSize();

    echo "Workload: $workload ($workload_size)\n";

    # This status loop is not needed, just showing how it works
    for ($x= 0; $x < $workload_size; $x++)
    {
        echo "Sending status: " . ($x + 1) . "/$workload_size complete\n";
        $job->sendStatus($x, $workload_size);
        sleep(1);
    }

    $result= strrev($workload);
    echo "Result: $result\n";

    # Return what we want to send back to the client.
    return $result;
}

# A much simpler and less verbose version of the above function would be:
function reverse_fn_fast($job)
{
    return strrev($job->workload());
}
