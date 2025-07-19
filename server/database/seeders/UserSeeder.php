<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper to generate a random date within the last 30 days
        function randomLastLoginAt() {
            return now()->subDays(rand(0, 30))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
        }

        // Create admin users
        User::create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@qaforum.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'bio' => 'System administrator with full access to all features.',
            'avatar' => 'https://i.pravatar.cc/150?img=1',
            'created_at' => now()->subMonths(6),
            'last_login_at' => randomLastLoginAt(),
        ]);

        // Create regular users with different statuses
        $regularUsers = [
            [
                'name' => 'User One',
                'username' => 'userone',
                'email' => 'userone@qaforum.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Active user.',
                'avatar' => 'https://i.pravatar.cc/150?img=3',
                'created_at' => now()->subMonths(2),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'User Two',
                'username' => 'usertwo',
                'email' => 'usertwo@qaforum.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Inactive user.',
                'avatar' => 'https://i.pravatar.cc/150?img=4',
                'created_at' => now()->subMonths(1),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Mike Johnson',
                'username' => 'mikejohnson',
                'email' => 'mike@example.com',
                'role' => 'user',
                'status' => 'inactive',
                'bio' => 'Database administrator and SQL expert.',
                'avatar' => 'https://i.pravatar.cc/150?img=5',
                'created_at' => now()->subMonths(5),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Sarah Wilson',
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'username' => 'sarahwilson',
                'email' => 'sarah@example.com',
                'role' => 'user',
                'status' => 'banned',
                'bio' => 'UI/UX designer focused on user experience.',
                'avatar' => 'https://i.pravatar.cc/150?img=6',
                'created_at' => now()->subMonths(1),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'David Brown',
                'first_name' => 'David',
                'last_name' => 'Brown',
                'username' => 'davidbrown',
                'email' => 'david@example.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'DevOps engineer and cloud specialist.',
                'avatar' => 'https://i.pravatar.cc/150?img=7',
                'created_at' => now()->subWeeks(2),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Emily Davis',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'username' => 'emilydavis',
                'email' => 'emily@example.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Frontend developer specializing in Vue.js and modern CSS.',
                'avatar' => 'https://i.pravatar.cc/150?img=8',
                'created_at' => now()->subWeeks(1),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Alex Thompson',
                'first_name' => 'Alex',
                'last_name' => 'Thompson',
                'username' => 'alexthompson',
                'email' => 'alex@example.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Mobile app developer with React Native experience.',
                'avatar' => 'https://i.pravatar.cc/150?img=9',
                'created_at' => now()->subDays(5),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Lisa Garcia',
                'first_name' => 'Lisa',
                'last_name' => 'Garcia',
                'username' => 'lisagarcia',
                'email' => 'lisa@example.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Backend developer focused on API design and microservices.',
                'avatar' => 'https://i.pravatar.cc/150?img=10',
                'created_at' => now()->subDays(3),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Tom Anderson',
                'first_name' => 'Tom',
                'last_name' => 'Anderson',
                'username' => 'tomanderson',
                'email' => 'tom@example.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Security researcher and penetration tester.',
                'avatar' => 'https://i.pravatar.cc/150?img=11',
                'created_at' => now()->subDays(1),
                'last_login_at' => randomLastLoginAt(),
            ],
            [
                'name' => 'Rachel Green',
                'first_name' => 'Rachel',
                'last_name' => 'Green',
                'username' => 'rachelgreen',
                'email' => 'rachel@example.com',
                'role' => 'user',
                'status' => 'active',
                'bio' => 'Data scientist and machine learning enthusiast.',
                'avatar' => 'https://i.pravatar.cc/150?img=12',
                'created_at' => now()->subHours(12),
                'last_login_at' => randomLastLoginAt(),
            ],
        ];

        foreach ($regularUsers as $userData) {
            User::create($userData);
        }

        // Create some content for users to demonstrate the admin functionality
        $this->createUserContent();
    }

    private function createUserContent()
    {
        $users = User::where('role', 'user')->get();

        // Create questions for users
        foreach ($users as $user) {
            $questionCount = rand(0, 3);
            for ($i = 0; $i < $questionCount; $i++) {
                Question::create([
                    'user_id' => $user->user_id,
                    'title' => 'Sample Question ' . ($i + 1) . ' by ' . $user->first_name,
                    'description' => 'This is a sample question content created for testing the admin user management functionality.',
                    'created_at' => $user->created_at->addDays(rand(1, 30)),
                ]);
            }
        }

        // Create answers for some questions
        $questions = Question::all();
        foreach ($questions as $question) {
            $answerCount = rand(0, 2);
            for ($i = 0; $i < $answerCount; $i++) {
                $randomUser = $users->random();
                Answer::create([
                    'user_id' => $randomUser->user_id,
                    'question_id' => $question->question_id,
                    'content' => 'This is a sample answer to the question by ' . $randomUser->first_name,
                    'created_at' => $question->created_at->addDays(rand(1, 7)),
                ]);
            }
        }

        // Create posts for some users
        foreach ($users as $user) {
            $postCount = rand(0, 2);
            for ($i = 0; $i < $postCount; $i++) {
                Post::create([
                    'user_id' => $user->user_id,
                    'heading' => 'Sample Post ' . ($i + 1) . ' by ' . $user->first_name,
                    'details' => 'This is a sample post content created for testing the admin user management functionality.',
                    'created_at' => $user->created_at->addDays(rand(1, 20)),
                ]);
            }
        }
    }
}
