<?php
global $payment_statuses;

$payment_statuses = array(
    0   =>  "Unpaid",
    1   =>  "Already paid/manual payment",
    2   =>  "Pending (Single payment)",
    3   =>  "Failed (Single payment)",
    4   =>  "Paid (Single payment)",
    5   =>  "Cancelled (Single payment)",
    7   =>  "Subscription created, awaiting payments",
    8   =>  "Subscription created, payments successful",
    9   =>  "Subscription cancelled",
    10  =>  "Payments returned",
    11  =>  "Subscription ended"
);
