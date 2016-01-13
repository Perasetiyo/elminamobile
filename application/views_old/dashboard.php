
    <?=$this->load->view('header');?>
	
    <div class="leftpanel">
		<?php $session_data = $this->session->userdata('logged_in'); ?>
        <div class="leftmenu">        
            <ul class="nav nav-tabs nav-stacked">
            	<li class="nav-header"><center><span style="font-size:18px; text-align:center; color:#FFF">ELMINA BACKOFFICE</span></center></li>
       	    
			<?php if ($session_data['user_role'] == 'admin' || $session_data['user_role'] == 'manager' || $session_data['user_role'] == 'operation') { ?>
			<li class="nav-header">Operation</li>
				<li><a href="<?=base_url()?>index.php/order"><span class="iconfa-laptop"></span>Orders</a></li>
				<li><a href="<?=base_url()?>index.php/stock/lists"><span class="iconfa-laptop"></span>Product Stock</a></li>
				<li><a href="<?=base_url()?>index.php/reject/lists"><span class="iconfa-laptop"></span>Reject</a></li>
				<li><a href="<?=base_url()?>index.php/customer/lists"><span class="iconfa-laptop"></span>Customer List</a></li>
				<li><a href="<?=base_url()?>index.php/deposit/lists"><span class="iconfa-laptop"></span>Deposit</a></li>
			<?php } ?>	
			
			<?php if ($session_data['user_role'] == 'admin' || $session_data['user_role'] == 'manager') { ?>
			<li class="nav-header">Stats & Logs</li>
				<li><a href="<?=base_url()?>index.php/cart_stats/main"><span class="iconfa-laptop"></span>Cart Statistic</a></li>
				<li><a href="<?=base_url()?>index.php/customer_stats/customer_rank"><span class="iconfa-laptop"></span>Customer Statistic</a></li>
				<li><a href="<?=base_url()?>index.php/activity/lists"><span class="iconfa-laptop"></span>Activity Log</a></li>
			
			<?php } ?>
			
			<?php if ($session_data['user_role'] == 'admin' || $session_data['user_role'] == 'finance') { ?>
			
			<li class="nav-header">Fixed Cost</li>
				<li><a href="<?=base_url()?>index.php/fixed_cost/lists"><span class="iconfa-laptop"></span>Fixed Cost List</a></li>
				<li><a href="<?=base_url()?>index.php/fixed_cost_history/lists"><span class="iconfa-laptop"></span>Fixed Cost History</a></li>
				
			<li class="nav-header">Manufacturing</li>
				<li><a href="<?=base_url()?>index.php/production_cost/lists"><span class="iconfa-laptop"></span>Cost</a></li>
				
			<li class="nav-header">Financing</li>
				<li><a href="<?=base_url()?>index.php/income_statement/main"><span class="iconfa-laptop"></span>Income Statement</a></li>
				<li><a href="<?=base_url()?>index.php/cash_statement/main"><span class="iconfa-laptop"></span>Cash Statement</a></li>
				<li><a href="<?=base_url()?>index.php/balance_sheet/main"><span class="iconfa-laptop"></span>Balance Sheet</a></li>
				<li><a href="<?=base_url()?>index.php/roseta_stone/main"><span class="iconfa-laptop"></span>Roseta Stone</a></li>
				<li><a href="<?=base_url()?>index.php/bankAccountAction"><span class="iconfa-laptop"></span>Bank Account</a></li>
			
			<li class="nav-header">Accounting</li>
				<li><a><h3><strong>ASSET</strong></h3></a></li>
				<li><a href="<?=base_url()?>index.php/cash/lists"><span class="iconfa-laptop"></span>Cash</a></li>
				<li><a href="<?=base_url()?>index.php/ppe/lists"><span class="iconfa-laptop"></span>PPE</a></li>
				<li><a href="<?=base_url()?>index.php/inventory/lists"><span class="iconfa-laptop"></span>Inventory</a></li>
				<li><a href="<?=base_url()?>index.php/acrec/lists"><span class="iconfa-laptop"></span>Account Receivable / Piutang</a></li>
				<li><a><h3><strong>LIABILITIES</strong></h3></a></li>
				<li><a href="<?=base_url()?>index.php/liabilities/lists"><span class="iconfa-laptop"></span>Liabilities</a></li>
				<li><a><h3><strong>EQUITY</strong></h3></a></li>
				<li><a href="<?=base_url()?>index.php/equity/lists"><span class="iconfa-laptop"></span>Investment</a></li>
				<li><a href="<?=base_url()?>index.php/earning/lists"><span class="iconfa-laptop"></span>Earnings</a></li>
				<li><a><h3><strong>COST</strong></h3></a></li>
				<li><a href="<?=base_url()?>index.php/expense/lists"><span class="iconfa-laptop"></span>Expenses</a></li>
				<li><a href="<?=base_url()?>index.php/depreciation/lists"><span class="iconfa-laptop"></span>Depreciation</a></li>
			<?php } ?>	
			
			<?php if (!empty($session_data['user_role'])) {?>
			<li class="nav-header">Logout</li>
				<li><a href="<?=base_url()?>index.php/login/logout"><span class="iconfa-laptop"></span>Logout</a></li>
			<?php } ?>
            </ul>
        </div><!--leftmenu-->
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
        
        <div class="pageheader">
           		
                <?=$this->load->view($page);?>
                </div><!--row-fluid-->
                
				
                <?=$this->load->view('footer');?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        
      // simple chart
		var flash = [[0, 11], [1, 9], [2,12], [3, 8], [4, 7], [5, 3], [6, 1]];
		var html5 = [[0, 5], [1, 4], [2,4], [3, 1], [4, 9], [5, 10], [6, 13]];
      var css3 = [[0, 6], [1, 1], [2,9], [3, 12], [4, 10], [5, 12], [6, 11]];
			
		function showTooltip(x, y, contents) {
			jQuery('<div id="tooltip" class="tooltipflot">' + contents + '</div>').css( {
				position: 'absolute',
				display: 'none',
				top: y + 5,
				left: x + 5
			}).appendTo("body").fadeIn(200);
		}
	
			
		var plot = jQuery.plot(jQuery("#chartplace"),
			   [ { data: flash, label: "Flash(x)", color: "#6fad04"},
              { data: html5, label: "HTML5(x)", color: "#06c"},
              { data: css3, label: "CSS3", color: "#666"} ], {
				   series: {
					   lines: { show: true, fill: true, fillColor: { colors: [ { opacity: 0.05 }, { opacity: 0.15 } ] } },
					   points: { show: true }
				   },
				   legend: { position: 'nw'},
				   grid: { hoverable: true, clickable: true, borderColor: '#666', borderWidth: 2, labelMargin: 10 },
				   yaxis: { min: 0, max: 15 }
				 });
		
		var previousPoint = null;
		jQuery("#chartplace").bind("plothover", function (event, pos, item) {
			jQuery("#x").text(pos.x.toFixed(2));
			jQuery("#y").text(pos.y.toFixed(2));
			
			if(item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;
						
					jQuery("#tooltip").remove();
					var x = item.datapoint[0].toFixed(2),
					y = item.datapoint[1].toFixed(2);
						
					showTooltip(item.pageX, item.pageY,
									item.series.label + " of " + x + " = " + y);
				}
			
			} else {
			   jQuery("#tooltip").remove();
			   previousPoint = null;            
			}
		
		});
		
		jQuery("#chartplace").bind("plotclick", function (event, pos, item) {
			if (item) {
				jQuery("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
				plot.highlight(item.series, item.datapoint);
			}
		});
    
        
        //datepicker
        jQuery('#datepicker').datepicker();
        
        // tabbed widget
        jQuery('.tabbedwidget').tabs();
        
        
    
    });
</script>
</body>
</html>
