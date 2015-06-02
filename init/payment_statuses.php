<?php
global $payment_statuses;

$payment_statuses = array(
    0   =>  array("None", 'none'),
    2   =>  array("Pending", 'pendingSingle'),
    3   =>  array("Failed", 'failedSingle'),
    4   =>  array("Paid in full", 'paidSingle'),
    5   =>  array("Cancelled", 'cancelledSingle'),
    7   =>  array("Created, awaiting payments", 'subCreatedAwaiting'),
    8   =>  array("Active", 'subActive'),
    9   =>  array("Cancelled", 'subCancelled'),
    10  =>  array("Payments returned", 'payReturned'),
    11  =>  array("Subscription ended", 'subEnded')
);
