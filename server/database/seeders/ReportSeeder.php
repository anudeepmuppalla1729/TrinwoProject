<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PostReport;
use App\Models\QuestionReport;
use App\Models\AnswerReport;
use App\Models\User;
use App\Models\Post;
use App\Models\Question;
use App\Models\Answer;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        // Get some users for reporting
        $users = User::take(5)->get();
        $posts = Post::take(3)->get();
        $questions = Question::take(3)->get();
        $answers = Answer::take(3)->get();

        if ($users->isEmpty() || $posts->isEmpty() || $questions->isEmpty() || $answers->isEmpty()) {
            $this->command->info('Skipping report seeding - not enough data available');
            return;
        }

        $statuses = ['pending', 'review', 'resolved', 'dismissed'];
        $reasons = [
            'Inappropriate content',
            'Spam or misleading information',
            'Offensive language',
            'Duplicate content',
            'Violates community guidelines',
            'Incorrect information',
            'Promotional content'
        ];

        // Create post reports
        foreach ($posts as $post) {
            PostReport::create([
                'reporter_id' => $users->random()->user_id,
                'post_id' => $post->post_id,
                'reason' => $reasons[array_rand($reasons)],
                'status' => $statuses[array_rand($statuses)]
            ]);
        }

        // Create question reports
        foreach ($questions as $question) {
            QuestionReport::create([
                'reporter_id' => $users->random()->user_id,
                'question_id' => $question->question_id,
                'reason' => $reasons[array_rand($reasons)],
                'status' => $statuses[array_rand($statuses)]
            ]);
        }

        // Create answer reports
        foreach ($answers as $answer) {
            AnswerReport::create([
                'reporter_id' => $users->random()->user_id,
                'answer_id' => $answer->answer_id,
                'reason' => $reasons[array_rand($reasons)],
                'status' => $statuses[array_rand($statuses)]
            ]);
        }

        $this->command->info('Sample reports created successfully!');
    }
} 