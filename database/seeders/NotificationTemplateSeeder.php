<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class NotificationTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('notification_templates')->truncate();

        $data = [
            [
                "id" => 1,
                "name" => "Transactions",
                "template" => "{{action}} | Ref No: {{reference_number}} , Party: {{party_name}} , Amount: {{amount}} /-",
                "hooks" => '["create_purchase_bill","create_purchase_order","create_quotation","create_sale_invoice","create_sale_order","update_purchase_bill","update_purchase_order","update_quotation","update_sale_invoice","update_sale_order","cancel_purchase_bill","cancel_purchase_order","cancel_quotation","cancel_sale_invoice","cancel_sale_order"]',
                "hooks_id" => '["19","20","21","22","23","24","25","26","27","28","29","30","31","32","33"]',
                "created_at" => "2025-11-20 12:32:46",
                "updated_at" => "2025-11-25 10:32:38",
            ],
            [
                "id" => 2,
                "name" => "User",
                "template" => "{{action}} | Name: {{user_name}} , Role: {{user_role}}",
                "hooks" => '["create_user","update_user"]',
                "hooks_id" => '["2","3"]',
                "created_at" => "2025-11-25 10:34:49",
                "updated_at" => "2025-11-25 10:34:49",
            ],
            [
                "id" => 3,
                "name" => "Role",
                "template" => "{{action}} | Role: {{role_name}}",
                "hooks" => '["update_role"]',
                "hooks_id" => '["1"]',
                "created_at" => "2025-11-25 10:35:18",
                "updated_at" => "2025-11-25 10:35:18",
            ],
            [
                "id" => 4,
                "name" => "Lead",
                "template" => "{{action}} | Party: {{party_name}} , Contact: {{contact_person}} - {{contact_number}}",
                "hooks" => '["create_lead","delete_lead","update_lead_status"]',
                "hooks_id" => '["4","5","6"]',
                "created_at" => "2025-11-25 10:36:33",
                "updated_at" => "2025-11-25 10:36:33",
            ],
            [
                "id" => 5,
                "name" => "Tender",
                "template" => "{{action}} | Tender: {{tender_number}} , Due: {{due_date}} , Status: {{status}}",
                "hooks" => '["create_tender","update_tender","delete_tender","apply_tender","update_applied_tender"]',
                "hooks_id" => '["8","9","10","11","12"]',
                "created_at" => "2025-11-25 10:38:12",
                "updated_at" => "2025-11-25 10:38:12",
            ],
            [
                "id" => 6,
                "name" => "Certificate",
                "template" => "{{action}} | Cert No: {{certificate_number}} , Party: {{party_name}} , Created by: {{performed_by}}",
                "hooks" => '["update_warranty_certificate","update_compliance_certificate","create_refilling_certificate","update_refilling_certificate","cancel_refilling_certificate","create_hpt_certificate","update_hpt_certificate","cancel_hpt_certificate"]',
                "hooks_id" => '["34","35","36","37","38","39","40","41"]',
                "created_at" => "2025-11-25 10:40:00",
                "updated_at" => "2025-11-25 10:40:00",
            ],
            [
                "id" => 7,
                "name" => "Pickup-delivery",
                "template" => "{{action}} | Ref No: {{reference_number}} , Party: {{party_name}} , Performed By: {{performed_by}}",
                "hooks" => '["complete_pickup_delivery"]',
                "hooks_id" => '["7"]',
                "created_at" => "2025-11-25 10:42:07",
                "updated_at" => "2025-11-25 10:42:07",
            ],
            [
                "id" => 8,
                "name" => "Ticket",
                "template" => "{{action}} | Priority: {{priority}} , Status: {{status}} , Department: {{department}}",
                "hooks" => '["create_ticket","update_ticket"]',
                "hooks_id" => '["17","18"]',
                "created_at" => "2025-11-25 10:43:51",
                "updated_at" => "2025-11-25 10:43:51",
            ],
            [
                "id" => 9,
                "name" => "Payment",
                "template" => "{{action}} | Ref No: {{reference_number}} , Party: {{party_name}} , Amount: {{amount}} /-",
                "hooks" => '["create_payment_in","update_payment_in","create_payment_out","update_payment_out"]',
                "hooks_id" => '["13","14","15","16"]',
                "created_at" => "2025-11-25 10:55:16",
                "updated_at" => "2025-11-25 10:55:16",
            ],
            [
                "id" => 10,
                "name" => "On-Site Refilling completed",
                "template" => "Ref No: {{reference_number}} , Party: {{party_name}}",
                "hooks" => '["on_site_refilling_complete"]',
                "hooks_id" => '["42"]',
                "created_at" => "2025-11-25 10:56:54",
                "updated_at" => "2025-11-25 10:56:54",
            ],
            [
                "id" => 11,
                "name" => "Vendor",
                "template" => "{{action}} | Vendor: {{vendor_name}}",
                "hooks" => '["delete_vendor"]',
                "hooks_id" => '["43"]',
                "created_at" => "2025-11-25 11:48:50",
                "updated_at" => "2025-11-25 11:48:50",
            ],
            [
                "id" => 12,
                "name" => "Refilling Settings",
                "template" => "{{action}} | Performed by: {{performed_by}}",
                "hooks" => '["update_spare_partes","update_cylinder"]',
                "hooks_id" => '["44","45"]',
                "created_at" => "2025-11-25 11:51:27",
                "updated_at" => "2025-11-25 11:51:27",
            ],
        ];

        DB::table('notification_templates')->insert($data);
    }
}
