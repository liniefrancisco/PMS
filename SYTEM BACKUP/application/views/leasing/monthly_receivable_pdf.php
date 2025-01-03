<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>
    </head>
    <body>

        <?php
            $counter = 0; $total_basic = 0; $total_percentage = 0; $total_discount = 0; $total_total = 0; $total_vat = 0; $total_subtotal = 0; $total_wht = 0; $total_net = 0;
            $last_part = false; $total_cusa = 0; $total_aircon = 0; $total_chilledWater = 0; $total_electricity = 0; $total_water = 0; $total_gas = 0; $total_pestControl = 0;
            $total_bioAug = 0; $total_serviceReq = 0; $total_overtime = 0; $total_IDcharges = 0; $total_faxCharges = 0; $total_fixedAssetRental = 0; $total_penalty = 0; $total_motorcadeCharges = 0;
            $total_securityCharges = 0; $total_plywoodEnclosure = 0; $total_pvcLock = 0; $total_exhaustDuct = 0; $total_trainingRoomCharges = 0; $total_storageRoomCharges = 0;
            $total_neonLights = 0; $total_rollup = 0; $total_addbox = 0; $total_unathorizedClosure = 0; $total_houseViolation = 0;
            $total_notaryFee = 0; $total_lateSubmission = 0; $total_bannerBoard = 0; $total_ledwall = 0; $total_forwardedBalance = 0; $total_others = 0; $total_adjustmentVAT = 0; $total_leakDetector = 0;
            $total_glassService = 0; $total_coupon = 0; $total_expandedTax = 0; $grand_total = 0;
        ?>

        <?php for ($i=0; $i < count($reports) / 8; $i++) { ?>
        <?php $index = $counter * 8; $tenant0 = 0; $tenant1 = 0; $tenant2 = 0; $tenant3 = 0; $tenant4 = 0; $tenant5 = 0; $tenant6 = 0; $tenant7 = 0; if ($i+1 >= count($reports) / 8) {$last_part = TRUE;}?>

        <span class="store_name"><?php echo $store_name; ?></span>
        <br>
        <div width="100%">
            <div class="title" style="float: left;">
                Monthly Receivable - <?php echo $tenancy_type; ?>(<?php echo $inclusive_date; ?>)
            </div>
            <div class="title" style="float: right">
                Run Date: <?php echo date('Y-m-d');?>, Run Time <?php echo date('Y-m-d h:i:s:A'); ?>
            </div>
        </div>
        <table class="report_table" style="font-size:10px">
            <thead>
                <tr>
                    <th></th>
                    <?php if ($index + 0 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 0 < count($reports)){echo $reports[$index + 0]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 1 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 1 < count($reports)){echo $reports[$index + 1]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 2 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 2 < count($reports)){echo $reports[$index + 2]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 3 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 3 < count($reports)){echo $reports[$index + 3]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 4 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 4 < count($reports)){echo $reports[$index + 4]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 5 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 5 < count($reports)){echo $reports[$index + 5]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 6 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 6 < count($reports)){echo $reports[$index + 6]['trade_name'];} ?></th>
                    <?php endif ?>
                     <?php if ($index + 7 < count($reports)): ?>
                        <th width="30%"><?php if ($index + 7 < count($reports)){echo $reports[$index + 7]['trade_name'];} ?></th>
                    <?php endif ?>
                    <!-- <th width="30%"><?php if ($index + 1 < count($reports)){echo $reports[$index + 1]['trade_name'];} ?></th>
                    <th width="30%"><?php if ($index + 2 < count($reports)){echo $reports[$index + 2]['trade_name'];} ?></th>
                    <th width="30%"><?php if ($index + 3 < count($reports)){echo $reports[$index + 3]['trade_name'];} ?></th>
                    <th width="30%"><?php if ($index + 4 < count($reports)){echo $reports[$index + 4]['trade_name'];} ?></th>
                    <th width="30%"><?php if ($index + 5 < count($reports)){echo $reports[$index + 5]['trade_name'];} ?></th>
                    <th width="30%"><?php if ($index + 6 < count($reports)){echo $reports[$index + 6]['trade_name'];} ?></th>
                    <th width="30%"><?php if ($index + 7 < count($reports)){echo $reports[$index + 7]['trade_name'];} ?></th> -->
                    <?php if ($last_part): ?>
                    <th width="50%">Grand Total</th>
                    <?php endif ?>
                </tr>
            </thead>
            <tbody >
                <tr>
                    <td>Basic Rent</td>
                    <?php if ($index + 0 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 0]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 0]['basic_rent'], 2); $total_basic += $reports[$index + 0]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 0]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 0]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 0]['basic_rental'], 2); $total_basic += $reports[$index + 0]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 0]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 0]['basic_rental'] >= $reports[$index + 0]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 0]['basic_rent'], 2); $total_basic += $reports[$index + 0]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 1 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 1]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 1]['basic_rent'], 2); $total_basic += $reports[$index + 1]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 1]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 1]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 1]['basic_rental'], 2); $total_basic += $reports[$index + 1]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 1]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 1]['basic_rental'] >= $reports[$index + 1]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 1]['basic_rent'], 2); $total_basic += $reports[$index + 1]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>


                    <?php if ($index + 2 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 2]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 2]['basic_rent'], 2); $total_basic += $reports[$index + 2]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 2]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 2]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 2]['basic_rental'], 2); $total_basic += $reports[$index + 2]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 2]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 2]['basic_rental'] >= $reports[$index + 2]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 2]['basic_rent'], 2); $total_basic += $reports[$index + 2]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 3 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 3]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 3]['basic_rent'], 2); $total_basic += $reports[$index + 3]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 3]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 3]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 3]['basic_rental'], 2); $total_basic += $reports[$index + 3]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 3]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 3]['basic_rental'] >= $reports[$index + 3]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 3]['basic_rent'], 2); $total_basic += $reports[$index + 3]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 4 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 4]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 4]['basic_rent'], 2); $total_basic += $reports[$index + 4]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 4]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 4]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 4]['basic_rental'], 2); $total_basic += $reports[$index + 4]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 4]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 4]['basic_rental'] >= $reports[$index + 4]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 4]['basic_rent'], 2); $total_basic += $reports[$index + 4]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 5 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 5]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 5]['basic_rent'], 2); $total_basic += $reports[$index + 5]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 5]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 5]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 5]['basic_rental'], 2); $total_basic += $reports[$index + 5]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 5]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 5]['basic_rental'] >= $reports[$index + 5]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 5]['basic_rent'], 2); $total_basic += $reports[$index + 5]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 6 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 6]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 6]['basic_rent'], 2); $total_basic += $reports[$index + 6]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 6]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 6]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 6]['basic_rental'], 2); $total_basic += $reports[$index + 6]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 6]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 6]['basic_rental'] >= $reports[$index + 6]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 6]['basic_rent'], 2); $total_basic += $reports[$index + 6]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 7 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 7]['rental_type'] == 'Fixed'): ?>
                            <?php echo number_format($reports[$index + 7]['basic_rent'], 2); $total_basic += $reports[$index + 7]['basic_rent'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 7]['rental_type'] == 'Fixed Plus Percentage' && $reports[$index + 7]['basic_rent'] > 0): ?>
                            <?php echo number_format($reports[$index + 7]['basic_rental'], 2); $total_basic += $reports[$index + 7]['basic_rental'] ;?>
                        <?php endif ?>

                        <?php if ($reports[$index + 7]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 7]['basic_rental'] >= $reports[$index + 7]['basic_rent']): ?>
                                <?php echo number_format($reports[$index + 7]['basic_rent'], 2); $total_basic += $reports[$index + 7]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                     <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_basic, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Percentage Rent</td>
                    <?php if ($index + 0 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 0]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 0]['basic_rent'] > 0 && $reports[$index + 0]['basic_rent'] > $reports[$index + 0]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 0]['basic_rent'], 2); $total_percentage +=  $reports[$index + 0]['basic_rent'];?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 0]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 0]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 0]['basic_rent'] - $reports[$index + 0]['basic_rental'], 2); $total_percentage +=  number_format($reports[$index + 0]['basic_rent'] - $reports[$index + 0]['basic_rental'], 2)?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                     <?php endif ?>

                    <?php if ($index + 1 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 1]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 1]['basic_rent'] > 0 && $reports[$index + 1]['basic_rent'] > $reports[$index + 1]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 1]['basic_rent'], 2); $total_percentage +=  $reports[$index + 1]['basic_rent']?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 1]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 1]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 1]['basic_rent'] - $reports[$index + 1]['basic_rental'], 2); $total_percentage +=  number_format($reports[$index + 1]['basic_rent'] - $reports[$index + 1]['basic_rental'], 2) ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 2 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 2]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 2]['basic_rent'] > 0 && $reports[$index + 2]['basic_rent'] > $reports[$index + 2]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 2]['basic_rent'], 2); $total_percentage += $reports[$index + 2]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 2]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 2]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 2]['basic_rent'] - $reports[$index + 2]['basic_rental'], 2);  $total_percentage += number_format($reports[$index + 2]['basic_rent'] - $reports[$index + 2]['basic_rental'], 2) ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 3 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 3]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 3]['basic_rent'] > 0 && $reports[$index + 3]['basic_rent'] > $reports[$index + 3]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 3]['basic_rent'], 2); $total_percentage += $reports[$index + 3]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>


                        <?php if ($reports[$index + 3]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 3]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 3]['basic_rent'] - $reports[$index + 3]['basic_rental'], 2); $total_percentage += number_format($reports[$index + 3]['basic_rent'] - $reports[$index + 3]['basic_rental'], 2) ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 4 < count($reports)): ?>
                    <td align="right">

                        <?php if ($reports[$index + 4]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 4]['basic_rent'] > 0 && $reports[$index + 4]['basic_rent'] > $reports[$index + 4]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 4]['basic_rent'], 2); $total_percentage += $reports[$index + 4]['basic_rent'] ;?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 4]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 4]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 4]['basic_rent'] - $reports[$index + 4]['basic_rental'], 2); $total_percentage += number_format($reports[$index + 4]['basic_rent'] - $reports[$index + 4]['basic_rental'], 2) ;?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 5 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 5]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 5]['basic_rent'] > 0 && $reports[$index + 5]['basic_rent'] > $reports[$index + 5]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 5]['basic_rent'], 2); $total_percentage +=  $reports[$index + 5]['basic_rent'];?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 5]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 5]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 5]['basic_rent'] - $reports[$index + 5]['basic_rental'], 2); $total_percentage += number_format($reports[$index + 5]['basic_rent'] - $reports[$index + 5]['basic_rental'], 2);?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>


                    <?php if ($index + 6 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 6]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 6]['basic_rent'] > 0 && $reports[$index + 6]['basic_rent'] > $reports[$index + 6]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 6]['basic_rent'], 2); $total_percentage +=  $reports[$index + 6]['basic_rent'];?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 6]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 6]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 6]['basic_rent'] - $reports[$index + 6]['basic_rental'], 2); $total_percentage +=  number_format($reports[$index + 6]['basic_rent'] - $reports[$index + 6]['basic_rental'], 2);?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>

                    <?php if ($index + 7 < count($reports)): ?>
                    <td align="right">
                        <?php if ($reports[$index + 7]['rental_type'] == 'Fixed/Percentage w/c Higher'): ?>
                            <?php if ($reports[$index + 7]['basic_rent'] > 0 && $reports[$index + 7]['basic_rent'] > $reports[$index + 7]['basic_rental']): ?>
                                <?php echo number_format($reports[$index + 7]['basic_rent'], 2); $total_percentage +=  $reports[$index + 7]['basic_rent'];?>
                            <?php endif ?>
                        <?php endif ?>
                        <?php if ($reports[$index + 7]['rental_type'] == 'Fixed Plus Percentage'): ?>
                            <?php if ($reports[$index + 7]['basic_rent'] > 0): ?>
                                <?php echo number_format($reports[$index + 7]['basic_rent'] - $reports[$index + 7]['basic_rental'], 2); $total_percentage +=  number_format($reports[$index + 7]['basic_rent'] - $reports[$index + 7]['basic_rental'], 2);?>
                            <?php endif ?>
                        <?php endif ?>
                    </td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_percentage, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Discount</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['discount'], 2); $total_discount += $reports[$index + 0]['discount']; ?></td>
                    <?php endif ?>

                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['discount'], 2); $total_discount += $reports[$index + 1]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['discount'], 2); $total_discount += $reports[$index + 2]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['discount'], 2); $total_discount += $reports[$index + 3]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['discount'], 2); $total_discount += $reports[$index + 4]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['discount'], 2); $total_discount += $reports[$index + 5]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['discount'], 2); $total_discount += $reports[$index + 6]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['discount'], 2); $total_discount += $reports[$index + 7]['discount']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_discount, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Total</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['basic_rent'], 2); $total_total += $reports[$index + 0]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['basic_rent'], 2); $total_total += $reports[$index + 1]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['basic_rent'], 2); $total_total += $reports[$index + 2]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['basic_rent'], 2); $total_total += $reports[$index + 3]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['basic_rent'], 2); $total_total += $reports[$index + 4]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['basic_rent'], 2); $total_total += $reports[$index + 5]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['basic_rent'], 2); $total_total += $reports[$index + 6]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['basic_rent'], 2); $total_total += $reports[$index + 7]['basic_rent']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_total, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Add: Vat</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['vat'], 2); $total_vat += $reports[$index + 0]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['vat'], 2); $total_vat += $reports[$index + 1]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['vat'], 2); $total_vat += $reports[$index + 2]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['vat'], 2); $total_vat += $reports[$index + 3]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['vat'], 2); $total_vat += $reports[$index + 4]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['vat'], 2); $total_vat += $reports[$index + 5]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['vat'], 2); $total_vat += $reports[$index + 6]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['vat'], 2); $total_vat += $reports[$index + 7]['vat']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_vat, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Subtotal</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['basic_rent'] + $reports[$index + 0]['vat'], 2); $total_subtotal += $reports[$index + 0]['basic_rent'] + $reports[$index + 0]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['basic_rent'] + $reports[$index + 1]['vat'], 2); $total_subtotal += $reports[$index + 1]['basic_rent'] + $reports[$index + 1]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['basic_rent'] + $reports[$index + 2]['vat'], 2); $total_subtotal += $reports[$index + 2]['basic_rent'] + $reports[$index + 2]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['basic_rent'] + $reports[$index + 3]['vat'], 2); $total_subtotal += $reports[$index + 3]['basic_rent'] + $reports[$index + 3]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['basic_rent'] + $reports[$index + 4]['vat'], 2); $total_subtotal += $reports[$index + 4]['basic_rent'] + $reports[$index + 4]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['basic_rent'] + $reports[$index + 5]['vat'], 2); $total_subtotal += $reports[$index + 5]['basic_rent'] + $reports[$index + 5]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['basic_rent'] + $reports[$index + 6]['vat'], 2); $total_subtotal += $reports[$index + 6]['basic_rent'] + $reports[$index + 6]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['basic_rent'] + $reports[$index + 7]['vat'], 2); $total_subtotal += $reports[$index + 7]['basic_rent'] + $reports[$index + 7]['vat'];?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_subtotal, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Less Tax Withheld</td>
                    <?php if ($index + 0 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 0]['wht'] * -1, 2); $total_wht +=  $reports[$index + 0]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 1]['wht'] * -1, 2); $total_wht +=  $reports[$index + 1]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 2]['wht'] * -1, 2); $total_wht +=  $reports[$index + 2]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 3]['wht'] * -1, 2); $total_wht +=  $reports[$index + 3]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 4]['wht'] * -1, 2); $total_wht +=  $reports[$index + 4]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 5]['wht'] * -1, 2); $total_wht +=  $reports[$index + 5]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 6]['wht'] * -1, 2); $total_wht +=  $reports[$index + 6]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                         <td align="right"><?php echo number_format($reports[$index + 7]['wht'] * -1, 2); $total_wht +=  $reports[$index + 7]['wht'];?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_wht * -1, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td><b>NET RENTAL DUE AFTER TAX</b></td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 0]['net_rental'], 2); $tenant0 += $reports[$index + 0]['net_rental']; $total_net += $reports[$index + 0]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 1]['net_rental'], 2); $tenant1 += $reports[$index + 1]['net_rental']; $total_net += $reports[$index + 1]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 2]['net_rental'], 2); $tenant2 += $reports[$index + 2]['net_rental']; $total_net += $reports[$index + 2]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 3]['net_rental'], 2); $tenant3 += $reports[$index + 3]['net_rental']; $total_net += $reports[$index + 3]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 4]['net_rental'], 2); $tenant4 += $reports[$index + 4]['net_rental']; $total_net += $reports[$index + 4]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 5]['net_rental'], 2); $tenant5 += $reports[$index + 5]['net_rental']; $total_net += $reports[$index + 5]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 6]['net_rental'], 2); $tenant6 += $reports[$index + 6]['net_rental']; $total_net += $reports[$index + 6]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($reports[$index + 7]['net_rental'], 2); $tenant7 += $reports[$index + 7]['net_rental']; $total_net += $reports[$index + 7]['net_rental']; ?></b></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><b><?php echo number_format($total_net, 2); ?></b></td>
                    <?php endif ?>
                </tr>
                <tr >
                    <td>Add: Other Current Charges</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                        <td align="right"></td>
                    <?php endif ?>
                </tr>
                <tr >
                    <td>CUSA</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['cusa'], 2); $tenant0 += $reports[$index + 0]['cusa']; $total_cusa += $reports[$index + 0]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['cusa'], 2); $tenant1 += $reports[$index + 1]['cusa']; $total_cusa += $reports[$index + 1]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['cusa'], 2); $tenant2 += $reports[$index + 2]['cusa']; $total_cusa += $reports[$index + 2]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['cusa'], 2); $tenant3 += $reports[$index + 3]['cusa']; $total_cusa += $reports[$index + 3]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['cusa'], 2); $tenant4 += $reports[$index + 4]['cusa']; $total_cusa += $reports[$index + 4]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['cusa'], 2); $tenant5 += $reports[$index + 5]['cusa']; $total_cusa += $reports[$index + 5]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['cusa'], 2); $tenant6 += $reports[$index + 6]['cusa']; $total_cusa += $reports[$index + 6]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['cusa'], 2); $tenant7 += $reports[$index + 7]['cusa']; $total_cusa += $reports[$index + 7]['cusa']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_cusa, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr >
                    <td>Aircon</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['aircon'], 2); $tenant0 += $reports[$index + 0]['aircon']; $total_aircon += $reports[$index + 0]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['aircon'], 2); $tenant1 += $reports[$index + 1]['aircon']; $total_aircon += $reports[$index + 1]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['aircon'], 2); $tenant2 += $reports[$index + 2]['aircon']; $total_aircon += $reports[$index + 2]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['aircon'], 2); $tenant3 += $reports[$index + 3]['aircon']; $total_aircon += $reports[$index + 3]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['aircon'], 2); $tenant4 += $reports[$index + 4]['aircon']; $total_aircon += $reports[$index + 4]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['aircon'], 2); $tenant5 += $reports[$index + 5]['aircon']; $total_aircon += $reports[$index + 5]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['aircon'], 2); $tenant6 += $reports[$index + 6]['aircon']; $total_aircon += $reports[$index + 6]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['aircon'], 2); $tenant7 += $reports[$index + 7]['aircon']; $total_aircon += $reports[$index + 7]['aircon']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_aircon, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr >
                    <td>Chilled Water</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['chilled_water'], 2); $tenant0 += $reports[$index + 0]['chilled_water']; $total_chilledWater += $reports[$index + 0]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['chilled_water'], 2); $tenant1 += $reports[$index + 1]['chilled_water']; $total_chilledWater += $reports[$index + 1]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['chilled_water'], 2); $tenant2 += $reports[$index + 2]['chilled_water']; $total_chilledWater += $reports[$index + 2]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['chilled_water'], 2); $tenant3 += $reports[$index + 3]['chilled_water']; $total_chilledWater += $reports[$index + 3]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['chilled_water'], 2); $tenant4 += $reports[$index + 4]['chilled_water']; $total_chilledWater += $reports[$index + 4]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['chilled_water'], 2); $tenant5 += $reports[$index + 5]['chilled_water']; $total_chilledWater += $reports[$index + 5]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['chilled_water'], 2); $tenant6 += $reports[$index + 6]['chilled_water']; $total_chilledWater += $reports[$index + 6]['chilled_water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['chilled_water'], 2); $tenant7 += $reports[$index + 7]['chilled_water']; $total_chilledWater += $reports[$index + 7]['chilled_water']; ?></td>
                    <?php endif ?>

                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_chilledWater, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Electricity</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['electricity'], 2); $tenant0 += $reports[$index + 0]['electricity']; $total_electricity += $reports[$index + 0]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['electricity'], 2); $tenant1 += $reports[$index + 1]['electricity']; $total_electricity += $reports[$index + 1]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['electricity'], 2); $tenant2 += $reports[$index + 2]['electricity']; $total_electricity += $reports[$index + 2]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['electricity'], 2); $tenant3 += $reports[$index + 3]['electricity']; $total_electricity += $reports[$index + 3]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['electricity'], 2); $tenant4 += $reports[$index + 4]['electricity']; $total_electricity += $reports[$index + 4]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['electricity'], 2); $tenant5 += $reports[$index + 5]['electricity']; $total_electricity += $reports[$index + 5]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['electricity'], 2); $tenant6 += $reports[$index + 6]['electricity']; $total_electricity += $reports[$index + 6]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['electricity'], 2); $tenant7 += $reports[$index + 7]['electricity']; $total_electricity += $reports[$index + 7]['electricity']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_electricity, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Water</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['water'], 2); $tenant0 += $reports[$index + 0]['water']; $total_water += $reports[$index + 0]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['water'], 2); $tenant1 += $reports[$index + 1]['water']; $total_water += $reports[$index + 1]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['water'], 2); $tenant2 += $reports[$index + 2]['water']; $total_water += $reports[$index + 2]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['water'], 2); $tenant3 += $reports[$index + 3]['water']; $total_water += $reports[$index + 3]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['water'], 2); $tenant4 += $reports[$index + 4]['water']; $total_water += $reports[$index + 4]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['water'], 2); $tenant5 += $reports[$index + 5]['water']; $total_water += $reports[$index + 5]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['water'], 2); $tenant6 += $reports[$index + 6]['water']; $total_water += $reports[$index + 6]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['water'], 2); $tenant7 += $reports[$index + 7]['water']; $total_water += $reports[$index + 7]['water']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_water, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Gas</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['gas'], 2); $tenant0 += $reports[$index + 0]['gas']; $total_gas += $reports[$index + 0]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['gas'], 2); $tenant1 += $reports[$index + 1]['gas']; $total_gas += $reports[$index + 1]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['gas'], 2); $tenant2 += $reports[$index + 2]['gas']; $total_gas += $reports[$index + 2]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['gas'], 2); $tenant3 += $reports[$index + 3]['gas']; $total_gas += $reports[$index + 3]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['gas'], 2); $tenant4 += $reports[$index + 4]['gas']; $total_gas += $reports[$index + 4]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['gas'], 2); $tenant5 += $reports[$index + 5]['gas']; $total_gas += $reports[$index + 5]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['gas'], 2); $tenant6 += $reports[$index + 6]['gas']; $total_gas += $reports[$index + 6]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['gas'], 2); $tenant7 += $reports[$index + 7]['gas']; $total_gas += $reports[$index + 7]['gas']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_gas, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Pest Control</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['pest_control'], 2); $tenant0 += $reports[$index + 0]['pest_control']; $total_pestControl += $reports[$index + 0]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['pest_control'], 2); $tenant1 += $reports[$index + 1]['pest_control']; $total_pestControl += $reports[$index + 1]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['pest_control'], 2); $tenant2 += $reports[$index + 2]['pest_control']; $total_pestControl += $reports[$index + 2]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['pest_control'], 2); $tenant3 += $reports[$index + 3]['pest_control']; $total_pestControl += $reports[$index + 3]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['pest_control'], 2); $tenant4 += $reports[$index + 4]['pest_control']; $total_pestControl += $reports[$index + 4]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['pest_control'], 2); $tenant5 += $reports[$index + 5]['pest_control']; $total_pestControl += $reports[$index + 5]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['pest_control'], 2); $tenant6 += $reports[$index + 6]['pest_control']; $total_pestControl += $reports[$index + 6]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['pest_control'], 2); $tenant7 += $reports[$index + 7]['pest_control']; $total_pestControl += $reports[$index + 7]['pest_control']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_pestControl, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Bio Augmentation</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['bio_aug'], 2); $tenant0 += $reports[$index + 0]['bio_aug']; $total_bioAug += $reports[$index + 0]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['bio_aug'], 2); $tenant1 += $reports[$index + 1]['bio_aug']; $total_bioAug += $reports[$index + 1]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['bio_aug'], 2); $tenant2 += $reports[$index + 2]['bio_aug']; $total_bioAug += $reports[$index + 2]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['bio_aug'], 2); $tenant3 += $reports[$index + 3]['bio_aug']; $total_bioAug += $reports[$index + 3]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['bio_aug'], 2); $tenant4 += $reports[$index + 4]['bio_aug']; $total_bioAug += $reports[$index + 4]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['bio_aug'], 2); $tenant5 += $reports[$index + 5]['bio_aug']; $total_bioAug += $reports[$index + 5]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['bio_aug'], 2); $tenant6 += $reports[$index + 6]['bio_aug']; $total_bioAug += $reports[$index + 6]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['bio_aug'], 2); $tenant7 += $reports[$index + 7]['bio_aug']; $total_bioAug += $reports[$index + 7]['bio_aug']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_bioAug, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Service Request</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['service_request'], 2); $tenant0 += $reports[$index + 0]['service_request']; $total_serviceReq += $reports[$index + 0]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['service_request'], 2); $tenant1 += $reports[$index + 1]['service_request']; $total_serviceReq += $reports[$index + 1]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['service_request'], 2); $tenant2 += $reports[$index + 2]['service_request']; $total_serviceReq += $reports[$index + 2]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['service_request'], 2); $tenant3 += $reports[$index + 3]['service_request']; $total_serviceReq += $reports[$index + 3]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['service_request'], 2); $tenant4 += $reports[$index + 4]['service_request']; $total_serviceReq += $reports[$index + 4]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['service_request'], 2); $tenant5 += $reports[$index + 5]['service_request']; $total_serviceReq += $reports[$index + 5]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['service_request'], 2); $tenant6 += $reports[$index + 6]['service_request']; $total_serviceReq += $reports[$index + 6]['service_request']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['service_request'], 2); $tenant7 += $reports[$index + 7]['service_request']; $total_serviceReq += $reports[$index + 7]['service_request']; ?></td>
                    <?php endif ?>

                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_serviceReq, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Overtime Charges</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['overtime'], 2); $tenant0 += $reports[$index + 0]['overtime']; $total_overtime += $reports[$index + 0]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['overtime'], 2); $tenant1 += $reports[$index + 1]['overtime']; $total_overtime += $reports[$index + 1]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['overtime'], 2); $tenant2 += $reports[$index + 2]['overtime']; $total_overtime += $reports[$index + 2]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['overtime'], 2); $tenant3 += $reports[$index + 3]['overtime']; $total_overtime += $reports[$index + 3]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['overtime'], 2); $tenant4 += $reports[$index + 4]['overtime']; $total_overtime += $reports[$index + 4]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['overtime'], 2); $tenant5 += $reports[$index + 5]['overtime']; $total_overtime += $reports[$index + 5]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['overtime'], 2); $tenant6 += $reports[$index + 6]['overtime']; $total_overtime += $reports[$index + 6]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['overtime'], 2); $tenant7 += $reports[$index + 7]['overtime']; $total_overtime += $reports[$index + 7]['overtime']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_overtime, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>ID Charges</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['id_charges'], 2); $tenant0 += $reports[$index + 0]['id_charges']; $total_IDcharges += $reports[$index + 0]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['id_charges'], 2); $tenant1 += $reports[$index + 1]['id_charges']; $total_IDcharges += $reports[$index + 1]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['id_charges'], 2); $tenant2 += $reports[$index + 2]['id_charges']; $total_IDcharges += $reports[$index + 2]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['id_charges'], 2); $tenant3 += $reports[$index + 3]['id_charges']; $total_IDcharges += $reports[$index + 3]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['id_charges'], 2); $tenant4 += $reports[$index + 4]['id_charges']; $total_IDcharges += $reports[$index + 4]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['id_charges'], 2); $tenant5 += $reports[$index + 5]['id_charges']; $total_IDcharges += $reports[$index + 5]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['id_charges'], 2); $tenant6 += $reports[$index + 6]['id_charges']; $total_IDcharges += $reports[$index + 6]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['id_charges'], 2); $tenant7 += $reports[$index + 7]['id_charges']; $total_IDcharges += $reports[$index + 7]['id_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_IDcharges, 2); ?></td>
                    <?php endif ?>
                </tr>

                <tr>
                    <td>Fixed Asset Rental</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['fixed_asset'], 2); $tenant0 += $reports[$index + 0]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 0]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['fixed_asset'], 2); $tenant1 += $reports[$index + 1]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 1]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['fixed_asset'], 2); $tenant2 += $reports[$index + 2]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 2]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['fixed_asset'], 2); $tenant3 += $reports[$index + 3]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 3]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['fixed_asset'], 2); $tenant4 += $reports[$index + 4]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 4]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['fixed_asset'], 2); $tenant5 += $reports[$index + 5]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 5]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['fixed_asset'], 2); $tenant6 += $reports[$index + 6]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 6]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['fixed_asset'], 2); $tenant7 += $reports[$index + 7]['fixed_asset']; $total_fixedAssetRental += $reports[$index + 7]['fixed_asset']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_fixedAssetRental, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Penalty</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['penalty'], 2); $tenant0 += $reports[$index + 0]['penalty']; $total_penalty += $reports[$index + 0]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['penalty'], 2); $tenant1 += $reports[$index + 1]['penalty']; $total_penalty += $reports[$index + 1]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['penalty'], 2); $tenant2 += $reports[$index + 2]['penalty']; $total_penalty += $reports[$index + 2]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['penalty'], 2); $tenant3 += $reports[$index + 3]['penalty']; $total_penalty += $reports[$index + 3]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['penalty'], 2); $tenant4 += $reports[$index + 4]['penalty']; $total_penalty += $reports[$index + 4]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['penalty'], 2); $tenant5 += $reports[$index + 5]['penalty']; $total_penalty += $reports[$index + 5]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['penalty'], 2); $tenant6 += $reports[$index + 6]['penalty']; $total_penalty += $reports[$index + 6]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['penalty'], 2); $tenant7 += $reports[$index + 7]['penalty']; $total_penalty += $reports[$index + 7]['penalty']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_penalty, 2); ?></td>
                    <?php endif ?>
                </tr>

                <tr>
                    <td>Security Charges</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['sec_charges'], 2); $tenant0 += $reports[$index + 0]['sec_charges']; $total_securityCharges += $reports[$index + 0]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['sec_charges'], 2); $tenant1 += $reports[$index + 1]['sec_charges']; $total_securityCharges += $reports[$index + 1]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['sec_charges'], 2); $tenant2 += $reports[$index + 2]['sec_charges']; $total_securityCharges += $reports[$index + 2]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['sec_charges'], 2); $tenant3 += $reports[$index + 3]['sec_charges']; $total_securityCharges += $reports[$index + 3]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['sec_charges'], 2); $tenant4 += $reports[$index + 4]['sec_charges']; $total_securityCharges += $reports[$index + 4]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['sec_charges'], 2); $tenant5 += $reports[$index + 5]['sec_charges']; $total_securityCharges += $reports[$index + 5]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['sec_charges'], 2); $tenant6 += $reports[$index + 6]['sec_charges']; $total_securityCharges += $reports[$index + 6]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['sec_charges'], 2); $tenant7 += $reports[$index + 7]['sec_charges']; $total_securityCharges += $reports[$index + 7]['sec_charges']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_securityCharges, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Expanded Withholding Tax</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['expanded_tax'] * -1, 2); $tenant0 -= $reports[$index + 0]['expanded_tax']; $total_expandedTax += $reports[$index + 0]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['expanded_tax'] * -1, 2); $tenant1 -= $reports[$index + 1]['expanded_tax']; $total_expandedTax += $reports[$index + 1]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['expanded_tax'] * -1, 2); $tenant2 -= $reports[$index + 2]['expanded_tax']; $total_expandedTax += $reports[$index + 2]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['expanded_tax'] * -1, 2); $tenant3 -= $reports[$index + 3]['expanded_tax']; $total_expandedTax += $reports[$index + 3]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['expanded_tax'] * -1, 2); $tenant4 -= $reports[$index + 4]['expanded_tax']; $total_expandedTax += $reports[$index + 4]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['expanded_tax'] * -1, 2); $tenant5 -= $reports[$index + 5]['expanded_tax']; $total_expandedTax += $reports[$index + 5]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['expanded_tax'] * -1, 2); $tenant6 -= $reports[$index + 6]['expanded_tax']; $total_expandedTax += $reports[$index + 6]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['expanded_tax'] * -1, 2); $tenant7 -= $reports[$index + 7]['expanded_tax']; $total_expandedTax += $reports[$index + 7]['expanded_tax'];?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_expandedTax * -1, 2); ?></td>
                    <?php endif ?>
                </tr>

                <tr>
                    <td>Plywood Enclosure</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['plywood'], 2); $tenant0 += $reports[$index + 0]['plywood']; $total_plywoodEnclosure += $reports[$index + 0]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['plywood'], 2); $tenant1 += $reports[$index + 1]['plywood']; $total_plywoodEnclosure += $reports[$index + 1]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['plywood'], 2); $tenant2 += $reports[$index + 2]['plywood']; $total_plywoodEnclosure += $reports[$index + 2]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['plywood'], 2); $tenant3 += $reports[$index + 3]['plywood']; $total_plywoodEnclosure += $reports[$index + 3]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['plywood'], 2); $tenant4 += $reports[$index + 4]['plywood']; $total_plywoodEnclosure += $reports[$index + 4]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['plywood'], 2); $tenant5 += $reports[$index + 5]['plywood']; $total_plywoodEnclosure += $reports[$index + 5]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['plywood'], 2); $tenant6 += $reports[$index + 6]['plywood']; $total_plywoodEnclosure += $reports[$index + 6]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['plywood'], 2); $tenant7 += $reports[$index + 7]['plywood']; $total_plywoodEnclosure += $reports[$index + 7]['plywood']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_plywoodEnclosure, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>PVC Door & Lock</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['pvc'], 2); $tenant0 += $reports[$index + 0]['pvc']; $total_pvcLock += $reports[$index + 0]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['pvc'], 2); $tenant1 += $reports[$index + 1]['pvc']; $total_pvcLock += $reports[$index + 1]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['pvc'], 2); $tenant2 += $reports[$index + 2]['pvc']; $total_pvcLock += $reports[$index + 2]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['pvc'], 2); $tenant3 += $reports[$index + 3]['pvc']; $total_pvcLock += $reports[$index + 3]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['pvc'], 2); $tenant4 += $reports[$index + 4]['pvc']; $total_pvcLock += $reports[$index + 4]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['pvc'], 2); $tenant5 += $reports[$index + 5]['pvc']; $total_pvcLock += $reports[$index + 5]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['pvc'], 2); $tenant6 += $reports[$index + 6]['pvc']; $total_pvcLock += $reports[$index + 6]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['pvc'], 2); $tenant7 += $reports[$index + 7]['pvc']; $total_pvcLock += $reports[$index + 7]['pvc']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_pvcLock, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Exhaust Duct Cleaning Charges</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['exhaust'], 2); $tenant0 += $reports[$index + 0]['exhaust']; $total_exhaustDuct += $reports[$index + 0]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['exhaust'], 2); $tenant1 += $reports[$index + 1]['exhaust']; $total_exhaustDuct += $reports[$index + 1]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['exhaust'], 2); $tenant2 += $reports[$index + 2]['exhaust']; $total_exhaustDuct += $reports[$index + 2]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['exhaust'], 2); $tenant3 += $reports[$index + 3]['exhaust']; $total_exhaustDuct += $reports[$index + 3]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['exhaust'], 2); $tenant4 += $reports[$index + 4]['exhaust']; $total_exhaustDuct += $reports[$index + 4]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['exhaust'], 2); $tenant5 += $reports[$index + 5]['exhaust']; $total_exhaustDuct += $reports[$index + 5]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['exhaust'], 2); $tenant6 += $reports[$index + 6]['exhaust']; $total_exhaustDuct += $reports[$index + 6]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['exhaust'], 2); $tenant7 += $reports[$index + 7]['exhaust']; $total_exhaustDuct += $reports[$index + 7]['exhaust']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_exhaustDuct, 2); ?></td>
                    <?php endif ?>
                </tr>

                <tr>
                    <td>Storage Charges</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['storage_room'], 2); $tenant0 += $reports[$index + 0]['storage_room']; $total_storageRoomCharges += $reports[$index + 0]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['storage_room'], 2); $tenant1 += $reports[$index + 1]['storage_room']; $total_storageRoomCharges += $reports[$index + 1]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['storage_room'], 2); $tenant2 += $reports[$index + 2]['storage_room']; $total_storageRoomCharges += $reports[$index + 2]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['storage_room'], 2); $tenant3 += $reports[$index + 3]['storage_room']; $total_storageRoomCharges += $reports[$index + 3]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['storage_room'], 2); $tenant4 += $reports[$index + 4]['storage_room']; $total_storageRoomCharges += $reports[$index + 4]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['storage_room'], 2); $tenant5 += $reports[$index + 5]['storage_room']; $total_storageRoomCharges += $reports[$index + 5]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['storage_room'], 2); $tenant6 += $reports[$index + 6]['storage_room']; $total_storageRoomCharges += $reports[$index + 6]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['storage_room'], 2); $tenant7 += $reports[$index + 7]['storage_room']; $total_storageRoomCharges += $reports[$index + 7]['storage_room']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_storageRoomCharges, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>AdBox</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['addbox'], 2); $tenant0 += $reports[$index + 0]['addbox']; $total_addbox += $reports[$index + 0]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['addbox'], 2); $tenant1 += $reports[$index + 1]['addbox']; $total_addbox += $reports[$index + 1]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['addbox'], 2); $tenant2 += $reports[$index + 2]['addbox']; $total_addbox += $reports[$index + 2]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['addbox'], 2); $tenant3 += $reports[$index + 3]['addbox']; $total_addbox += $reports[$index + 3]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['addbox'], 2); $tenant4 += $reports[$index + 4]['addbox']; $total_addbox += $reports[$index + 4]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['addbox'], 2); $tenant5 += $reports[$index + 5]['addbox']; $total_addbox += $reports[$index + 5]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['addbox'], 2); $tenant6 += $reports[$index + 6]['addbox']; $total_addbox += $reports[$index + 6]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['addbox'], 2); $tenant7 += $reports[$index + 7]['addbox']; $total_addbox += $reports[$index + 7]['addbox']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_addbox, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Unathorized Closure</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['unatho_closure'], 2); $tenant0 += $reports[$index + 0]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 0]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['unatho_closure'], 2); $tenant1 += $reports[$index + 1]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 1]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['unatho_closure'], 2); $tenant2 += $reports[$index + 2]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 2]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['unatho_closure'], 2); $tenant3 += $reports[$index + 3]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 3]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['unatho_closure'], 2); $tenant4 += $reports[$index + 4]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 4]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['unatho_closure'], 2); $tenant5 += $reports[$index + 5]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 5]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['unatho_closure'], 2); $tenant6 += $reports[$index + 6]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 6]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['unatho_closure'], 2); $tenant7 += $reports[$index + 7]['unatho_closure']; $total_unathorizedClosure += $reports[$index + 7]['unatho_closure']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_unathorizedClosure, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Houserules Violation</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['house_rules'], 2); $tenant0 += $reports[$index + 0]['house_rules']; $total_houseViolation += $reports[$index + 0]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['house_rules'], 2); $tenant1 += $reports[$index + 1]['house_rules']; $total_houseViolation += $reports[$index + 1]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['house_rules'], 2); $tenant2 += $reports[$index + 2]['house_rules']; $total_houseViolation += $reports[$index + 2]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['house_rules'], 2); $tenant3 += $reports[$index + 3]['house_rules']; $total_houseViolation += $reports[$index + 3]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['house_rules'], 2); $tenant4 += $reports[$index + 4]['house_rules']; $total_houseViolation += $reports[$index + 4]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['house_rules'], 2); $tenant5 += $reports[$index + 5]['house_rules']; $total_houseViolation += $reports[$index + 5]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['house_rules'], 2); $tenant6 += $reports[$index + 6]['house_rules']; $total_houseViolation += $reports[$index + 6]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['house_rules'], 2); $tenant7 += $reports[$index + 7]['house_rules']; $total_houseViolation += $reports[$index + 7]['house_rules']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_houseViolation, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Notary Fee</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['notary_fee'], 2); $tenant0 += $reports[$index + 0]['notary_fee']; $total_notaryFee += $reports[$index + 0]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['notary_fee'], 2); $tenant1 += $reports[$index + 1]['notary_fee']; $total_notaryFee += $reports[$index + 1]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['notary_fee'], 2); $tenant2 += $reports[$index + 2]['notary_fee']; $total_notaryFee += $reports[$index + 2]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['notary_fee'], 2); $tenant3 += $reports[$index + 3]['notary_fee']; $total_notaryFee += $reports[$index + 3]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['notary_fee'], 2); $tenant4 += $reports[$index + 4]['notary_fee']; $total_notaryFee += $reports[$index + 4]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['notary_fee'], 2); $tenant5 += $reports[$index + 5]['notary_fee']; $total_notaryFee += $reports[$index + 5]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['notary_fee'], 2); $tenant6 += $reports[$index + 6]['notary_fee']; $total_notaryFee += $reports[$index + 6]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['notary_fee'], 2); $tenant7 += $reports[$index + 7]['notary_fee']; $total_notaryFee += $reports[$index + 7]['notary_fee']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_notaryFee, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Late submission of Deposit Slip</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['late_depositSlip'], 2); $tenant0 += $reports[$index + 0]['late_depositSlip']; $total_lateSubmission += $reports[$index + 0]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['late_depositSlip'], 2); $tenant1 += $reports[$index + 1]['late_depositSlip']; $total_lateSubmission += $reports[$index + 1]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['late_depositSlip'], 2); $tenant2 += $reports[$index + 2]['late_depositSlip']; $total_lateSubmission += $reports[$index + 2]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['late_depositSlip'], 2); $tenant3 += $reports[$index + 3]['late_depositSlip']; $total_lateSubmission += $reports[$index + 3]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['late_depositSlip'], 2); $tenant4 += $reports[$index + 4]['late_depositSlip']; $total_lateSubmission += $reports[$index + 4]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['late_depositSlip'], 2); $tenant5 += $reports[$index + 5]['late_depositSlip']; $total_lateSubmission += $reports[$index + 5]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['late_depositSlip'], 2); $tenant6 += $reports[$index + 6]['late_depositSlip']; $total_lateSubmission += $reports[$index + 6]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['late_depositSlip'], 2); $tenant7 += $reports[$index + 7]['late_depositSlip']; $total_lateSubmission += $reports[$index + 7]['late_depositSlip']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_lateSubmission, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Banner Board</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['banner_board'], 2); $tenant0 += $reports[$index + 0]['banner_board']; $total_bannerBoard += $reports[$index + 0]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['banner_board'], 2); $tenant1 += $reports[$index + 1]['banner_board']; $total_bannerBoard += $reports[$index + 1]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['banner_board'], 2); $tenant2 += $reports[$index + 2]['banner_board']; $total_bannerBoard += $reports[$index + 2]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['banner_board'], 2); $tenant3 += $reports[$index + 3]['banner_board']; $total_bannerBoard += $reports[$index + 3]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['banner_board'], 2); $tenant4 += $reports[$index + 4]['banner_board']; $total_bannerBoard += $reports[$index + 4]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['banner_board'], 2); $tenant5 += $reports[$index + 5]['banner_board']; $total_bannerBoard += $reports[$index + 5]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['banner_board'], 2); $tenant6 += $reports[$index + 6]['banner_board']; $total_bannerBoard += $reports[$index + 6]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['banner_board'], 2); $tenant7 += $reports[$index + 7]['banner_board']; $total_bannerBoard += $reports[$index + 7]['banner_board']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_bannerBoard, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>LED Wall</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['led_wall'], 2); $tenant0 += $reports[$index + 0]['led_wall']; $total_ledwall += $reports[$index + 0]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['led_wall'], 2); $tenant1 += $reports[$index + 1]['led_wall']; $total_ledwall += $reports[$index + 1]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['led_wall'], 2); $tenant2 += $reports[$index + 2]['led_wall']; $total_ledwall += $reports[$index + 2]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['led_wall'], 2); $tenant3 += $reports[$index + 3]['led_wall']; $total_ledwall += $reports[$index + 3]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['led_wall'], 2); $tenant4 += $reports[$index + 4]['led_wall']; $total_ledwall += $reports[$index + 4]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['led_wall'], 2); $tenant5 += $reports[$index + 5]['led_wall']; $total_ledwall += $reports[$index + 5]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['led_wall'], 2); $tenant6 += $reports[$index + 6]['led_wall']; $total_ledwall += $reports[$index + 6]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['led_wall'], 2); $tenant7 += $reports[$index + 7]['led_wall']; $total_ledwall += $reports[$index + 7]['led_wall']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_ledwall, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Forwarded Balance</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['forwarded_balance'], 2); $tenant0 += $reports[$index + 0]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 0]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['forwarded_balance'], 2); $tenant1 += $reports[$index + 1]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 1]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['forwarded_balance'], 2); $tenant2 += $reports[$index + 2]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 2]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['forwarded_balance'], 2); $tenant3 += $reports[$index + 3]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 3]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['forwarded_balance'], 2); $tenant4 += $reports[$index + 4]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 4]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['forwarded_balance'], 2); $tenant5 += $reports[$index + 5]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 5]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['forwarded_balance'], 2); $tenant6 += $reports[$index + 6]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 6]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['forwarded_balance'], 2); $tenant7 += $reports[$index + 7]['forwarded_balance']; $total_forwardedBalance += $reports[$index + 7]['forwarded_balance']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_forwardedBalance, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Others</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['others'], 2); $tenant0 += $reports[$index + 0]['others']; $total_others += $reports[$index + 0]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['others'], 2); $tenant1 += $reports[$index + 1]['others']; $total_others += $reports[$index + 1]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['others'], 2); $tenant2 += $reports[$index + 2]['others']; $total_others += $reports[$index + 2]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['others'], 2); $tenant3 += $reports[$index + 3]['others']; $total_others += $reports[$index + 3]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['others'], 2); $tenant4 += $reports[$index + 4]['others']; $total_others += $reports[$index + 4]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['others'], 2); $tenant5 += $reports[$index + 5]['others']; $total_others += $reports[$index + 5]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['others'], 2); $tenant6 += $reports[$index + 6]['others']; $total_others += $reports[$index + 6]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['others'], 2); $tenant7 += $reports[$index + 7]['others']; $total_others += $reports[$index + 7]['others']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_others, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Adjustment(VAT)</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['adjustment_VAT'], 2); $tenant0 += $reports[$index + 0]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 0]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['adjustment_VAT'], 2); $tenant1 += $reports[$index + 1]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 1]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['adjustment_VAT'], 2); $tenant2 += $reports[$index + 2]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 2]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['adjustment_VAT'], 2); $tenant3 += $reports[$index + 3]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 3]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['adjustment_VAT'], 2); $tenant4 += $reports[$index + 4]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 4]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['adjustment_VAT'], 2); $tenant5 += $reports[$index + 5]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 5]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['adjustment_VAT'], 2); $tenant6 += $reports[$index + 6]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 6]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['adjustment_VAT'], 2); $tenant7 += $reports[$index + 7]['adjustment_VAT']; $total_adjustmentVAT += $reports[$index + 7]['adjustment_VAT']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_adjustmentVAT, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Gas Leak Detector</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['leak_detector'], 2); $tenant0 += $reports[$index + 0]['leak_detector']; $total_leakDetector += $reports[$index + 0]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['leak_detector'], 2); $tenant1 += $reports[$index + 1]['leak_detector']; $total_leakDetector += $reports[$index + 1]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['leak_detector'], 2); $tenant2 += $reports[$index + 2]['leak_detector']; $total_leakDetector += $reports[$index + 2]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['leak_detector'], 2); $tenant3 += $reports[$index + 3]['leak_detector']; $total_leakDetector += $reports[$index + 3]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['leak_detector'], 2); $tenant4 += $reports[$index + 4]['leak_detector']; $total_leakDetector += $reports[$index + 4]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['leak_detector'], 2); $tenant5 += $reports[$index + 5]['leak_detector']; $total_leakDetector += $reports[$index + 5]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['leak_detector'], 2); $tenant6 += $reports[$index + 6]['leak_detector']; $total_leakDetector += $reports[$index + 6]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['leak_detector'], 2); $tenant7 += $reports[$index + 7]['leak_detector']; $total_leakDetector += $reports[$index + 7]['leak_detector']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_leakDetector, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Glass Service</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['glass_service'], 2); $tenant0 += $reports[$index + 0]['glass_service']; $total_glassService += $reports[$index + 0]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['glass_service'], 2); $tenant1 += $reports[$index + 1]['glass_service']; $total_glassService += $reports[$index + 1]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['glass_service'], 2); $tenant2 += $reports[$index + 2]['glass_service']; $total_glassService += $reports[$index + 2]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['glass_service'], 2); $tenant3 += $reports[$index + 3]['glass_service']; $total_glassService += $reports[$index + 3]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['glass_service'], 2); $tenant4 += $reports[$index + 4]['glass_service']; $total_glassService += $reports[$index + 4]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['glass_service'], 2); $tenant5 += $reports[$index + 5]['glass_service']; $total_glassService += $reports[$index + 5]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['glass_service'], 2); $tenant6 += $reports[$index + 6]['glass_service']; $total_glassService += $reports[$index + 6]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['glass_service'], 2); $tenant7 += $reports[$index + 7]['glass_service']; $total_glassService += $reports[$index + 7]['glass_service']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_glassService, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td>Coupon</td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 0]['coupon'], 2); $tenant0 += $reports[$index + 0]['coupon']; $total_coupon += $reports[$index + 0]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 1]['coupon'], 2); $tenant1 += $reports[$index + 1]['coupon']; $total_coupon += $reports[$index + 1]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 2]['coupon'], 2); $tenant2 += $reports[$index + 2]['coupon']; $total_coupon += $reports[$index + 2]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 3]['coupon'], 2); $tenant3 += $reports[$index + 3]['coupon']; $total_coupon += $reports[$index + 3]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 4]['coupon'], 2); $tenant4 += $reports[$index + 4]['coupon']; $total_coupon += $reports[$index + 4]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 5]['coupon'], 2); $tenant5 += $reports[$index + 5]['coupon']; $total_coupon += $reports[$index + 5]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 6]['coupon'], 2); $tenant6 += $reports[$index + 6]['coupon']; $total_coupon += $reports[$index + 6]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><?php echo number_format($reports[$index + 7]['coupon'], 2); $tenant7 += $reports[$index + 7]['coupon']; $total_coupon += $reports[$index + 7]['coupon']; ?></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right"><?php echo number_format($total_coupon, 2); ?></td>
                    <?php endif ?>
                </tr>
                <tr>
                    <td><b>TOTALS</b></td>
                    <?php if ($index + 0 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant0 ,2); $grand_total += $tenant0; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 1 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant1 ,2); $grand_total += $tenant1; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 2 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant2 ,2); $grand_total += $tenant2; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 3 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant3 ,2); $grand_total += $tenant3; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 4 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant4 ,2); $grand_total += $tenant4; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 5 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant5 ,2); $grand_total += $tenant5; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 6 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant6 ,2); $grand_total += $tenant6; ?></b></td>
                    <?php endif ?>
                    <?php if ($index + 7 < count($reports)): ?>
                        <td align="right"><b><?php echo number_format($tenant7 ,2); $grand_total += $tenant7; ?></b></td>
                    <?php endif ?>
                    <?php if ($last_part): ?>
                    <td align="right" style = "color:red"><b><?php echo number_format($grand_total, 2); ?></b></td>
                    <?php endif ?>
                </tr>
            </tbody>
        </table>
        <pre>

        </pre>
        <?php $counter++;  } ; ?>
    </body>
</html>
