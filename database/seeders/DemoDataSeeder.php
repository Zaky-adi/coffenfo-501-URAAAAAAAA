<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new RuntimeException('DemoDataSeeder hanya boleh dijalankan di environment local/testing.');
        }

        DB::transaction(function (): void {
            $now = now();
            $admin = User::query()->where('email', 'admin@example.test')->firstOrFail();
            $owner = User::query()->where('email', 'owner@example.test')->firstOrFail();
            $user = User::query()->firstOrCreate(['email' => 'demo.user@example.test'], [
                'public_id' => (string) Str::uuid(), 'name' => 'Coffenfo Demo User', 'username' => 'demo_user',
                'password' => Hash::make('DemoUser123!'), 'role' => User::ROLE_USER, 'status' => 'active', 'email_verified_at' => $now,
            ]);

            foreach ([$admin, $owner, $user] as $account) {
                DB::table('user_preferences')->insertOrIgnore(['user_id' => $account->id]);
                DB::table('user_points')->insertOrIgnore(['user_id' => $account->id, 'balance' => 0]);
                DB::table('user_privacy_settings')->insertOrIgnore(['user_id' => $account->id]);
            }

            $areas = DB::table('areas')->where('is_active', true)->orderBy('id')->limit(2)->pluck('id')->all();
            if (count($areas) < 2) throw new RuntimeException('Jalankan seeder master data terlebih dahulu.');
            $cafes = [];
            foreach ([['Kopi Demo Batam', 'kopi-demo-batam'], ['Ruang Seduh Demo', 'ruang-seduh-demo']] as $index => [$name, $slug]) {
                $row = DB::table('cafes')->where('slug', $slug)->first();
                if (!$row) {
                    $id = DB::table('cafes')->insertGetId([
                        'public_id' => (string) Str::uuid(), 'owner_id' => $owner->id, 'name' => $name, 'slug' => $slug,
                        'tagline' => 'Kopi pilihan di Batam', 'description' => 'Cafe contoh untuk pengujian API Coffenfo.',
                        'coffee_note' => 'Arabika lokal, diseduh dengan teliti.', 'address' => $index === 0 ? 'Batam Center' : 'Nagoya',
                        'area_id' => $areas[$index], 'city' => 'Batam', 'latitude' => $index === 0 ? 1.0456000 : 1.1456000,
                        'longitude' => $index === 0 ? 104.0305000 : 104.0105000, 'phone' => '62777800000' . ($index + 1),
                        'price_range' => 'mid', 'price_range_new' => $index + 1, 'has_self_roasting' => $index === 0,
                        'accepts_orders' => true, 'avg_rating' => 0, 'review_count' => 0, 'status' => 'active',
                        'is_active' => true, 'created_at' => $now, 'updated_at' => $now,
                    ]);
                    $row = DB::table('cafes')->where('id', $id)->first();
                }
                $cafes[] = $row;
            }
            [$cafe, $cafe2] = $cafes;

            foreach ([[$cafe, 'Senin'], [$cafe2, 'Selasa']] as [$cafeRow, $caption]) {
                if (DB::table('opening_hours')->where('cafe_id', $cafeRow->id)->count() === 0) {
                    for ($day = 0; $day < 7; $day++) DB::table('opening_hours')->insert(['cafe_id' => $cafeRow->id, 'day' => $day, 'open_time' => '08:00:00', 'close_time' => '22:00:00', 'is_closed' => false, 'created_at' => $now, 'updated_at' => $now]);
                }
                if (DB::table('cafe_photos')->where('cafe_id', $cafeRow->id)->count() === 0) DB::table('cafe_photos')->insert(['cafe_id' => $cafeRow->id, 'path' => 'https://example.test/demo-cafe.jpg', 'caption' => $caption . ' - foto dummy', 'is_cover' => true, 'created_at' => $now, 'updated_at' => $now]);
            }

            $bean = DB::table('bean_types')->value('id'); $species = DB::table('coffee_species')->value('id');
            $roast = DB::table('roast_levels')->value('id'); $brew = DB::table('brew_methods')->value('id');
            $serve = DB::table('serving_methods')->value('id'); $facility = DB::table('facilities')->value('id');
            $category = DB::table('menu_categories')->value('id');
            foreach ([[$cafe, $bean, 'cafe_beans', 'bean_type_id'], [$cafe, $species, 'cafe_species', 'species_id'], [$cafe, $roast, 'cafe_roast_levels', 'roast_level_id'], [$cafe, $brew, 'cafe_brew_methods', 'brew_method_id'], [$cafe, $serve, 'cafe_serving_methods', 'serving_method_id'], [$cafe, $facility, 'cafe_facilities', 'facility_id']] as [$cafeRow, $masterId, $pivot, $foreign]) {
                if ($masterId && DB::table($pivot)->where('cafe_id', $cafeRow->id)->count() === 0) DB::table($pivot)->insert(['cafe_id' => $cafeRow->id, $foreign => $masterId]);
            }

            if (DB::table('cafe_social_links')->count() === 0) DB::table('cafe_social_links')->insert(['cafe_id' => $cafe->id, 'platform' => 'instagram', 'url' => 'https://example.test/coffe-demo', 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('cafe_hours_exceptions')->count() === 0) DB::table('cafe_hours_exceptions')->insert(['cafe_id' => $cafe->id, 'date' => $now->copy()->addDays(14)->toDateString(), 'open_time' => null, 'close_time' => null, 'is_closed' => true, 'reason' => 'Contoh hari libur', 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('featured_cafes')->count() === 0) DB::table('featured_cafes')->insert(['cafe_id' => $cafe->id, 'label' => 'Cafe pilihan dummy', 'starts_on' => $now->toDateString(), 'ends_on' => $now->copy()->addDays(7)->toDateString(), 'sort_order' => 1, 'created_by' => $admin->id, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('cafe_similarity')->count() === 0) {
                DB::table('cafe_similarity')->insert(['cafe_id' => $cafe->id, 'similar_cafe_id' => $cafe2->id, 'score' => 0.85000, 'method' => 'content', 'computed_at' => $now]);
            }

            $menu = DB::table('menu_items')->where('cafe_id', $cafe->id)->first();
            if (!$menu) {
                $menuId = DB::table('menu_items')->insertGetId(['public_id' => (string) Str::uuid(), 'cafe_id' => $cafe->id, 'category_id' => $category, 'name' => 'Kopi Susu Demo', 'description' => 'Menu contoh untuk pengujian.', 'price' => 25000, 'image_url' => null, 'is_available' => true, 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now]);
                $menu = DB::table('menu_items')->where('id', $menuId)->first();
            }
            if (DB::table('menus')->count() === 0) DB::table('menus')->insert(['cafe_id' => $cafe->id, 'menu_category_id' => $category, 'name' => 'Kopi Susu Demo Legacy', 'description' => 'Menu dummy tabel legacy.', 'price' => 25000, 'photo' => null, 'is_available' => true, 'meta' => null, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('menu_option_groups')->count() === 0) {
                $group = DB::table('menu_option_groups')->insertGetId(['menu_id' => $menu->id, 'name' => 'Ukuran', 'is_required' => true, 'max_select' => 1, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now]);
                DB::table('menu_options')->insert(['menu_option_group_id' => $group, 'name' => 'Regular', 'price_add' => 0, 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now]);
            }

            $visit = DB::table('visits')->where('user_id', $user->id)->where('cafe_id', $cafe->id)->first();
            if (!$visit) {
                $visitId = DB::table('visits')->insertGetId(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'checkin_latitude' => $cafe->latitude, 'checkin_longitude' => $cafe->longitude, 'visited_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
                $visit = DB::table('visits')->where('id', $visitId)->first();
            }
            if (DB::table('reviews')->count() === 0) {
                $reviewId = DB::table('reviews')->insertGetId(['public_id' => (string) Str::uuid(), 'user_id' => $user->id, 'cafe_id' => $cafe->id, 'visit_id' => $visit->id, 'rating' => 5, 'comment' => 'Kopi enak dan suasananya nyaman untuk bekerja.', 'status' => 'approved', 'has_photo' => true, 'is_featured' => true, 'helpful_count' => 0, 'is_anonymous' => false, 'created_at' => $now, 'updated_at' => $now]);
                DB::table('review_photos')->insert(['review_id' => $reviewId, 'path' => 'https://example.test/demo-review.jpg', 'caption' => 'Foto dummy', 'created_at' => $now, 'updated_at' => $now]);
                $review = DB::table('reviews')->where('id', $reviewId)->first();
            } else $review = DB::table('reviews')->first();
            if (DB::table('review_replies')->count() === 0) DB::table('review_replies')->insert(['review_id' => $review->id, 'user_id' => $owner->id, 'reply' => 'Terima kasih atas ulasannya!', 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('review_likes')->count() === 0) DB::table('review_likes')->insert(['review_id' => $review->id, 'user_id' => $admin->id, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('review_helpful_votes')->count() === 0 && $review->user_id !== $admin->id) DB::table('review_helpful_votes')->insert(['review_id' => $review->id, 'user_id' => $admin->id, 'created_at' => $now]);
            if (DB::table('review_reports')->count() === 0 && $review->user_id !== $owner->id) DB::table('review_reports')->insert(['review_id' => $review->id, 'reporter_user_id' => $owner->id, 'reason' => 'other', 'note' => 'Contoh laporan dummy', 'status' => 'dismissed', 'handled_by' => $admin->id, 'handled_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('cafes')->where('id', $cafe->id)->exists() && DB::getDriverName() === 'mysql') DB::statement('CALL sp_recalc_cafe_rating(?)', [$cafe->id]);

            if (DB::table('wishlists')->count() === 0) DB::table('wishlists')->insert(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'created_at' => $now]);
            if (DB::table('user_cafe_views')->count() === 0) DB::table('user_cafe_views')->insert(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'view_count' => 1, 'first_viewed_at' => $now, 'last_viewed_at' => $now]);
            if (DB::table('user_search_history')->count() === 0) DB::table('user_search_history')->insert(['user_id' => $user->id, 'keyword' => 'kopi batam', 'cafe_id' => $cafe->id, 'created_at' => $now]);
            if (DB::table('search_histories')->count() === 0) DB::table('search_histories')->insert(['user_id' => $user->id, 'keyword' => 'kopi batam', 'results_count' => 2, 'filters' => json_encode(['area_id' => $areas[0]]), 'cafe_id' => $cafe->id, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('user_search_clicks')->count() === 0) DB::table('user_search_clicks')->insert(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'keyword' => 'kopi batam', 'filters' => json_encode(['area_id' => $areas[0]]), 'clicked_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
            if (DB::table('user_events')->count() === 0) DB::table('user_events')->insert(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'event_type' => 'view', 'surface' => 'home', 'position' => 1, 'rec_request_id' => (string) Str::uuid(), 'model_version' => 'v1-content', 'created_at' => $now]);
            if (DB::table('user_taste_weights')->count() === 0) DB::table('user_taste_weights')->insert(['user_id' => $user->id, 'feature_type' => 'brew_method', 'feature_key' => (string) $brew, 'weight' => 0.7500, 'evidence_count' => 1, 'updated_at' => $now]);
            if (DB::table('user_cafe_scores')->count() === 0) DB::table('user_cafe_scores')->insert(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'score' => 0.85000, 'reason_code' => 'matches_taste', 'reason_ref' => 'brew_method', 'model_version' => 'v1-content', 'computed_at' => $now]);
            if (DB::table('user_hidden_cafes')->count() === 0) DB::table('user_hidden_cafes')->insert(['user_id' => $user->id, 'cafe_id' => $cafe2->id, 'reason' => 'not_interested', 'created_at' => $now]);
            if (DB::table('user_locations')->count() === 0) DB::table('user_locations')->insert(['user_id' => $user->id, 'latitude' => 1.046, 'longitude' => 104.031, 'area_id' => $areas[0], 'accuracy_m' => 50, 'updated_at' => $now]);
            DB::table('user_privacy_settings')->where('user_id', $user->id)->update(['personalization_enabled' => true, 'location_enabled' => true, 'consent_version' => 'demo-v1', 'consented_at' => $now]);

            if (DB::table('vouchers')->count() === 0) {
                $voucherId = DB::table('vouchers')->insertGetId(['cafe_id' => $cafe->id, 'title' => 'Diskon Demo', 'description' => 'Voucher dummy untuk uji tukar.', 'points_cost' => 100, 'discount_type' => 'fixed', 'discount_value' => 10000, 'min_purchase' => 20000, 'quota' => 20, 'redeemed_count' => 1, 'valid_from' => $now->toDateString(), 'valid_until' => $now->copy()->addMonth()->toDateString(), 'max_discount' => 10000, 'per_user_limit' => 1, 'is_active' => true, 'terms' => 'Data dummy.', 'created_at' => $now, 'updated_at' => $now]);
            } else $voucherId = DB::table('vouchers')->value('id');
            if (DB::table('voucher_redemptions')->count() === 0) {
                $redemptionId = DB::table('voucher_redemptions')->insertGetId(['user_id' => $user->id, 'voucher_id' => $voucherId, 'code' => 'DEMO' . strtoupper(Str::random(10)), 'status' => 'active', 'redeemed_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
                DB::table('user_points')->where('user_id', $user->id)->update(['balance' => 900, 'lifetime_earned' => 1000, 'updated_at' => $now]);
                DB::table('points_ledger')->insertOrIgnore([
                    ['user_id' => $user->id, 'entry_type' => 'earn', 'points' => 1000, 'balance_after' => 1000, 'source_type' => 'admin', 'source_id' => $admin->id, 'idempotency_key' => 'demo-points-earn-' . $user->id, 'note' => 'Saldo awal dummy', 'created_at' => $now],
                    ['user_id' => $user->id, 'entry_type' => 'redeem', 'points' => -100, 'balance_after' => 900, 'source_type' => 'voucher', 'source_id' => $redemptionId, 'idempotency_key' => 'demo-points-redeem-' . $user->id, 'note' => 'Tukar voucher dummy', 'created_at' => $now],
                ]);
            }
            if (DB::table('point_transactions')->count() === 0) DB::table('point_transactions')->insert(['user_id' => $user->id, 'points' => 1000, 'type' => 'adjustment', 'reference_type' => 'DemoSeeder', 'reference_id' => null, 'balance_after' => 1000, 'note' => 'Data poin dummy', 'created_at' => $now, 'updated_at' => $now]);

            if (DB::table('carts')->count() === 0) {
                $cartId = DB::table('carts')->insertGetId(['user_id' => $user->id, 'cafe_id' => $cafe->id, 'updated_at' => $now]);
                DB::table('cart_items')->insert(['cart_id' => $cartId, 'menu_item_id' => $menu->id, 'quantity' => 1, 'note' => 'Contoh keranjang']);
            }
            if (DB::table('orders')->count() === 0) {
                $orderId = DB::table('orders')->insertGetId(['public_id' => (string) Str::uuid(), 'order_code' => 'DEMO' . strtoupper(Str::random(10)), 'user_id' => $user->id, 'cafe_id' => $cafe->id, 'order_type' => 'takeaway', 'customer_note' => 'Pesanan dummy', 'status' => 'completed', 'subtotal' => 25000, 'service_fee' => 2500, 'discount_amount' => 0, 'total_amount' => 27500, 'est_prep_min' => 10, 'est_prep_max' => 15, 'paid_at' => $now, 'accepted_at' => $now, 'completed_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
                DB::table('order_items')->insert(['order_id' => $orderId, 'menu_item_id' => $menu->id, 'item_name' => $menu->name, 'unit_price' => 25000, 'quantity' => 1, 'line_total' => 25000]);
                DB::table('order_status_history')->insert(['order_id' => $orderId, 'from_status' => null, 'to_status' => 'completed', 'changed_by' => $owner->id, 'note' => 'Status dummy', 'created_at' => $now]);
                DB::table('payments')->insert(['public_id' => (string) Str::uuid(), 'order_id' => $orderId, 'method' => 'ewallet', 'channel' => 'gopay', 'provider' => 'demo', 'provider_ref' => 'DEMO-' . Str::uuid(), 'amount' => 27500, 'status' => 'paid', 'expires_at' => $now->copy()->addHour(), 'paid_at' => $now, 'callback_payload' => json_encode(['demo' => true]), 'created_at' => $now, 'updated_at' => $now]);
            }

            if (DB::table('notifications')->count() === 0) {
                $notificationId = DB::table('notifications')->insertGetId(['user_id' => $user->id, 'type' => 'system.demo', 'category' => 'system', 'title' => 'Notifikasi dummy', 'body' => 'Ini contoh notifikasi Coffenfo.', 'deep_link' => '/cafes/' . $cafe->public_id, 'data' => json_encode(['demo' => true]), 'created_at' => $now]);
                $deviceId = DB::table('user_devices')->where('user_id', $user->id)->value('id');
                if (!$deviceId) $deviceId = DB::table('user_devices')->insertGetId(['user_id' => $user->id, 'device_id' => 'demo-device-android', 'platform' => 'android', 'device_name' => 'Demo Android', 'app_version' => '1.0.0', 'locale' => 'id', 'push_token' => 'demo-token-' . Str::uuid(), 'push_enabled' => true, 'last_seen_at' => $now, 'created_at' => $now, 'updated_at' => $now]);
                DB::table('push_outbox')->insert(['notification_id' => $notificationId, 'user_device_id' => $deviceId, 'status' => 'skipped', 'attempts' => 0, 'last_error' => 'Dummy outbox; tidak dikirim.', 'scheduled_at' => $now, 'created_at' => $now]);
            }
            if (DB::table('notification_preferences')->count() === 0) {
                foreach (['review', 'points', 'order', 'recommendation', 'promo', 'system'] as $notificationCategory) DB::table('notification_preferences')->insert(['user_id' => $user->id, 'category' => $notificationCategory, 'push_enabled' => in_array($notificationCategory, ['review', 'points', 'order', 'system'], true), 'inapp_enabled' => true, 'updated_at' => $now]);
            }
            if (DB::table('user_device_tokens')->count() === 0) DB::table('user_device_tokens')->insert(['user_id' => $user->id, 'token' => 'demo-legacy-token-' . Str::uuid(), 'device_type' => 'android', 'last_used_at' => $now, 'created_at' => $now, 'updated_at' => $now]);

            if (DB::table('uploads')->count() === 0) DB::table('uploads')->insert(['public_id' => (string) Str::uuid(), 'user_id' => $owner->id, 'purpose' => 'cafe_photo', 'object_key' => 'demo/cafe-cover.jpg', 'content_type' => 'image/jpeg', 'declared_size' => 1000, 'size_bytes' => 1000, 'width' => 800, 'height' => 600, 'status' => 'ready', 'url' => 'https://example.test/demo-cafe.jpg', 'expires_at' => $now->copy()->addYear(), 'created_at' => $now, 'confirmed_at' => $now]);
            if (DB::table('partnership_requests')->count() === 0) DB::table('partnership_requests')->insert(['public_id' => (string) Str::uuid(), 'idempotency_key' => 'demo-partnership-' . Str::uuid(), 'cafe_name' => 'Cafe Mitra Dummy', 'owner_name' => 'Pemilik Dummy', 'whatsapp' => '6281234567890', 'area_id' => $areas[0], 'status' => 'new', 'ip_address' => '127.0.0.1', 'created_at' => $now, 'updated_at' => $now]);

            if (!DB::table('audit_logs')->where('action', 'demo.seed')->where('entity_id', $user->public_id)->exists()) {
                DB::table('audit_logs')->insert(['actor_user_id' => $admin->id, 'actor_role' => 'admin', 'action' => 'demo.seed', 'entity_type' => 'demo_data', 'entity_id' => $user->public_id, 'ip_address' => '127.0.0.1', 'user_agent' => 'DemoDataSeeder', 'metadata' => json_encode(['note' => 'Data dummy untuk pengujian lokal']), 'created_at' => $now]);
            }
        });
    }
}
