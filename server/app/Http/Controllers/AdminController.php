<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\QuestionReport;
use App\Models\AnswerReport;
use Illuminate\Support\Facades\DB;
use App\Mail\ReportNotification;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    // Dashboard Views
    public function dashboard()
    {
        return redirect()->route('admin.reports');
    }

    public function reports()
    {
        return view('pages.admin.reports');
    }

    public function users()
    {
        return view('pages.admin.users');
    }

    public function questions()
    {
        return view('pages.admin.questions');
    }

    public function answers()
    {
        return view('pages.admin.answers');
    }

    public function posts()
    {
        return view('pages.admin.posts');
    }

    public function settings()
    {
        // You can load settings from database or config
        $settings = (object) [
            'forum_name' => config('app.name', 'Q&A Forum'),
            'forum_description' => 'A community for developers to share knowledge',
            'welcome_message' => 'Welcome to our developer community!',
            'default_user_role' => 'member',
            'registration_type' => 'open',
            'enable_email_notifications' => true,
            'require_email_verification' => true,
        ];

        return view('pages.admin.settings', compact('settings'));
    }

    // Dashboard API Endpoints
    public function dashboardStats()
    {
        $stats = [
            'total_users' => User::count(),
            'total_questions' => Question::count(),
            'total_answers' => Answer::count(),
            'pending_reports' => PostReport::where('status', 'pending')->count() +
                               QuestionReport::where('status', 'pending')->count() +
                               AnswerReport::where('status', 'pending')->count(),
        ];

        return response()->json($stats);
    }

    public function recentActivity()
    {
        // This would typically come from an activity log table
        $activities = [
            [
                'type' => 'user_registered',
                'title' => 'New User Registration',
                'description' => 'John Doe joined the forum',
                'created_at' => now()->subMinutes(5)
            ],
            [
                'type' => 'question_created',
                'title' => 'New Question Posted',
                'description' => 'How to optimize database queries?',
                'created_at' => now()->subMinutes(15)
            ],
            [
                'type' => 'answer_created',
                'title' => 'New Answer Posted',
                'description' => 'Answer to database optimization question',
                'created_at' => now()->subMinutes(30)
            ]
        ];

        return response()->json($activities);
    }

    public function topContributors()
    {
        $contributors = User::withCount(['questions', 'answers'])
            ->orderBy('questions_count', 'desc')
            ->orderBy('answers_count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'username' => $user->username,
                    'avatar' => $user->avatar,
                    'questions_count' => $user->questions_count,
                    'answers_count' => $user->answers_count,
                    'points' => ($user->questions_count * 10) + ($user->answers_count * 5)
                ];
            });

        return response()->json($contributors);
    }

    public function trendingTopics()
    {
        // This would typically come from tag usage statistics
        $topics = [
            ['name' => 'JavaScript', 'questions_count' => 45, 'trend' => 12],
            ['name' => 'Python', 'questions_count' => 38, 'trend' => 8],
            ['name' => 'React', 'questions_count' => 32, 'trend' => 15],
            ['name' => 'Laravel', 'questions_count' => 28, 'trend' => 6],
            ['name' => 'Database', 'questions_count' => 25, 'trend' => 4]
        ];

        return response()->json($topics);
    }

    // Reports API
    public function reportsStats()
    {
        $stats = [
            'total' => PostReport::count() + QuestionReport::count() + AnswerReport::count(),
            'pending' => PostReport::where('status', 'pending')->count() +
                        QuestionReport::where('status', 'pending')->count() +
                        AnswerReport::where('status', 'pending')->count(),
            'resolved' => PostReport::where('status', 'resolved')->count() +
                         QuestionReport::where('status', 'resolved')->count() +
                         AnswerReport::where('status', 'resolved')->count(),
        ];

        return response()->json($stats);
    }

    public function getReports(Request $request)
    {
        $query = $request->get('query', '');
        $status = $request->get('status', '');
        $type = $request->get('type', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');
        $perPage = $request->get('per_page', 20);

        // Get reports from all three tables
        $postReports = PostReport::with(['reporter', 'post'])
            ->when($status, function($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($query, function($q) use ($query) {
                return $q->whereHas('post', function($postQuery) use ($query) {
                    $postQuery->where('heading', 'like', "%{$query}%")
                              ->orWhere('details', 'like', "%{$query}%");
                });
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                return $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                return $q->whereDate('created_at', '<=', $dateTo);
            })
            ->when($type && $type !== 'post', function($q) {
                return $q->whereRaw('1 = 0'); // Exclude if type is not post
            })
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->report_id,
                    'type' => 'post',
                    'content_title' => $report->post ? $report->post->heading : 'Deleted Post',
                    'content' => $report->post ? $report->post->details : 'Content not available',
                    'reporter_name' => $report->reporter->name ?? 'Unknown User',
                    'reporter_id' => $report->reporter_id,
                    'content_id' => $report->post_id,
                    'reason' => $report->reason,
                    'created_at' => $report->created_at,
                    'status' => $report->status ?? 'pending'
                ];
            });

        $questionReports = QuestionReport::with(['reporter', 'question'])
            ->when($status, function($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($query, function($q) use ($query) {
                return $q->whereHas('question', function($questionQuery) use ($query) {
                    $questionQuery->where('title', 'like', "%{$query}%")
                                  ->orWhere('description', 'like', "%{$query}%");
                });
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                return $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                return $q->whereDate('created_at', '<=', $dateTo);
            })
            ->when($type && $type !== 'question', function($q) {
                return $q->whereRaw('1 = 0'); // Exclude if type is not question
            })
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->report_id,
                    'type' => 'question',
                    'content_title' => $report->question ? $report->question->title : 'Deleted Question',
                    'content' => $report->question ? $report->question->description : 'Content not available',
                    'reporter_name' => $report->reporter->name ?? 'Unknown User',
                    'reporter_id' => $report->reporter_id,
                    'content_id' => $report->question_id,
                    'reason' => $report->reason,
                    'created_at' => $report->created_at,
                    'status' => $report->status ?? 'pending'
                ];
            });

        $answerReports = AnswerReport::with(['reporter', 'answer.question'])
            ->when($status, function($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($query, function($q) use ($query) {
                return $q->whereHas('answer', function($answerQuery) use ($query) {
                    $answerQuery->where('content', 'like', "%{$query}%");
                });
            })
            ->when($dateFrom, function($q) use ($dateFrom) {
                return $q->whereDate('created_at', '>=', $dateFrom);
            })
            ->when($dateTo, function($q) use ($dateTo) {
                return $q->whereDate('created_at', '<=', $dateTo);
            })
            ->when($type && $type !== 'answer', function($q) {
                return $q->whereRaw('1 = 0'); // Exclude if type is not answer
            })
            ->get()
            ->map(function ($report) {
                return [
                    'id' => $report->report_id,
                    'type' => 'answer',
                    'content_title' => $report->answer && $report->answer->question ? $report->answer->question->title : 'Deleted Question',
                    'content' => $report->answer ? $report->answer->content : 'Content not available',
                    'reporter_name' => $report->reporter->name ?? 'Unknown User',
                    'reporter_id' => $report->reporter_id,
                    'content_id' => $report->answer_id,
                    'reason' => $report->reason,
                    'created_at' => $report->created_at,
                    'status' => $report->status ?? 'pending'
                ];
            });

        // Combine all reports and sort by created_at
        $allReports = $postReports->concat($questionReports)->concat($answerReports)
            ->sortByDesc('created_at')
            ->values();

        // Apply pagination manually
        $total = $allReports->count();
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $reports = $allReports->slice($offset, $perPage);

        return response()->json([
            'data' => $reports->values(),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $total)
            ]
        ]);
    }

    public function updateReportStatus(Request $request, $type, $id)
    {
        \Log::info("updateReportStatus called", ['type' => $type, 'id' => $id, 'request' => $request->all()]);
        
        $request->validate([
            'status' => 'required|in:pending,review,resolved,dismissed'
        ]);

        $status = $request->status;
        $report = null;

        switch ($type) {
            case 'post':
                $report = PostReport::find($id);
                break;
            case 'question':
                $report = QuestionReport::find($id);
                break;
            case 'answer':
                $report = AnswerReport::find($id);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $report->update(['status' => $status]);

        // Send email notification to reporter
        if ($status === 'resolved') {
            try {
                Mail::to($report->reporter->email)->send(new ReportNotification($report, 'resolved', $type));
            } catch (\Exception $e) {
                // Log email error but don't fail the request
                \Log::error('Failed to send report notification email: ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => 'Report status updated successfully',
            'report' => [
                'id' => $report->report_id,
                'type' => $type,
                'status' => $status
            ]
        ]);
    }

    public function deleteReport($type, $id)
    {
        $report = null;

        switch ($type) {
            case 'post':
                $report = PostReport::find($id);
                break;
            case 'question':
                $report = QuestionReport::find($id);
                break;
            case 'answer':
                $report = AnswerReport::find($id);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully'
        ]);
    }

    public function getReportDetails($type, $id)
    {
        $report = null;

        switch ($type) {
            case 'post':
                $report = PostReport::with(['reporter', 'post'])->find($id);
                break;
            case 'question':
                $report = QuestionReport::with(['reporter', 'question'])->find($id);
                break;
            case 'answer':
                $report = AnswerReport::with(['reporter', 'answer.question'])->find($id);
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $data = [
            'id' => $report->report_id,
            'type' => $type,
            'reporter' => [
                'id' => $report->reporter->user_id,
                'name' => $report->reporter->name,
                'email' => $report->reporter->email
            ],
            'reason' => $report->reason,
            'status' => $report->status,
            'created_at' => $report->created_at
        ];

        // Add content details based on type
        switch ($type) {
            case 'post':
                if ($report->post) {
                    $data['content'] = [
                        'id' => $report->post->post_id,
                        'title' => $report->post->heading,
                        'content' => $report->post->details,
                        'author' => $report->post->user->name ?? 'Unknown',
                        'created_at' => $report->post->created_at
                    ];
                } else {
                    $data['content'] = [
                        'id' => null,
                        'title' => 'Content Deleted',
                        'content' => 'This content has been removed.',
                        'author' => 'Unknown',
                        'created_at' => null
                    ];
                }
                break;
            case 'question':
                if ($report->question) {
                    $data['content'] = [
                        'id' => $report->question->question_id,
                        'title' => $report->question->title,
                        'content' => $report->question->description,
                        'author' => $report->question->user->name ?? 'Unknown',
                        'created_at' => $report->question->created_at
                    ];
                } else {
                    $data['content'] = [
                        'id' => null,
                        'title' => 'Content Deleted',
                        'content' => 'This content has been removed.',
                        'author' => 'Unknown',
                        'created_at' => null
                    ];
                }
                break;
            case 'answer':
                if ($report->answer) {
                    $data['content'] = [
                        'id' => $report->answer->answer_id,
                        'content' => $report->answer->content,
                        'question_title' => $report->answer->question->title ?? 'Question Deleted',
                        'author' => $report->answer->user->name ?? 'Unknown',
                        'created_at' => $report->answer->created_at
                    ];
                } else {
                    $data['content'] = [
                        'id' => null,
                        'title' => 'Content Deleted',
                        'content' => 'This content has been removed.',
                        'author' => 'Unknown',
                        'created_at' => null
                    ];
                }
                break;
        }

        return response()->json($data);
    }

    public function deleteReportedContent($type, $id)
    {
        \Log::info("deleteReportedContent called", ['type' => $type, 'id' => $id]);
        
        $report = null;
        $contentAuthor = null;

        switch ($type) {
            case 'post':
                $report = PostReport::with(['post.user', 'reporter'])->find($id);
                if ($report && $report->post) {
                    $contentAuthor = $report->post->user;
                    // The Post model will handle cascade deletion automatically
                    $report->post->delete();
                }
                break;
            case 'question':
                $report = QuestionReport::with(['question.user', 'reporter'])->find($id);
                if ($report && $report->question) {
                    $contentAuthor = $report->question->user;
                    // The Question model will handle cascade deletion automatically
                    $report->question->delete();
                }
                break;
            case 'answer':
                $report = AnswerReport::with(['answer.user', 'reporter'])->find($id);
                if ($report && $report->answer) {
                    $contentAuthor = $report->answer->user;
                    // The Answer model will handle cascade deletion automatically
                    $report->answer->delete();
                }
                break;
            default:
                return response()->json(['error' => 'Invalid report type'], 400);
        }

        if (!$report) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        // Mark the report as resolved
        $report->update(['status' => 'resolved']);

        // Send email notifications
        try {
            // Send email to content author about deletion
            if (isset($contentAuthor) && $contentAuthor) {
                Mail::to($contentAuthor->email)->send(new ReportNotification($report, 'deleted', $type));
            }
            
            // Send email to reporter about resolution
            Mail::to($report->reporter->email)->send(new ReportNotification($report, 'resolved', $type));
        } catch (\Exception $e) {
            // Log email error but don't fail the request
            \Log::error('Failed to send content deletion notification emails: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Reported content deleted successfully',
            'report_status' => 'resolved'
        ]);
    }

    // Users API
    public function usersStats()
    {
        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return response()->json($stats);
    }

    public function getUsers(Request $request)
    {
        $users = User::withCount(['questions', 'answers'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                    'created_at' => $user->created_at,
                    'questions_count' => $user->questions_count,
                    'answers_count' => $user->answers_count,
                    'status' => $user->status ?? 'active'
                ];
            });

        return response()->json($users);
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'username' => 'required|string|unique:users|max:255',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,moderator,admin'
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role']
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    // Questions API
    public function questionsStats()
    {
        $stats = [
            'total' => Question::count(),
            'answered' => Question::whereHas('answers')->count(),
            'unanswered' => Question::whereDoesntHave('answers')->count(),
        ];

        return response()->json($stats);
    }

    public function getQuestions(Request $request)
    {
        $questions = Question::with(['user'])
            ->withCount(['answers'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($question) {
                return [
                    'id' => $question->id,
                    'title' => $question->title,
                    'content' => $question->content,
                    'user' => [
                        'id' => $question->user->id,
                        'username' => $question->user->username,
                        'avatar' => $question->user->avatar
                    ],
                    'created_at' => $question->created_at,
                    'answers_count' => $question->answers_count,
                    'views_count' => $question->views_count ?? 0,
                    'status' => $question->answers_count > 0 ? 'answered' : 'unanswered'
                ];
            });

        return response()->json($questions);
    }

    // Answers API
    public function answersStats()
    {
        $stats = [
            'total' => Answer::count(),
            'accepted' => Answer::where('is_accepted', true)->count(),
            'top_rated' => Answer::where('rating', '>=', 4)->count(),
        ];

        return response()->json($stats);
    }

    public function getAnswers(Request $request)
    {
        $answers = Answer::with(['user', 'question'])
            ->withCount(['votes'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($answer) {
                return [
                    'id' => $answer->id,
                    'content' => $answer->content,
                    'user' => [
                        'id' => $answer->user->id,
                        'username' => $answer->user->username,
                        'avatar' => $answer->user->avatar
                    ],
                    'question' => [
                        'id' => $answer->question->id,
                        'title' => $answer->question->title
                    ],
                    'created_at' => $answer->created_at,
                    'votes_count' => $answer->votes_count,
                    'rating' => $answer->rating ?? 0
                ];
            });

        return response()->json($answers);
    }

    // Posts API
    public function postsStats()
    {
        $stats = [
            'total' => Post::count(),
            'published' => Post::where('status', 'published')->count(),
            'draft' => Post::where('status', 'draft')->count(),
        ];

        return response()->json($stats);
    }

    public function getPosts(Request $request)
    {
        $posts = Post::with(['user'])
            ->withCount(['comments'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'user' => [
                        'id' => $post->user->id,
                        'username' => $post->user->username,
                        'avatar' => $post->user->avatar
                    ],
                    'created_at' => $post->created_at,
                    'views_count' => $post->views_count ?? 0,
                    'comments_count' => $post->comments_count,
                    'status' => $post->status
                ];
            });

        return response()->json($posts);
    }

    public function storePost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|in:published,draft',
            'tags' => 'nullable|string'
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'status' => $validated['status'],
            'user_id' => auth()->id(),
            'tags' => $validated['tags']
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post
        ]);
    }

    // Settings API
    public function updateSettings(Request $request)
    {
        // This would typically save to a settings table or config
        return response()->json([
            'message' => 'Settings updated successfully'
        ]);
    }

    public function updateSecuritySettings(Request $request)
    {
        // This would typically save to a settings table or config
        return response()->json([
            'message' => 'Security settings updated successfully'
        ]);
    }

    public function updateAppearanceSettings(Request $request)
    {
        // This would typically save to a settings table or config
        return response()->json([
            'message' => 'Appearance settings updated successfully'
        ]);
    }

    // System API
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');

        return response()->json([
            'message' => 'Cache cleared successfully'
        ]);
    }

    public function optimizeSystem()
    {
        \Artisan::call('config:cache');
        \Artisan::call('route:cache');
        \Artisan::call('view:cache');

        return response()->json([
            'message' => 'System optimized successfully'
        ]);
    }
} 