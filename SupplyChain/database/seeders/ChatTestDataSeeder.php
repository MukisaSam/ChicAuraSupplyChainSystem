<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Hash;

class ChatTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create test admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@chicaura.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Get or create test manufacturer
        $manufacturer = User::firstOrCreate(
            ['email' => 'manufacturer@chicaura.com'],
            [
                'name' => 'Fashion Manufacturer',
                'password' => Hash::make('password'),
                'role' => 'manufacturer',
            ]
        );

        // Create manufacturer profile if it doesn't exist
        if (!$manufacturer->manufacturer) {
            Manufacturer::create([
                'user_id' => $manufacturer->id,
                'business_address' => '123 Manufacturing St, Fashion District',
                'phone' => '+1234567890',
                'license_document' => 'manufacturer_license.pdf',
                'production_capacity' => 10000,
                'specialization' => json_encode(['dresses', 'shirts', 'pants']),
            ]);
        }

        // Get or create test wholesaler
        $wholesaler = User::firstOrCreate(
            ['email' => 'wholesaler@chicaura.com'],
            [
                'name' => 'Wholesale Retailer',
                'password' => Hash::make('password'),
                'role' => 'wholesaler',
            ]
        );

        // Create wholesaler profile if it doesn't exist
        if (!$wholesaler->wholesaler) {
            Wholesaler::create([
                'user_id' => $wholesaler->id,
                'business_address' => '456 Retail Ave, Shopping District',
                'phone' => '+0987654321',
                'license_document' => 'wholesaler_license.pdf',
                'business_type' => 'Fashion Retail',
                'preferred_categories' => json_encode(['dresses', 'shirts']),
                'monthly_order_volume' => 500,
            ]);
        }

        // Only create chat messages if they don't exist
        if (ChatMessage::count() == 0) {
            // Create sample chat messages
            $messages = [
                [
                    'sender_id' => $manufacturer->id,
                    'receiver_id' => $wholesaler->id,
                    'content' => 'Hello! We have new summer collection ready. Would you like to see the catalog?',
                    'message_type' => 'text',
                    'created_at' => now()->subHours(2),
                ],
                [
                    'sender_id' => $wholesaler->id,
                    'receiver_id' => $manufacturer->id,
                    'content' => 'Hi! Yes, I would love to see the summer collection. Can you send me the details?',
                    'message_type' => 'text',
                    'created_at' => now()->subHours(1),
                ],
                [
                    'sender_id' => $manufacturer->id,
                    'receiver_id' => $wholesaler->id,
                    'content' => 'Great! I\'ll send you the catalog with pricing. The collection includes 50 new designs.',
                    'message_type' => 'text',
                    'created_at' => now()->subMinutes(30),
                ],
                [
                    'sender_id' => $admin->id,
                    'receiver_id' => $wholesaler->id,
                    'content' => 'Welcome to ChicAura! How can I help you with your orders today?',
                    'message_type' => 'text',
                    'created_at' => now()->subDays(1),
                ],
                [
                    'sender_id' => $wholesaler->id,
                    'receiver_id' => $admin->id,
                    'content' => 'Thank you! I have a question about the order tracking system.',
                    'message_type' => 'text',
                    'created_at' => now()->subDays(1)->addMinutes(30),
                ],
            ];

            foreach ($messages as $messageData) {
                ChatMessage::create($messageData);
            }
        }

        $this->command->info('Chat test data seeded successfully!');
        $this->command->info('Test accounts:');
        $this->command->info('- Admin: admin@chicaura.com / password');
        $this->command->info('- Manufacturer: manufacturer@chicaura.com / password');
        $this->command->info('- Wholesaler: wholesaler@chicaura.com / password');
    }
}
