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
                List of Delinquent Tenants
            </div>
            <div class="title">
                As of <?php echo $as_of_date; ?>
            </div>
        </div>
        
        <table class="report_table">
            <thead>
                <tr>
                    <th width="25%">TENANT'S NAME</th>
                    <th width="20%">OWNER/CONTACT PERSON</th>
                    <th width="30%">PAYABLES</th>
                    <th width="20%">AMOUNT DUE <?php echo $as_of_date; ?></th>
                    <th width="50%">REMARKS</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tenants as $data): $total_payable = 0; ?>
                    <tr>
                        <td><?php echo $data['trade_name']; ?></td>
                        <td><?php echo $data['contact_person']; ?></td>
                        <td>
                            <?php 
                                foreach ($delinquent as $value) 
                                {
                                    if ($value['tenant_id'] == $data['tenant_id']) 
                                    {    
                                        $type = 'Basic';
                                        if ($value['gl_account'] != 'Rent Receivables') 
                                        {
                                            $type = 'Other';
                                        }
                                        echo $type . " - " . $value['due_date'] . ' - ' . number_format($value['total'], 2) . "<br>";

                                        $total_payable += $value['total'];
                                    }
                                }

                             ?>
                        </td>
                        <td align="right"><?php echo number_format($total_payable, 2) ?></td>
                        <td></td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

    </body>
</html>
