<?php

$managerRole="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\r\n<!-- \r\n    uniCenta oPOS - Touch friendly Point Of Sale\r\n    Copyright (c) 2009-2012 uniCenta.\r\n    http://sourceforge.net/projects/unicentaopos\r\n\r\n    This file is part of uniCenta oPOS.\r\n    uniCenta oPOS is free software: you can redistribute it and/or modify\r\n    it under the terms of the GNU General Public License as published by\r\n    the Free Software Foundation, either version 3 of the License, or\r\n    (at your option) any later versions.\r\n\r\n    uniCenta oPOS is distributed in the hope that it will be useful,\r\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\r\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\r\n    GNU General Public License for more details.\r\n\r\n    You should have received a copy of the GNU General Public License\r\n    along with uniCenta oPOS.  If not, see <http://www.gnu.org/licenses/>.\r\n -->\r\n\r\n<!-- \r\n    The Manager Role Permissions are same as Administrator Role Permissions \r\n    Adjust these to suit requirements\r\n-->\r\n    \r\n<permissions>\r\n    <class name=\"com.openbravo.pos.sales.JPanelTicketSales\"/>\r\n    <class name=\"com.openbravo.pos.sales.JPanelTicketEdits\"/>\r\n    <class name=\"com.openbravo.pos.customers.CustomersPayment\"/>    \r\n    <class name=\"com.openbravo.pos.panels.JPanelPayments\"/>\r\r\n    <class name=\"/com/openbravo/reports/closedpos.bs\"/>\r\n    <class name=\"sales.EditLines\"/>\r\n<!--    <class name=\"sales.EditTicket\"/>-->\r\n    <class name=\"sales.RefundTicket\"/>\r\n    <class name=\"sales.PrintTicket\"/>\r\n    <class name=\"sales.Total\"/>\r\n    <class name=\"sales.ChangeTaxOptions\"/>\r\n    <class name=\"payment.bank\"/>\r\n    <class name=\"payment.cash\"/>\r\n    <class name=\"payment.cheque\"/>\r\n    <class name=\"payment.paper\"/>\r\n    <class name=\"payment.magcard\"/>\r\n    <class name=\"payment.free\"/>\r\n    <class name=\"refund.cash\"/>\r\n    <class name=\"refund.cheque\"/>\r\n    <class name=\"refund.paper\"/>\r\n    <class name=\"refund.magcard\"/>\r\n    <class name=\"com.openbravo.pos.forms.MenuCustomers\"/>\r\n    <class name=\"com.openbravo.pos.customers.CustomersPanel\"/>\r\n    <class name=\"com.openbravo.pos.suppliers.SuppliersPanel\"/>\r\n    <class name=\"/com/openbravo/reports/customers_list.bs\"/>\r\n    <class name=\"/com/openbravo/reports/nosaledraweropenings.bs\"/>\r\n    <class name=\"/com/openbravo/reports/customers.bs\"/>\r\n    <class name=\"/com/openbravo/reports/customersb.bs\"/>\r\n    <class name=\"/com/openbravo/reports/customersdiary.bs\"/>\r\n    <class name=\"com.openbravo.pos.inventory.TaxCustCategoriesPanel\"/>    \r\n    <class name=\"com.openbravo.pos.forms.MenuStockManagement\"/>\r\n    <class name=\"com.openbravo.pos.inventory.ProductsPanel\"/>\r\n    <class name=\"com.openbravo.pos.promotion.JPanelPromo\"/>\r\n    <class name=\"com.openbravo.pos.inventory.ProductsWarehousePanel\"/>    \r\n    <class name=\"com.openbravo.pos.inventory.CategoriesPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.AttributesPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.AttributeValuesPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.AttributeSetsPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.AttributeUsePanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.TaxPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.TaxCategoriesPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.StockDiaryPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.StockManagement\"/>\r\n    <class name=\"com.openbravo.pos.inventory.AuxiliarPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.OrderImportExport\"/>\r\n    <class name=\"/com/openbravo/reports/products.bs\"/>      \r\n    <class name=\"/com/openbravo/reports/productlabels.bs\"/>      \r\n    <class name=\"/com/openbravo/reports/salecatalog.bs\"/>\r\n    <class name=\"/com/openbravo/reports/inventory.bs\"/>\r\n    <class name=\"/com/openbravo/reports/inventoryb.bs\"/>\r\n    <class name=\"/com/openbravo/reports/inventorybroken.bs\"/>\r\n    <class name=\"/com/openbravo/reports/inventorylistdetail.bs\"/>\r\n    <class name=\"/com/openbravo/reports/inventorydiff.bs\"/>\r\n    <class name=\"/com/openbravo/reports/inventorydiffdetail.bs\"/>\r\n    <class name=\"com.openbravo.pos.forms.MenuSalesManagement\"/>\r\n    <class name=\"/com/openbravo/reports/usersales.bs\"/>\r\n    <class name=\"/com/openbravo/reports/closedproducts.bs\"/>\r\n    <class name=\"/com/openbravo/reports/taxes.bs\"/>\r\n    <class name=\"/com/openbravo/reports/chartsales.bs\"/> \r\n    <class name=\"/com/openbravo/reports/productsales.bs\"/>\r\n    <class name=\"/com/openbravo/reports/cashflow.bs\"/>\r\n    <class name=\"/com/openbravo/reports/cashregisterlog.bs\"/>\r\n    <class name=\"/com/openbravo/reports/categorysales.bs\"/>\r\n    <class name=\"/com/openbravo/reports/extendedcashregisterlog.bs\"/>\r\n    <class name=\"/com/openbravo/reports/paymentreport.bs\"/>\r\n    <class name=\"/com/openbravo/reports/salebycustomer.bs\"/>\r\n    <class name=\"/com/openbravo/reports/saletaxes.bs\"/>\r\n    <!--class name=\"com.openbravo.pos.forms.MenuMaintenance\"/-->\r\n    <class name=\"com.openbravo.pos.admin.PeoplePanel\"/>\r\n    <class name=\"com.openbravo.pos.admin.RolesPanel\"/>\r\n    <class name=\"com.openbravo.pos.admin.ResourcesPanel\"/>\r\n    <class name=\"com.openbravo.pos.inventory.LocationsPanel\"/>\r\n    <class name=\"com.openbravo.pos.mant.JPanelFloors\"/>\r\n    <class name=\"com.openbravo.pos.mant.JPanelPlaces\"/>\r\n    <class name=\"/com/openbravo/reports/people.bs\"/>\r\n<!--    <class name=\"com.openbravo.possync.ProductsSyncCreate\"/> -->\r\n<!--    <class name=\"com.openbravo.possync.OrdersSyncCreate\"/> -->\r\n    <class name=\"Menu.ChangePassword\"/>\r\n    <class name=\"com.openbravo.pos.panels.JPanelPrinter\"/>\r\n    <!--class name=\"com.openbravo.pos.config.JPanelConfiguration\"/-->\r\n    \r\n    <class name=\"/com/openbravo/reports/salesbysupplier.bs\"/>\r\n    <class name=\"/com/openbravo/reports/purchasingbysupplier.bs\"/>\r\n    \r\n    <class name=\"com.openbravo.pos.imports.JPanelCSV\"/>\r\n    <class name=\"com.openbravo.pos.imports.JPanelCSVImport\"/>\r\n    <class name=\"com.openbravo.pos.imports.JPanelCSVCleardb\"/>\r\n    <class name=\"/com/openbravo/reports/updatedprices.bs\"/>\r\n    <class name=\"/com/openbravo/reports/newproducts.bs\"/>\r\n    <class name=\"/com/openbravo/reports/missingdata.bs\"/>\r\n    <class name=\"/com/openbravo/reports/invaliddata.bs\"/>\r\n    \r\n\r\n<!-- Section for Additional button scripts -->    \r\n<!--    <class name=\"button.refundit\"/> -->\r\n    <class name=\"button.sendorder\"/>\r\n    <class name=\"button.print\"/>\r\n    <class name=\"button.opendrawer\"/>\r\n    <class name=\"button.linediscount\"/>\r\n<!--    <class name=\"button.totaldiscount\"/> -->\r\n<!--    <class name=\"button.scharge\"/> -->\r\n\r\n<!-- These reports will report an error with Derby DB\'s due to SQL limitations \r\n    Remove the comment at beginning and of line\r\n    You will also need to enable in Administration>Resources>Roles>Administrator/Manager & etc ***\r\n    -->\r\n    <class name=\"/com/openbravo/reports/extproducts.bs\"/>\r\n    <class name=\"/com/openbravo/reports/categorysales.bs\"/>\r\n    <!--class name=\"/com/openbravo/reports/productsalesprofit.bs\"/-->\r\n    <class name=\"/com/openbravo/reports/piesalescat.bs\"/>\r\n    <class name=\"/com/openbravo/reports/top10sales.bs\"/>\r\n    \r\n<!-- EPM -->\r\n  <class name=\"com.openbravo.pos.forms.MenuEmployees\" />\r\n  <class name=\"com.openbravo.pos.epm.BreaksPanel\" /> \r\n  <class name=\"com.openbravo.pos.epm.LeavesPanel\" />\r\n  <class name=\"com.openbravo.pos.epm.JPanelEmployeePresence\" />  \r\n  <class name=\"/com/openbravo/reports/dailypresencereport.bs\" /> \r\n  <class name=\"/com/openbravo/reports/dailyschedulereport.bs\" /> \r\n<!--  <class name=\"/com/openbravo/reports/performancereport.bs\" />  -->\r\n        \r\n\r\n</permissions>";

