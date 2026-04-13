<?php

echo "PHP SNMP Test Script\n";
echo "--------------------\n";

// --- CONFIGURE THIS ---
$printerIp = '192.168.45.60'; // <--- IMPORTANT: Change this to a REAL printer IP on your network
$community = 'public';        // <--- Change this if your printer uses a different community string
// --------------------

// Check if the snmp2_get function exists.
if (!function_exists('snmp2_get')) {
    die("FATAL ERROR: The snmp2_get() function does not exist. The PHP SNMP extension is NOT correctly loaded for the command line.\n");
}

echo "SUCCESS: The snmp2_get() function exists.\n";
echo "Attempting to connect to printer at: $printerIp\n";

// Use error suppression (@) because this function throws warnings on failure, which we will handle.
// The OID '1.3.6.1.2.1.1.1.0' asks for the printer's system description. It's a standard test.
$sysDesc = @snmp2_get($printerIp, $community, '1.3.6.1.2.1.1.1.0', 1000000, 2);

if ($sysDesc === false) {
    echo "RESULT: FAILED.\n";
    echo "Could not get a response from the printer. This could be because:\n";
    echo "1. The IP address '$printerIp' is incorrect.\n";
    echo "2. The printer is offline or does not have SNMP enabled.\n";
    echo "3. The community string '$community' is wrong.\n";
    echo "4. A firewall is blocking the connection.\n";
} else {
    echo "RESULT: SUCCESS!\n";
    echo "Received response from printer: " . $sysDesc . "\n";
    echo "This confirms your PHP SNMP extension is working correctly!\n";
}
