<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Manufacturer;
use App\Models\Wholesaler;
use App\Models\Supplier;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Hash;

class ChatTestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch users created by TestUsersSeeder
        $admin = User::where('email', 'admin@chicaura.com')->first();
        $manufacturer = User::where('email', 'manufacturer@chicaura.com')->first();
        $supplier = User::where('email', 'supplier@chicaura.com')->first();
        $wholesaler = User::where('email', 'wholesaler@chicaura.com')->first();

        // Only create chat messages if users exist
        if ($admin && $manufacturer && $supplier && $wholesaler) {
            // Always seed messages (truncate first for idempotency)
            ChatMessage::truncate();
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
                    'sender_id' => $manufacturer->id,
                    'receiver_id' => $supplier->id,
                    'content' => 'Hi! We need 5000 meters of premium cotton fabric. What\'s your best price?',
                    'message_type' => 'text',
                    'created_at' => now()->subHours(3),
                ],
                [
                    'sender_id' => $supplier->id,
                    'receiver_id' => $manufacturer->id,
                    'content' => 'Hello! For that quantity, I can offer $2.50 per meter. Delivery within 2 weeks.',
                    'message_type' => 'text',
                    'created_at' => now()->subHours(2),
                ],
                [
                    'sender_id' => $manufacturer->id,
                    'receiver_id' => $supplier->id,
                    'content' => 'Perfect! Can you also provide silk fabric for our luxury line?',
                    'message_type' => 'text',
                    'created_at' => now()->subMinutes(45),
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
                [
                    'sender_id' => $wholesaler->id,
                    'receiver_id' => $manufacturer->id,
                    'content' => 'Can you provide a discount if I order 1000 units of the new designs?',
                    'message_type' => 'text',
                    'created_at' => now()->subMinutes(25),
                ],
                [
                    'sender_id' => $manufacturer->id,
                    'receiver_id' => $wholesaler->id,
                    'content' => 'Yes, for 1000 units, we can offer a 10% discount and free shipping.',
                    'message_type' => 'text',
                    'created_at' => now()->subMinutes(20),
                ],
                [
                    'sender_id' => $wholesaler->id,
                    'receiver_id' => $manufacturer->id,
                    'content' => 'That sounds great! Please send me the invoice and expected delivery date.',
                    'message_type' => 'text',
                    'created_at' => now()->subMinutes(15),
                ],
                [
                    'sender_id' => $manufacturer->id,
                    'receiver_id' => $wholesaler->id,
                    'content' => 'Invoice sent! Delivery is expected within 10 days.',
                    'message_type' => 'text',
                    'created_at' => now()->subMinutes(10),
                ],
            ];
            foreach ($messages as $messageData) {
                ChatMessage::create($messageData);
            }
        }

        $this->command->info('Chat test data seeded successfully!');
        $this->command->info('Test accounts:');
        $this->command->info('- Admin: admin@chicaura.com / password123');
        $this->command->info('- Manufacturer: manufacturer@chicaura.com / password123');
        $this->command->info('- Supplier: supplier@chicaura.com / password123');
        $this->command->info('- Wholesaler: wholesaler@chicaura.com / password123');
    }
}