//$empRole="<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<!-- \n    uniCenta oPOS - Touch friendly Point Of Sale\n    Copyright (c) 2009-2012 uniCenta.\n    http://sourceforge.net/projects/unicentaopos\n\n    This file is part of uniCenta oPOS.\n\n    uniCenta oPOS is free software: you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation, either version 3 of the License, or\n    (at your option) any later version.\n\n    uniCenta oPOS is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    GNU General Public License for more details.\n\n    You should have received a copy of the GNU General Public License\n    along with uniCenta oPOS.  If not, see <http://www.gnu.org/licenses/>.\n -->\n<permissions>\n    <class name=\"com.openbravo.pos.sales.JPanelTicketSales\"/>\n   <class name=\"com.openbravo.pos.panels.JPanelPayments\"/>\n    <class name=\"com.openbravo.pos.promotion.JPanelPromo\"/>\n<class name=\"sales.EditLines\"/>\n  <class name=\"sales.RefundTicket\"/>\n    <class name=\"sales.PrintTicket\"/>\n    <class name=\"sales.Total\"/>\n   <class name=\"payment.cash\"/>\n  <class name=\"payment.magcard\"/> \n    <class name=\"payment.paper\"/> \n    <class name=\"refund.cash\"/>\n    <class name=\"refund.cheque\"/>\n    <class name=\"refund.paper\"/>\n    <class name=\"refund.magcard\"/>\n   \n<!-- EPM -->\n <!-- <class name=\"com.openbravo.pos.epm.JPanelEmployeePresence\" /> -->   \n\n  <!-- Section for Additional button scripts -->    \n<!--    <class name=\"button.refundit\"/> -->\n<!--<class name=\"button.sendorder\"/>-->\n    <class name=\"button.print\"/>\n    <class name=\"button.opendrawer\"/> \n    <class name=\"button.linediscount\"/> \n <class name=\"button.scharge\"/>\n\n</permissions>";

$empRole = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><permissions><class name=\"com.openbravo.pos.sales.JPanelTicketSales\"/><class name=\"com.openbravo.pos.sales.JPanelTicketEdits\"/><class name=\"com.openbravo.pos.panels.JPanelPayments\"/><class name=\"com.openbravo.pos.promotion.JPanelPromo\"/><class name=\"sales.EditLines\"/><class name=\"sales.RefundTicket\"/><class name=\"sales.PrintTicket\"/><class name=\"button.sendorder\"/><class name=\"sales.Total\"/><class name=\"payment.cash\"/><class name=\"payment.cheque\"/><class name=\"payment.paper\"/><class name=\"payment.magcard\"/><class name=\"payment.laybyrefund\"/><class name=\"payment.free\"/><class name=\"refund.cash\"/><class name=\"refund.cheque\"/><class name=\"refund.paper\"/><class name=\"refund.magcard\"/><class name=\"Menu.ChangePassword\"/><class name=\"com.openbravo.pos.epm.JPanelEmployeePresence\" /><class name=\"button.print\"/><class name=\"button.opendrawer\"/><class name=\"button.linediscount\"/></permissions>";


?>
