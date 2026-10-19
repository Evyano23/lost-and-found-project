<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InitialDataSeeder extends Seeder
{
    public function run(): void
    {
        // Only seed if users table is empty to avoid overwriting production data
        if (DB::table('users')->count() > 0) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        // categories (10 records)
        DB::table('categories')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'Electronics & Gadgets',
    'slug' => 'electronics-gadgets',
    'icon' => 'device-phone-mobile',
    'description' => 'Smartphones, laptops, tablets, earbuds, chargers, and smartwatches.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'Wallets, Purses & Bags',
    'slug' => 'wallets-purses-bags',
    'icon' => 'briefcase',
    'description' => 'Wallets, clutches, backpacks, tote bags, and luggage.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'Keys & Access Badges',
    'slug' => 'keys-access-badges',
    'icon' => 'key',
    'description' => 'House keys, car key fobs, building access cards, and keychains.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  3 => 
  array (
    'id' => 4,
    'name' => 'Documents & IDs',
    'slug' => 'documents-ids',
    'icon' => 'identification',
    'description' => 'Passports, driver licenses, student badges, and bank cards.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  4 => 
  array (
    'id' => 5,
    'name' => 'Pets & Animals',
    'slug' => 'pets-animals',
    'icon' => 'heart',
    'description' => 'Dogs, cats, birds, and other missing or rescued animals.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  5 => 
  array (
    'id' => 6,
    'name' => 'Jewelry & Watches',
    'slug' => 'jewelry-watches',
    'icon' => 'sparkles',
    'description' => 'Watches, rings, necklaces, bracelets, and precious items.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  6 => 
  array (
    'id' => 7,
    'name' => 'Clothing & Eyewear',
    'slug' => 'clothing-eyewear',
    'icon' => 'tag',
    'description' => 'Jackets, prescription glasses, sunglasses, hats, and scarves.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  7 => 
  array (
    'id' => 8,
    'name' => 'Books & Stationery',
    'slug' => 'books-stationery',
    'icon' => 'book-open',
    'description' => 'Notebooks, textbooks, binders, and art supplies.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  8 => 
  array (
    'id' => 9,
    'name' => 'Sports & Mobility',
    'slug' => 'sports-mobility',
    'icon' => 'truck',
    'description' => 'Bicycles, scooters, skateboards, gym bags, and sporting gear.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
  9 => 
  array (
    'id' => 10,
    'name' => 'Other Miscellaneous',
    'slug' => 'other-miscellaneous',
    'icon' => 'archive-box',
    'description' => 'Any items not covered by other categories.',
    'is_active' => 1,
    'created_at' => '2026-09-18 18:21:19',
    'updated_at' => '2026-09-18 18:21:19',
  ),
));

        // users (4 records)
        DB::table('users')->insert(array (
  0 => 
  array (
    'id' => 1,
    'name' => 'eviano destiny',
    'email' => 'creteboi23@gmail.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$Q2HLeLQM3AurRznk/hvqDe4qipy4IZhRAiY6FxTqvNe7Adwz6Oxgy',
    'remember_token' => NULL,
    'created_at' => '2026-09-18 18:44:45',
    'updated_at' => '2026-09-22 21:54:16',
    'phone' => NULL,
    'avatar' => NULL,
    'is_admin' => 1,
    'reports_against_count' => 2,
    'successful_returns_count' => 3,
  ),
  1 => 
  array (
    'id' => 2,
    'name' => 'dover',
    'email' => 'dover@gmail.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$FU9a8Nvq/sKtuweVphFThOonIC9Ilcwydbg/xbNiz4dVB8WeaXDce',
    'remember_token' => NULL,
    'created_at' => '2026-09-19 09:11:18',
    'updated_at' => '2026-09-19 09:16:57',
    'phone' => NULL,
    'avatar' => NULL,
    'is_admin' => 1,
    'reports_against_count' => 0,
    'successful_returns_count' => 0,
  ),
  2 => 
  array (
    'id' => 3,
    'name' => 'frank',
    'email' => 'frank@gmail.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$dPa2zODGplE4EGJeaJjRk.ak0IOZ9F4pnvqPw7Cnu9ZQ0188x7P4O',
    'remember_token' => NULL,
    'created_at' => '2026-09-19 13:42:13',
    'updated_at' => '2026-09-19 13:42:13',
    'phone' => NULL,
    'avatar' => NULL,
    'is_admin' => 0,
    'reports_against_count' => 0,
    'successful_returns_count' => 0,
  ),
  3 => 
  array (
    'id' => 4,
    'name' => 'granit',
    'email' => 'granit@gmail.com',
    'email_verified_at' => NULL,
    'password' => '$2y$12$g3qm8JYB1dw94Yq74wkw/uIuTd1F32I5KcxtK5vAmL.sQ5HAYo5fa',
    'remember_token' => NULL,
    'created_at' => '2026-10-07 13:47:08',
    'updated_at' => '2026-10-07 13:47:08',
    'phone' => NULL,
    'avatar' => NULL,
    'is_admin' => 0,
    'reports_against_count' => 0,
    'successful_returns_count' => 0,
  ),
));

        // items (10 records)
        DB::table('items')->insert(array (
  0 => 
  array (
    'id' => 1,
    'user_id' => 1,
    'category_id' => 6,
    'title' => 'watches',
    'description' => 'black leathwer watch',
    'type' => 'lost',
    'status' => 'claimed',
    'date_lost_or_found' => '2026-09-09 00:00:00',
    'location_name' => 'pti junction, warri',
    'latitude' => NULL,
    'longitude' => NULL,
    'primary_image' => 'items/N3WS7k4yBVS8rOYkZrfKEvA3T67ANM51WLfeOrKY.png',
    'contact_phone' => NULL,
    'contact_email' => 'creteboi23@gmail.com',
    'reward_amount' => NULL,
    'is_anonymous' => 0,
    'created_at' => '2026-09-19 08:41:17',
    'updated_at' => '2026-10-07 13:51:51',
  ),
  1 => 
  array (
    'id' => 2,
    'user_id' => 3,
    'category_id' => 4,
    'title' => 'code snippets docs',
    'description' => 'code docs one black, one white',
    'type' => 'lost',
    'status' => 'claimed',
    'date_lost_or_found' => '2026-09-16 00:00:00',
    'location_name' => 'pti junction, warri',
    'latitude' => NULL,
    'longitude' => NULL,
    'primary_image' => 'items/XvTdWH3zh9JtljMfbuj5WiAvkrKq2PFtJiAQGJw3.png',
    'contact_phone' => '07054678934',
    'contact_email' => 'frank@gmail.com',
    'reward_amount' => 5000,
    'is_anonymous' => 1,
    'created_at' => '2026-09-19 16:07:34',
    'updated_at' => '2026-10-07 13:50:13',
  ),
  2 => 
  array (
    'id' => 3,
    'user_id' => 3,
    'category_id' => 9,
    'title' => 'black nike ball',
    'description' => 'black nike ball',
    'type' => 'found',
    'status' => 'claimed',
    'date_lost_or_found' => '2026-09-10 00:00:00',
    'location_name' => 'pti junction, warri',
    'latitude' => 5.57408,
    'longitude' => 5.842967,
    'primary_image' => 'items/jNAWy8iIAHVPcr32sRd91T4LwKLG8CxZYjJHwVj3.png',
    'contact_phone' => NULL,
    'contact_email' => 'frank@gmail.com',
    'reward_amount' => NULL,
    'is_anonymous' => 0,
    'created_at' => '2026-09-20 18:20:24',
    'updated_at' => '2026-10-07 13:50:46',
  ),
  3 => 
  array (
    'id' => 4,
    'user_id' => 3,
    'category_id' => 3,
    'title' => 'silver keys',
    'description' => 'all silver design keys',
    'type' => 'lost',
    'status' => 'claimed',
    'date_lost_or_found' => '2026-09-16 00:00:00',
    'location_name' => 'akwa ibom',
    'latitude' => 5.574501,
    'longitude' => 5.842426,
    'primary_image' => 'items/Cp6w8pL2phtJnzYPLfPi8VbVPtAtdyJ04ggQh4nH.jpg',
    'contact_phone' => NULL,
    'contact_email' => 'frank@gmail.com',
    'reward_amount' => NULL,
    'is_anonymous' => 0,
    'created_at' => '2026-09-21 19:26:04',
    'updated_at' => '2026-09-22 22:52:14',
  ),
  4 => 
  array (
    'id' => 5,
    'user_id' => 3,
    'category_id' => 9,
    'title' => '2 tennis racket',
    'description' => 'tennis racket',
    'type' => 'found',
    'status' => 'claimed',
    'date_lost_or_found' => '2026-09-06 00:00:00',
    'location_name' => 'akwa ibom',
    'latitude' => 40.00949,
    'longitude' => -84.19963,
    'primary_image' => 'items/eDfDn6FegTeUqCAE7mlNSl7t5vm2xO1k9ckOKD3O.jpg',
    'contact_phone' => '09087654321',
    'contact_email' => 'frank@gmail.com',
    'reward_amount' => 6000,
    'is_anonymous' => 0,
    'created_at' => '2026-09-24 11:51:23',
    'updated_at' => '2026-09-24 11:55:20',
  ),
  5 => 
  array (
    'id' => 6,
    'user_id' => 1,
    'category_id' => 7,
    'title' => 'shoes',
    'description' => 'red nike shoes',
    'type' => 'found',
    'status' => 'claimed',
    'date_lost_or_found' => '2026-10-02 00:00:00',
    'location_name' => 'pti junction',
    'latitude' => 5.574163,
    'longitude' => 5.842368,
    'primary_image' => 'items/jpqwEyDteHmRkx6CCKVXMJHLweIe0seq2SSGTFS4.bin',
    'contact_phone' => NULL,
    'contact_email' => 'creteboi23@gmail.com',
    'reward_amount' => NULL,
    'is_anonymous' => 0,
    'created_at' => '2026-10-07 15:09:26',
    'updated_at' => '2026-10-17 18:30:06',
  ),
  6 => 
  array (
    'id' => 8,
    'user_id' => 1,
    'category_id' => 7,
    'title' => 'leather bag',
    'description' => 'brown leather bag',
    'type' => 'lost',
    'status' => 'active',
    'date_lost_or_found' => '2026-10-02 00:00:00',
    'location_name' => 'pti junction warri',
    'latitude' => 5.574163,
    'longitude' => 5.842368,
    'primary_image' => 'items/nX9iAEGRTqmQTd0f7hmoMPrUxQYvrvsI8XYO9Lin.bin',
    'contact_phone' => NULL,
    'contact_email' => 'creteboi23@gmail.com',
    'reward_amount' => 9000,
    'is_anonymous' => 0,
    'created_at' => '2026-10-07 15:28:19',
    'updated_at' => '2026-10-17 18:27:59',
  ),
  7 => 
  array (
    'id' => 9,
    'user_id' => 1,
    'category_id' => 7,
    'title' => 'shoe',
    'description' => 'lemon nike show',
    'type' => 'found',
    'status' => 'active',
    'date_lost_or_found' => '2026-10-10 00:00:00',
    'location_name' => 'pti junction warri',
    'latitude' => 5.568061,
    'longitude' => 5.83993,
    'primary_image' => 'items/TxIMiXND4nlYWoySCQUhatuns2nijhLZmHsCVI2v.bin',
    'contact_phone' => NULL,
    'contact_email' => 'creteboi23@gmail.com',
    'reward_amount' => NULL,
    'is_anonymous' => 0,
    'created_at' => '2026-10-13 07:09:18',
    'updated_at' => '2026-10-13 07:09:18',
  ),
  8 => 
  array (
    'id' => 10,
    'user_id' => 3,
    'category_id' => 4,
    'title' => 'comic book',
    'description' => 'huge comic book with iron man cover',
    'type' => 'lost',
    'status' => 'active',
    'date_lost_or_found' => '2026-10-02 00:00:00',
    'location_name' => 'pti junction',
    'latitude' => 5.574163,
    'longitude' => 5.842368,
    'primary_image' => 'items/noZznUs3gnzWmisF3GKI9KN8WKeXAsS5vVUnbMFQ.bin',
    'contact_phone' => NULL,
    'contact_email' => 'frank@gmail.com',
    'reward_amount' => NULL,
    'is_anonymous' => 0,
    'created_at' => '2026-10-17 18:34:10',
    'updated_at' => '2026-10-17 18:34:10',
  ),
  9 => 
  array (
    'id' => 11,
    'user_id' => 3,
    'category_id' => 1,
    'title' => 'samsung galxy phone',
    'description' => 'blue samsung galaxy s20',
    'type' => 'lost',
    'status' => 'active',
    'date_lost_or_found' => '2026-10-06 00:00:00',
    'location_name' => 'iterigbi street, effurun',
    'latitude' => NULL,
    'longitude' => NULL,
    'primary_image' => 'items/OV3TQimMZEzZWTU6vwSEqCsrbJuKnYQ5euGvDXhh.bin',
    'contact_phone' => NULL,
    'contact_email' => 'frank@gmail.com',
    'reward_amount' => 10000,
    'is_anonymous' => 0,
    'created_at' => '2026-10-18 18:55:42',
    'updated_at' => '2026-10-18 18:55:42',
  ),
));

        // item_images (2 records)
        DB::table('item_images')->insert(array (
  0 => 
  array (
    'id' => 1,
    'item_id' => 2,
    'image_path' => 'items/gallery/gG2hn5d8o4xqR5C1LSeopk6KTC6eYwDffjeJtbNe.png',
    'caption' => NULL,
    'is_primary' => 0,
    'created_at' => '2026-09-19 16:07:34',
    'updated_at' => '2026-09-19 16:07:34',
  ),
  1 => 
  array (
    'id' => 2,
    'item_id' => 4,
    'image_path' => 'items/gallery/wKIhx28Ze9qZLIUSoumFhMRXNMb2lnlXbZwYDOcd.jpg',
    'caption' => NULL,
    'is_primary' => 0,
    'created_at' => '2026-09-22 20:58:08',
    'updated_at' => '2026-09-22 20:58:08',
  ),
));

        // claims (8 records)
        DB::table('claims')->insert(array (
  0 => 
  array (
    'id' => 1,
    'item_id' => 1,
    'user_id' => 3,
    'proof_description' => 'the image matched wat i have been looking for, its 3 files with notable red lines',
    'proof_image' => NULL,
    'status' => 'cancelled',
    'contact_phone' => '07054678934',
    'contact_email' => 'frank@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-10-07 13:30:34',
    'created_at' => '2026-09-22 21:08:33',
    'updated_at' => '2026-10-07 13:30:34',
  ),
  1 => 
  array (
    'id' => 2,
    'item_id' => 4,
    'user_id' => 1,
    'proof_description' => 'it mine that a lech key i lost it a week ago',
    'proof_image' => NULL,
    'status' => 'approved',
    'contact_phone' => '09087654321',
    'contact_email' => 'creteboi23@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-09-22 22:52:14',
    'created_at' => '2026-09-22 22:51:04',
    'updated_at' => '2026-09-22 22:52:14',
  ),
  2 => 
  array (
    'id' => 3,
    'item_id' => 5,
    'user_id' => 1,
    'proof_description' => 'its exactly the same tennis racket am looking for',
    'proof_image' => NULL,
    'status' => 'approved',
    'contact_phone' => '09087654321',
    'contact_email' => 'creteboi23@gmail.com',
    'response_notes' => 'alright call me for meetup location',
    'resolved_at' => '2026-09-24 11:55:20',
    'created_at' => '2026-09-24 11:54:19',
    'updated_at' => '2026-09-24 11:55:20',
  ),
  3 => 
  array (
    'id' => 4,
    'item_id' => 3,
    'user_id' => 4,
    'proof_description' => 'rutcndndmesms,',
    'proof_image' => NULL,
    'status' => 'approved',
    'contact_phone' => '08066778844',
    'contact_email' => 'granit@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-10-07 13:50:46',
    'created_at' => '2026-10-07 13:47:53',
    'updated_at' => '2026-10-07 13:50:46',
  ),
  4 => 
  array (
    'id' => 5,
    'item_id' => 2,
    'user_id' => 4,
    'proof_description' => 'dgejwamzmd,e,',
    'proof_image' => NULL,
    'status' => 'approved',
    'contact_phone' => '08066778845',
    'contact_email' => 'granit@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-10-07 13:50:13',
    'created_at' => '2026-10-07 13:48:51',
    'updated_at' => '2026-10-07 13:50:13',
  ),
  5 => 
  array (
    'id' => 6,
    'item_id' => 1,
    'user_id' => 4,
    'proof_description' => 'ccddss2wrfeegg',
    'proof_image' => NULL,
    'status' => 'approved',
    'contact_phone' => '0907885533',
    'contact_email' => 'granit@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-10-07 13:51:51',
    'created_at' => '2026-10-07 13:49:13',
    'updated_at' => '2026-10-07 13:51:51',
  ),
  6 => 
  array (
    'id' => 7,
    'item_id' => 8,
    'user_id' => 3,
    'proof_description' => 'it tore at the buttom',
    'proof_image' => NULL,
    'status' => 'cancelled',
    'contact_phone' => '08066778845',
    'contact_email' => 'frank@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-10-17 18:27:59',
    'created_at' => '2026-10-07 15:30:07',
    'updated_at' => '2026-10-17 18:27:59',
  ),
  7 => 
  array (
    'id' => 8,
    'item_id' => 6,
    'user_id' => 3,
    'proof_description' => 'nikkkeeeeddssd',
    'proof_image' => NULL,
    'status' => 'approved',
    'contact_phone' => NULL,
    'contact_email' => 'frank@gmail.com',
    'response_notes' => NULL,
    'resolved_at' => '2026-10-17 18:30:06',
    'created_at' => '2026-10-17 18:29:18',
    'updated_at' => '2026-10-17 18:30:06',
  ),
));

        // verification_questions (2 records)
        DB::table('verification_questions')->insert(array (
  0 => 
  array (
    'id' => 1,
    'created_at' => '2026-10-07 15:09:26',
    'updated_at' => '2026-10-07 15:09:26',
    'item_id' => 6,
    'question' => 'what size is this shoe',
  ),
  1 => 
  array (
    'id' => 2,
    'created_at' => '2026-10-13 07:09:18',
    'updated_at' => '2026-10-13 07:09:18',
    'item_id' => 9,
    'question' => 'what size is this shoe',
  ),
));

        // claim_verification_answers (1 records)
        DB::table('claim_verification_answers')->insert(array (
  0 => 
  array (
    'id' => 1,
    'created_at' => '2026-10-17 18:29:18',
    'updated_at' => '2026-10-17 18:29:18',
    'claim_id' => 8,
    'verification_question_id' => 1,
    'answer' => '56',
  ),
));

        // reports (4 records)
        DB::table('reports')->insert(array (
  0 => 
  array (
    'id' => 1,
    'reportable_type' => 'item',
    'reportable_id' => 1,
    'reporter_id' => 1,
    'type' => 'spam',
    'reason' => NULL,
    'status' => 'reviewed',
    'created_at' => '2026-09-19 08:58:06',
    'updated_at' => '2026-09-19 10:08:32',
    'is_valid' => 1,
  ),
  1 => 
  array (
    'id' => 2,
    'reportable_type' => 'item',
    'reportable_id' => 1,
    'reporter_id' => 3,
    'type' => 'spam',
    'reason' => NULL,
    'status' => 'reviewed',
    'created_at' => '2026-09-19 13:42:37',
    'updated_at' => '2026-09-19 13:48:11',
    'is_valid' => 1,
  ),
  2 => 
  array (
    'id' => 3,
    'reportable_type' => 'item',
    'reportable_id' => 1,
    'reporter_id' => 3,
    'type' => 'fake_item',
    'reason' => NULL,
    'status' => 'pending',
    'created_at' => '2026-09-19 15:38:47',
    'updated_at' => '2026-09-19 15:38:47',
    'is_valid' => NULL,
  ),
  3 => 
  array (
    'id' => 4,
    'reportable_type' => 'item',
    'reportable_id' => 8,
    'reporter_id' => 3,
    'type' => 'fake_item',
    'reason' => 'its fake',
    'status' => 'dismissed',
    'created_at' => '2026-10-17 18:28:18',
    'updated_at' => '2026-10-17 18:30:43',
    'is_valid' => NULL,
  ),
));

        // notifications (11 records)
        DB::table('notifications')->insert(array (
  0 => 
  array (
    'id' => 'fabb2a4a-6e45-41b8-bbe7-44a1c52c3773',
    'type' => 'App\\Notifications\\ClaimSubmittedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 3,
    'data' => '{"type":"claim_submitted","claim_id":3,"item_id":5,"item_title":"2 tennis racket","claimant_name":"eviano destiny","message":"eviano destiny submitted a claim on \\"2 tennis racket\\"","url":"https:\\/\\/my-project.test\\/items\\/5\\/claims"}',
    'read_at' => '2026-09-24 11:54:53',
    'created_at' => '2026-09-24 11:54:19',
    'updated_at' => '2026-09-24 11:54:53',
  ),
  1 => 
  array (
    'id' => '9d848b9b-d357-4042-b354-8d93e740b51e',
    'type' => 'App\\Notifications\\ClaimApprovedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 1,
    'data' => '{"type":"claim_approved","claim_id":3,"item_id":5,"item_title":"2 tennis racket","message":"Your claim on \\"2 tennis racket\\" was approved!","url":"https:\\/\\/my-project.test\\/items\\/5"}',
    'read_at' => '2026-09-24 12:43:54',
    'created_at' => '2026-09-24 11:55:20',
    'updated_at' => '2026-09-24 12:43:54',
  ),
  2 => 
  array (
    'id' => '6f108b4b-dc4c-485e-b375-6b57d7a623b2',
    'type' => 'App\\Notifications\\ClaimSubmittedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 3,
    'data' => '{"type":"claim_submitted","claim_id":4,"item_id":3,"item_title":"black nike ball","claimant_name":"granit","message":"granit submitted a claim on \\"black nike ball\\"","url":"https:\\/\\/my-project.test\\/items\\/3\\/claims"}',
    'read_at' => '2026-10-07 13:50:44',
    'created_at' => '2026-10-07 13:47:54',
    'updated_at' => '2026-10-07 13:50:44',
  ),
  3 => 
  array (
    'id' => '805bb2e4-b83a-4417-aeed-bddb315560f2',
    'type' => 'App\\Notifications\\ClaimSubmittedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 3,
    'data' => '{"type":"claim_submitted","claim_id":5,"item_id":2,"item_title":"code snippets docs","claimant_name":"granit","message":"granit submitted a claim on \\"code snippets docs\\"","url":"https:\\/\\/my-project.test\\/items\\/2\\/claims"}',
    'read_at' => '2026-10-07 13:49:42',
    'created_at' => '2026-10-07 13:48:51',
    'updated_at' => '2026-10-07 13:49:42',
  ),
  4 => 
  array (
    'id' => '0eed2b73-7dae-4f53-80ff-f7b6a6429f59',
    'type' => 'App\\Notifications\\ClaimSubmittedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 1,
    'data' => '{"type":"claim_submitted","claim_id":6,"item_id":1,"item_title":"watches","claimant_name":"granit","message":"granit submitted a claim on \\"watches\\"","url":"https:\\/\\/my-project.test\\/items\\/1\\/claims"}',
    'read_at' => '2026-10-07 13:51:48',
    'created_at' => '2026-10-07 13:49:13',
    'updated_at' => '2026-10-07 13:51:48',
  ),
  5 => 
  array (
    'id' => '00d34ffe-8cc7-4989-9cea-fc857f83deca',
    'type' => 'App\\Notifications\\ClaimApprovedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 4,
    'data' => '{"type":"claim_approved","claim_id":5,"item_id":2,"item_title":"code snippets docs","message":"Your claim on \\"code snippets docs\\" was approved!","url":"https:\\/\\/my-project.test\\/items\\/2"}',
    'read_at' => NULL,
    'created_at' => '2026-10-07 13:50:13',
    'updated_at' => '2026-10-07 13:50:13',
  ),
  6 => 
  array (
    'id' => '9fce0342-5816-47e2-95da-0d1b986a8cbe',
    'type' => 'App\\Notifications\\ClaimApprovedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 4,
    'data' => '{"type":"claim_approved","claim_id":4,"item_id":3,"item_title":"black nike ball","message":"Your claim on \\"black nike ball\\" was approved!","url":"https:\\/\\/my-project.test\\/items\\/3"}',
    'read_at' => NULL,
    'created_at' => '2026-10-07 13:50:46',
    'updated_at' => '2026-10-07 13:50:46',
  ),
  7 => 
  array (
    'id' => '0901ee08-798c-4793-8afc-7dbae29b7fcb',
    'type' => 'App\\Notifications\\ClaimApprovedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 4,
    'data' => '{"type":"claim_approved","claim_id":6,"item_id":1,"item_title":"watches","message":"Your claim on \\"watches\\" was approved!","url":"https:\\/\\/my-project.test\\/items\\/1"}',
    'read_at' => NULL,
    'created_at' => '2026-10-07 13:51:51',
    'updated_at' => '2026-10-07 13:51:51',
  ),
  8 => 
  array (
    'id' => '082ad828-420d-49ee-8d72-f00bb65cf651',
    'type' => 'App\\Notifications\\ClaimSubmittedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 1,
    'data' => '{"type":"claim_submitted","claim_id":7,"item_id":8,"item_title":"leather bag","claimant_name":"frank","message":"frank submitted a claim on \\"leather bag\\"","url":"https:\\/\\/my-project.test\\/items\\/8\\/claims"}',
    'read_at' => '2026-10-07 15:30:33',
    'created_at' => '2026-10-07 15:30:07',
    'updated_at' => '2026-10-07 15:30:33',
  ),
  9 => 
  array (
    'id' => '31db3c00-4717-4e65-af37-547027c9d662',
    'type' => 'App\\Notifications\\ClaimSubmittedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 1,
    'data' => '{"type":"claim_submitted","claim_id":8,"item_id":6,"item_title":"shoes","claimant_name":"frank","message":"frank submitted a claim on \\"shoes\\"","url":"https:\\/\\/my-project.test\\/items\\/6\\/claims"}',
    'read_at' => '2026-10-17 18:29:59',
    'created_at' => '2026-10-17 18:29:18',
    'updated_at' => '2026-10-17 18:29:59',
  ),
  10 => 
  array (
    'id' => '99d6166d-4b86-4df6-b905-55aca004a629',
    'type' => 'App\\Notifications\\ClaimApprovedNotification',
    'notifiable_type' => 'App\\Models\\User',
    'notifiable_id' => 3,
    'data' => '{"type":"claim_approved","claim_id":8,"item_id":6,"item_title":"shoes","message":"Your claim on \\"shoes\\" was approved!","url":"https:\\/\\/my-project.test\\/items\\/6"}',
    'read_at' => '2026-10-17 18:31:57',
    'created_at' => '2026-10-17 18:30:06',
    'updated_at' => '2026-10-17 18:31:57',
  ),
));

        if (DB::getDriverName() === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
