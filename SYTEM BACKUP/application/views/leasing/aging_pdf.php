<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <title></title>
</head>
<body>

	<span class="aging-title"><?php echo $title; ?></span>
	<?php $total_current = 0; $total_days7 = 0;$total_days15 = 0; $total_days30 = 0; $total_days60 = 0; $total_days90 = 0; $total_days180 = 0; $total_days360 = 0; $grand_total = 0; ?>
	<table class="report_table" style="font-size:10px">
		<thead>
			<tr>
				<th width="8%">Tenant ID</th>
				<th width="20%">Trade Name</th>
				<th width="8%">Current <br> <span class="sub-header">(< 7 Days)</span></th>
				<th width="8%">7 Days <br> <span class="sub-header">(7 < 15 Days)</span></th>
				<th width="8%">15 Days <br> <span class="sub-header">(15 < 30 Days)</span></th>
				<th width="8%">30 Days <br> <span class="sub-header">(30 < 60 Days)</span></th>
				<th width="8%">60 Days <br> <span class="sub-header">(60 < 90 Days)</span></th>
				<th width="8%">90 Days <br> <span class="sub-header">(90 < 180 Days)</span></th>
				<th width="8%">180 Days <br> <span class="sub-header">(180 < 360 Days)</span></th>
				<th width="8%">Days 360 & Above</th>
				<th width="8%">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($reportData as $data): $total = 0; ?>
			<tr>
				<td><?php echo $data['tenant_id']; ?></td>
				<td><?php echo $data['trade_name']; ?></td>
				<td align="right"><?php if ($data['current'] > 1) {echo number_format($data['current'], 2); $total += $data['current']; $total_current += $data['current'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days7'] > 1) {echo number_format($data['days7'], 2); $total += $data['days7']; $total_days7 += $data['days7'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days15'] > 1) {echo number_format($data['days15'], 2); $total += $data['days15']; $total_days15 += $data['days15'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days30'] > 1) {echo number_format($data['days30'], 2); $total += $data['days30']; $total_days30 += $data['days30'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days60'] > 1) {echo number_format($data['days60'], 2); $total += $data['days60']; $total_days60 += $data['days60'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days90'] > 1) {echo number_format($data['days90'], 2); $total += $data['days90']; $total_days90 += $data['days90'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days180'] > 1) {echo number_format($data['days180'], 2); $total += $data['days180']; $total_days180 += $data['days180'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($data['days360'] > 1) {echo number_format($data['days360'], 2); $total += $data['days360']; $total_days360 += $data['days360'];} else{echo "-";} ?></td>
				<td align="right"><?php if ($total > 1) {echo number_format($total, 2); $grand_total += $total;} else{echo "-";} ?></td>
			</tr>
			<?php endforeach ?>
			<tr>
				<td><b>Grand Total</b></td>
				<td></td>
				<td align = "right"><b><?php if ($total_current > 1) {echo number_format($total_current, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days7 > 1) {echo number_format($total_days7, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days15 > 1) {echo number_format($total_days15, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days30 > 1) {echo number_format($total_days30, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days60 > 1) {echo number_format($total_days60, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days90 > 1) {echo number_format($total_days90, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days180 > 1) {echo number_format($total_days180, 2);}else{echo "-";} ?></b></td>
				<td align = "right"><b><?php if ($total_days360 > 1) {echo number_format($total_days360, 2);}else{echo "-";} ?></b></td>
				<td color="red" align = "right"><b><?php echo number_format($grand_total, 2); ?></b></td>
			</tr>
		</tbody>
	</table>
</body>
</html>