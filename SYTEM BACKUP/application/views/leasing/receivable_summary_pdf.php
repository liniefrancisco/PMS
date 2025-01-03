<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>
        <span class="store_name"><?php echo $store_name; ?></span>
        <br>
        <div width="100%">
            <div class="title">
                Leasing Department
            </div>
            <div class="title">
                Receivable Summary
            </div>
            <div class="title">
                As of <?php echo $as_of_date; ?>
            </div>
        </div>
        <table class="report_table">
            <thead>
                <tr>
                    <th width="25%">TENANT'S NAME</th>
                    <th width="25%">OWNER/CONTACT PERSON</th>
                    <th width="15%">Rent Receivable</th>
                    <th width="15%">Other Charges</th>
                    <th width="20%">Total Amount Due</th>
                </tr>
            </thead>
            <tbody>
                <?php $total_recivable = 0;  foreach ($reports as $value): ?>
                    <?php 
                        $total = 0;
                        $rr_total = 0;
                        $ar_total = 0;
                        foreach ($rr_summary as $rr) 
                        {
                            if ($rr['tenant_id'] == $value['tenant_id']) 
                            {
                                $total += $rr['total'];
                                $rr_total += $rr['total'];
                            }
                        }

                        foreach ($ar_summary as $ar) 
                        {
                            if ($ar['tenant_id'] == $value['tenant_id']) 
                            {
                                $total += $ar['total'];
                                $ar_total += $ar['total'];
                            }
                        }
                    ;?>

                    <?php if ($total > 1): ?>
                        <tr>
                            <td><?php echo $value['trade_name']; ?></td>
                            <td><?php echo $value['contact_person']; ?></td>
                            <td><?php echo number_format($rr_total, 2) ?></td>
                            <td><?php echo number_format($ar_total, 2) ?></td>
                            <td align = "right"><b><?php echo number_format($total, 2); $total_recivable += $total;?></b></td>
                        </tr>
                    <?php endif ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <div class="title">Total Receivable: <?php echo number_format($total_recivable, 2); ?></div>
    </body>
</html>
