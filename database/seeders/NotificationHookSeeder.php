<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NotificationHook;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationHookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notification_hooks')->truncate();
        $hooks = [
            //URP
            ['id' => 1, "notification_template_id" => 3, 'name' => 'update_role', 'action' => 'Update Role', 'permission_group' => 'roles', 'variables' => '["role_name","action"]', 'permission_name' => 'edit.roles', 'icon' => 'mdi mdi-account-edit', 'bg_icon' => 'bg-warning'],

            ['id' => 2, "notification_template_id" => 2, 'name' => 'create_user', 'action' => 'Create User', 'permission_group' => 'users', 'variables' => '["user_name","user_email","user_mobile","user_role","user_department","action"]', 'permission_name' => 'add.users', 'icon' => 'mdi mdi-account-plus', 'bg_icon' => 'bg-success'],
            ['id' => 3, "notification_template_id" => 2, 'name' => 'update_user', 'action' => 'Update User', 'permission_group' => 'users', 'variables' => '["user_name","user_email","user_mobile","user_role","user_department","action"]', 'permission_name' => 'edit.users', 'icon' => 'mdi mdi-account-edit', 'bg_icon' => 'bg-warning'],

            //Leads
            ['id' => 4, "notification_template_id" => 4, 'name' => 'create_lead', 'action' => 'Create Lead', 'permission_group' => 'Leads', 'variables' => '["party_name", "party_address", "contact_person", "contact_number", "performed_by", "due_date","action"]', 'permission_name' => 'add.leads', 'icon' => 'mdi mdi-calendar-clock', 'bg_icon' => 'bg-success'],
            ['id' => 5, "notification_template_id" => 4, 'name' => 'delete_lead', 'action' => 'Delete Lead', 'permission_group' => 'Leads', 'variables' => '["party_name", "party_address", "contact_person", "contact_number", "performed_by", "due_date","action"]', 'permission_name' => 'delete.leads', 'icon' => 'mdi mdi-calendar-clock', 'bg_icon' => 'bg-danger'],
            ['id' => 6, "notification_template_id" => 4, 'name' => 'update_lead_status', 'action' => 'Update Lead Status', 'permission_group' => 'Leads', 'variables' => '["party_name", "party_address", "contact_person", "contact_number", "performed_by", "due_date","status","action"]', 'permission_name' => 'update.leads.status', 'icon' => 'mdi mdi-calendar-clock', 'bg_icon' => 'bg-warning'],

            //Pickup/delivery
            ['id' => 7, "notification_template_id" => 7, 'name' => 'complete_pickup_delivery', 'action' => 'Complete Pickup/Delivery', 'permission_group' => 'Pickup/Delivery', 'variables' => '["reference_number","party_name", "party_address", "performed_by", "completed_on","action"]', 'permission_name' => 'menu.pickup.delivery', 'icon' => 'mdi mdi-car-lifted-pickup', 'bg_icon' => 'bg-success'],

            //Tenders
            //Manage tender
            ['id' => 8, "notification_template_id" => 5, 'name' => 'create_tender', 'action' => 'Create Tender', 'permission_group' => 'tender', 'variables' => '["due_date", "tender_number", "party_name", "party_address", "portal", "status", "remarks", "performed_by", "action"]', 'permission_name' => 'add.tender', 'icon' => 'mdi mdi-file-plus', 'bg_icon' => 'bg-success'],
            ['id' => 9, "notification_template_id" => 5, 'name' => 'update_tender', 'action' => 'Update Tender', 'permission_group' => 'tender', 'variables' => '["due_date", "tender_number", "party_name", "party_address", "portal", "status", "remarks", "performed_by", "action"]', 'permission_name' => 'edit.tender', 'icon' => 'mdi mdi-file-document-edit', 'bg_icon' => 'bg-warning'],
            ['id' => 10, "notification_template_id" => 5, 'name' => 'delete_tender', 'action' => 'Delete Tender', 'permission_group' => 'tender', 'variables' => '["due_date", "tender_number", "party_name", "party_address", "portal", "status", "remarks", "performed_by", "action"]', 'permission_name' => 'delete.tender', 'icon' => 'mdi mdi-delete-circle', 'bg_icon' => 'bg-danger'],

            //Applied Tender

            ['id' => 11, "notification_template_id" => 5, 'name' => 'apply_tender', 'action' => 'Apply Tender', 'permission_group' => 'tender', 'variables' => '["due_date", "tender_number", "party_name", "party_address", "portal", "status", "remarks", "tender_fees", "emd", "security_deposit", "performed_by", "action"]', 'permission_name' => 'apply.tender', 'icon' => 'mdi mdi-file-export', 'bg_icon' => 'bg-success'],
            ['id' => 12, "notification_template_id" => 5, 'name' => 'update_applied_tender', 'action' => 'Update Applied Tender', 'permission_group' => 'tender', 'variables' => '["due_date", "tender_number", "party_name", "party_address", "portal", "status", "remarks", "tender_fees", "emd", "security_deposit", "performed_by", "action"]', 'permission_name' => 'edit.tender', 'icon' => 'mdi mdi-file-document-edit', 'bg_icon' => 'bg-warning'],


            //Payment In
            ['id' => 13, "notification_template_id" => 9, 'name' => 'create_payment_in', 'action' => 'Create Payment In', 'permission_group' => 'paymentIn', 'variables' => '["reference_number", "party_name", "payment_type", "date", "amount","performed_by", "action"]', 'permission_name' => 'add.paymentin', 'icon' => 'mdi mdi-cash-plus', 'bg_icon' => 'bg-success'],
            ['id' => 14, "notification_template_id" => 9, 'name' => 'update_payment_in', 'action' => 'Update Payment In', 'permission_group' => 'paymentIn', 'variables' => '["reference_number", "party_name", "payment_type", "date", "amount","performed_by", "action"]', 'permission_name' => 'edit.paymentin', 'icon' => 'mdi mdi-cash-plus', 'bg_icon' => 'bg-warning'],

            //Payment Out
            ['id' => 15, "notification_template_id" => 9, 'name' => 'create_payment_out', 'action' => 'Create Payment Out', 'permission_group' => 'paymentOut', 'variables' => '["reference_number", "party_name", "payment_type", "date", "amount","performed_by", "action"]', 'permission_name' => 'add.paymentout', 'icon' => 'mdi mdi-cash-minus', 'bg_icon' => 'bg-success'],
            ['id' => 16, "notification_template_id" => 9, 'name' => 'update_payment_out', 'action' => 'Update Payment Out', 'permission_group' => 'paymentOut', 'variables' => '["reference_number", "party_name", "payment_type", "date", "amount","performed_by", "action"]', 'permission_name' => 'edit.paymentout', 'icon' => 'mdi mdi-cash-minus', 'bg_icon' => 'bg-warning'],

            //Tickets
            ['id' => 17, "notification_template_id" => 8, 'name' => 'create_ticket', 'action' => 'Create Ticket', 'permission_group' => 'ticket', 'variables' => '["department", "topic", "status", "priority", "performed_by", "action"]', 'permission_name' => 'add.ticket', 'icon' => 'mdi mdi-lifebuoy', 'bg_icon' => 'bg-success'],
            ['id' => 18, "notification_template_id" => 8, 'name' => 'update_ticket', 'action' => 'Update Ticket', 'permission_group' => 'ticket', 'variables' => '["department", "topic", "status", "priority", "performed_by", "action"]', 'permission_name' => 'edit.ticket', 'icon' => 'mdi mdi-lifebuoy', 'bg_icon' => 'bg-success'],

            //Quotation
            ['id' => 19, "notification_template_id" => 1, 'name' => 'create_quotation', 'action' => 'Create Quotation', 'permission_group' => 'quotation', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'add.quotation', 'icon' => 'mdi mdi-file-delimited', 'bg_icon' => 'bg-success'],
            ['id' => 20, "notification_template_id" => 1, 'name' => 'update_quotation', 'action' => 'Update Quotation', 'permission_group' => 'quotation', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'edit.quotation', 'icon' => 'mdi mdi-file-delimited', 'bg_icon' => 'bg-warning'],
            ['id' => 21, "notification_template_id" => 1, 'name' => 'cancel_quotation', 'action' => 'Cancel Quotation', 'permission_group' => 'quotation', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'cancel.quotation', 'icon' => 'mdi mdi-file-delimited', 'bg_icon' => 'bg-danger'],

            //Sale Order
            ['id' => 22, "notification_template_id" => 1, 'name' => 'create_sale_order', 'action' => 'Create Sale Order', 'permission_group' => 'saleOrder', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'add.sales.order', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-success'],
            ['id' => 23, "notification_template_id" => 1, 'name' => 'update_sale_order', 'action' => 'Update Sale Order', 'permission_group' => 'saleOrder', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'edit.sales.order', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-warning'],
            ['id' => 24, "notification_template_id" => 1, 'name' => 'cancel_sale_order', 'action' => 'Cancel Sale Order', 'permission_group' => 'saleOrder', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'cancel.saleorder', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-danger'],

            //Sale Invoice
            ['id' => 25, "notification_template_id" => 1, 'name' => 'create_sale_invoice', 'action' => 'Create Sale Invoice', 'permission_group' => 'saleInvoice', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'add.sale.invoice', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-success'],
            ['id' => 26, "notification_template_id" => 1, 'name' => 'update_sale_invoice', 'action' => 'Update Sale Invoice', 'permission_group' => 'saleInvoice', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'edit.sale.invoice', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-warning'],
            ['id' => 27, "notification_template_id" => 1, 'name' => 'cancel_sale_invoice', 'action' => 'Cancel Sale Invoice', 'permission_group' => 'saleInvoice', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'cancel.saleinvoice', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-danger'],


            //Purchase Order
            ['id' => 28, "notification_template_id" => 1, 'name' => 'create_purchase_order', 'action' => 'Create Purchase Order', 'permission_group' => 'purchaseOrder', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'add.purchase.order', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-success'],
            ['id' => 29, "notification_template_id" => 1, 'name' => 'update_purchase_order', 'action' => 'Update Purchase Order', 'permission_group' => 'purchaseOrder', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'edit.purchase.order', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-warning'],
            ['id' => 30, "notification_template_id" => 1, 'name' => 'cancel_purchase_order', 'action' => 'Cancel Purchase Order', 'permission_group' => 'purchaseOrder', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'cancel.purchase.order', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-danger'],

            //Purchase bill
            ['id' => 31, "notification_template_id" => 1, 'name' => 'create_purchase_bill', 'action' => 'Create Purchase Bill', 'permission_group' => 'purchaseBill', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'add.purchase.bill', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-success'],
            ['id' => 32, "notification_template_id" => 1, 'name' => 'update_purchase_bill', 'action' => 'Update Purchase Bill', 'permission_group' => 'purchaseBill', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'edit.purchase.bill', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-warning'],
            ['id' => 33, "notification_template_id" => 1, 'name' => 'cancel_purchase_bill', 'action' => 'Cancel Purchase Bill', 'permission_group' => 'purchaseBill', 'variables' => '["reference_number", "party_name", "date_due", "amount","performed_by", "action"]', 'permission_name' => 'cancel.purchase.bill', 'icon' => 'mdi mdi-file-document', 'bg_icon' => 'bg-danger'],

            //Warranty Certificate
            ['id' => 34, "notification_template_id" => 6, 'name' => 'update_warranty_certificate', 'action' => 'Update Warranty Certificate', 'permission_group' => 'saleInvoice', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'edit.certificates', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-warning'],

            //Compliance Certificate
            ['id' => 35, "notification_template_id" => 6, 'name' => 'update_compliance_certificate', 'action' => 'Update Compliance Certificate', 'permission_group' => 'saleInvoice', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'edit.certificates', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-warning'],

            //Refilling Certificate
            ['id' => 36, "notification_template_id" => 6, 'name' => 'create_refilling_certificate', 'action' => 'Create Refilling Certificate', 'permission_group' => 'ProcessRefilling', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'create.certificate', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-success'],
            ['id' => 37, "notification_template_id" => 6, 'name' => 'update_refilling_certificate', 'action' => 'Update Refilling Certificate', 'permission_group' => 'ProcessRefilling', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'edit.certificate', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-warning'],

            ['id' => 38, "notification_template_id" => 6, 'name' => 'cancel_refilling_certificate', 'action' => 'Cancel Refilling Certificate', 'permission_group' => 'ProcessRefilling', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'cancel.certificate', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-danger'],

            //HPT Certificate
            ['id' => 39, "notification_template_id" => 6, 'name' => 'create_hpt_certificate', 'action' => 'Create HPT Certificate', 'permission_group' => 'ProcessRefilling', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'create.certificate', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-success'],

            ['id' => 40, "notification_template_id" => 6, 'name' => 'update_hpt_certificate', 'action' => 'Update HPT Certificate', 'permission_group' => 'ProcessRefilling', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'edit.certificate', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-warning'],

            ['id' => 41, "notification_template_id" => 6, 'name' => 'cancel_hpt_certificate', 'action' => 'Cancel HPT Certificate', 'permission_group' => 'ProcessRefilling', 'variables' => '["certificate_number", "party_name", "performed_by", "action"]', 'permission_name' => 'cancel.certificate', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-danger'],

            //On-site Refilling
            ['id' => 42, "notification_template_id" => 10, 'name' => 'on_site_refilling_complete', 'action' => 'On-Site Refilling Complete', 'permission_group' => 'ProcessRefilling', 'variables' => '["reference_number", "party_name", "performed_by", "action"]', 'permission_name' => 'create.delivery.requests', 'icon' => 'mdi mdi-certificate', 'bg_icon' => 'bg-success'],

            //Manage Vendor
            ['id' => 43, "notification_template_id" => 11, 'name' => 'delete_vendor', 'action' => 'Delete Vendor', 'permission_group' => 'vendor', 'variables' => '["vendor_name", "performed_by", "action"]', 'permission_name' => 'delete.vendor', 'icon' => 'mdi mdi-account-edit', 'bg_icon' => 'bg-danger'],

            //Spare Parts
            ['id' => 44, "notification_template_id" => 12, 'name' => 'update_spare_partes', 'action' => 'Update Spare Parts', 'permission_group' => 'Refilling/Service', 'variables' => '["performed_by", "action"]', 'permission_name' => 'spare.parts.catalogue', 'icon' => 'mdi mdi-cog-outline', 'bg_icon' => 'bg-warning'],

            //Cylinders
            ['id' => 45, "notification_template_id" => 12, 'name' => 'update_cylinder', 'action' => 'Update Cylinders', 'permission_group' => 'Refilling/Service', 'variables' => '["performed_by", "action"]', 'permission_name' => 'cylinder.catalogue', 'icon' => 'mdi mdi-cog-outline', 'bg_icon' => 'bg-warning'],

        ];

        // // Extract names to keep
        // $hookNames = array_column($hooks, 'name');

        // // Delete hooks not in the list
        // NotificationHook::whereNotIn('name', $hookNames)->delete();

        // // Insert each hook with auto-incrementing IDs
        // foreach ($hooks as $hook) {
        //     NotificationHook::updateOrCreate([
        //         'name' => $hook['name'],
        //     ], [
        //         'id' => $hook['id'],
        //         'notification_template_id' => $hook['notification_template_id'],
        //         'action' => $hook['action'],
        //         'permission_group' => $hook['permission_group'],
        //         'variables' => $hook['variables'],
        //         'permission_name' => $hook['permission_name'],
        //         'icon' => $hook['icon'],
        //         'bg_icon' => $hook['bg_icon'],
        //     ]);
        // }


        DB::table('notification_hooks')->insert($hooks);
    }
}
