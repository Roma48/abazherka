
<table class="vm-table">
     <tr>
          <td> <strong><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_NUMBER') ?><strong></td>
          <td> <strong><?php echo $this->orderdetails['details']['BT']->order_number; ?><strong> </td>
     </tr>
     <tr>
          <td><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_DATE') ?></td>
          <td><?php echo vmJsApi::date($this->orderdetails['details']['BT']->created_on, 'LC4', true); ?></td>
     </tr>
     <tr>
          <td><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PO_STATUS') ?></td>
          <td><?php echo $this->orderstatuses[$this->orderdetails['details']['BT']->order_status]; ?></td>
     </tr>
     <tr>
          <td><?php echo JText::_('COM_VIRTUEMART_LAST_UPDATED') ?></td>
          <td><?php echo vmJsApi::date($this->orderdetails['details']['BT']->modified_on, 'LC4', true); ?></td>
     </tr>
     <tr>
          <td><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SHIPMENT_LBL') ?></td>
          <td>
               <?php
	    echo $this->shipment_name;
	    ?>
          </td>
     </tr>
     <tr>
          <td><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_PAYMENT_LBL') ?></td>
          <td><?php echo $this->payment_name; ?> </td>
     </tr>
     <tr>
          <td><?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_CUSTOMER_NOTE') ?></td>
          <td valign="top" width="50%"><?php echo $this->orderdetails['details']['BT']->customer_note; ?></td>
     </tr>
     <tr>
          <td colspan="2" class="PricebillTotal" align="right"><?php echo $this->currency->priceDisplay($this->orderdetails['details']['BT']->order_total, $this->currency); ?></td>
     </tr>
</table>
<table class="vm-table">
     <tr>
          <td class="no-border"><strong> <?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_BILL_TO_LBL') ?></strong> <br/>
               <dl>
                    <?php
	    foreach ($this->userfields['fields'] as $field) {
		if (!empty($field['value'])) {
		    echo '<dt>' . $field['title'] . '</dt>'
		    . '<dd>' . $field['value'] . '</dd>';
		}
	    }
	    ?>
               </dl>
          </td>
          <td class="no-border"><strong> <?php echo JText::_('COM_VIRTUEMART_ORDER_PRINT_SHIP_TO_LBL') ?></strong><br/>
               <dl>
                    <?php
	    foreach ($this->shipmentfields['fields'] as $field) {
		if (!empty($field['value'])) {
		    echo '<dt>' . $field['title'] . '</dt>'
		    . '<dd>' . $field['value'] . '</dd>';
		}
	    }
	    ?>
               </dl>
          </td>
     </tr>
</table>
