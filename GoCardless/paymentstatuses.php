<?php
/* For reference user payment statuses
 * 1 - No membership form, therefore unpaid
 * 2 - Already paid (manual payment)
 * 3 - GoCardless - single payment (Pending)
 * 4 - GoCardless - single payment (Debited)
 * 5 - GoCardless - single payment (Failed)
 * 6 - GoCardless - single payment (Cancelled/Chargeback)
 * 7 - GoCardless - single payment (Refunded)
 * 8 - GoCardless - subscription (Created - not yet taken payments)
 * 9 - GoCardless - subscription (Completed)
 * 10 - GoCardless - subscription (Cancelled)
 * 11 - GoCardless - subscription (Failing)
 */