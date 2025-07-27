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
                    'content_title' => $report->post ? ($report->post->heading ?? $report->post->title) : 'Deleted Post',
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
                        'title' => $report->post->heading ?? $report->post->title,
                        'content' => $report->post->details ?? $report->post->content,
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
        $activeUsers = User::where('role', 'user')->where('last_login_at', '>=', now()->subDays(7))->count();
        $inactiveUsers = User::where('role', 'user')->where(function($q) {
            $q->whereNull('last_login_at')->orWhere('last_login_at', '<', now()->subDays(7));
        })->count();
        $totalUsers = User::where('role', 'user')->count();
        $totalAdmins = \App\Models\Admin::count();
        $stats = [
            'total_accounts' => $totalUsers + $totalAdmins,
            'active' => $activeUsers,
            'inactive' => $inactiveUsers,
            'banned' => User::where('status', 'banned')->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
            'new_this_week' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'new_today' => User::whereDate('created_at', today())->count(),
            'by_role' => [
                'users' => $totalUsers,
                'admins' => $totalAdmins,
            ],
            'top_contributors' => User::withCount(['questions', 'answers', 'posts'])
                ->orderByRaw('(questions_count + answers_count + posts_count) DESC')
                ->limit(5)
                ->get()
                ->map(function($user) {
                    return [
                        'id' => $user->user_id,
                        'name' => $user->name,
                        'username' => $user->username,
                        'total_contributions' => $user->questions_count + $user->answers_count + $user->posts_count
                    ];
                })
        ];

        return response()->json($stats);
    }

    // Add new admin with password authentication
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:admins',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'auth_password' => 'required|string',
        ]);

        // Authenticate current admin
        $currentAdmin = auth('admin')->user();
        if (!$currentAdmin || !\Hash::check($request->auth_password, $currentAdmin->password)) {
            return response()->json(['message' => 'Authentication failed. Incorrect password.'], 403);
        }

        $admin = \App\Models\Admin::create([
            'username' => $request->username,
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'status' => 'active',
        ]);

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin
        ]);
    }

    public function getUsers(Request $request)
    {
        $query = $request->get('search', '');
        $role = $request->get('role', '');
        $status = $request->get('status', '');
        $joinedFrom = $request->get('joined_from', '');
        $joinedTo = $request->get('joined_to', '');
        $perPage = $request->get('per_page', 10);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        $users = User::withCount(['questions', 'answers', 'posts'])
            ->when($query, function($q) use ($query) {
                return $q->where(function($subQ) use ($query) {
                    $subQ->where('name', 'like', "%{$query}%")
                         ->orWhere('username', 'like', "%{$query}%")
                         ->orWhere('email', 'like', "%{$query}%");
                });
            })
            ->when($role, function($q) use ($role) {
                return $q->where('role', $role);
            })
            ->when($status, function($q) use ($status) {
                return $q->where('status', $status);
            })
            ->when($joinedFrom, function($q) use ($joinedFrom) {
                return $q->whereDate('created_at', '>=', $joinedFrom);
            })
            ->when($joinedTo, function($q) use ($joinedTo) {
                return $q->whereDate('created_at', '<=', $joinedTo);
            })
            ->orderBy($sortBy, $sortOrder)
            ->paginate($perPage);

        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->user_id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'avatar' => $user->avatar_url, // Use full S3 URL
                'bio' => $user->bio,
                'role' => $user->role ?? 'user',
                'status' => $user->status ?? 'active',
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'last_login_at' => $user->last_login_at,
                'questions_count' => $user->questions_count,
                'answers_count' => $user->answers_count,
                'posts_count' => $user->posts_count,
                'total_contributions' => $user->questions_count + $user->answers_count + $user->posts_count
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
            'role' => 'required|in:user,admin',
            'bio' => 'nullable|string|max:500'
        ]);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'username' => $validated['username'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'bio' => $validated['bio'] ?? null,
            'status' => 'active'
        ]);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    public function getUserDetails($id)
    {
        try {
            $user = \App\Models\User::withCount(['questions', 'answers', 'posts'])
                ->with(['questions' => function($q) {
                    $q->latest()->limit(5);
                }, 'answers' => function($q) {
                    $q->latest()->limit(5);
                }, 'posts' => function($q) {
                    $q->latest()->limit(5);
                }])
                ->findOrFail($id);

            return response()->json([
                'user' => [
                    'id' => $user->user_id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'avatar' => $user->avatar_url, // Use full S3 URL
                    'bio' => $user->bio,
                    'role' => $user->role ?? 'user',
                    'status' => $user->status ?? 'active',
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'last_login_at' => $user->last_login_at,
                    'questions_count' => $user->questions_count,
                    'answers_count' => $user->answers_count,
                    'posts_count' => $user->posts_count,
                    'recent_questions' => $user->questions->map(function($q) {
                        return [
                            'id' => $q->question_id,
                            'title' => $q->title,
                            'created_at' => $q->created_at
                        ];
                    }),
                    'recent_answers' => $user->answers->map(function($a) {
                        return [
                            'id' => $a->answer_id,
                            'content' => substr($a->content, 0, 100) . '...',
                            'question_title' => $a->question->title ?? 'Deleted Question',
                            'created_at' => $a->created_at
                        ];
                    }),
                    'recent_posts' => $user->posts->map(function($p) {
                        return [
                            'id' => $p->post_id,
                            'heading' => $p->heading,
                            'created_at' => $p->created_at
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error loading user details: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'username' => 'required|string|unique:users,username,' . $id . '|max:255',
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive,banned',
            'bio' => 'nullable|string|max:500'
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->user_id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete your own account'
            ], 400);
        }

        if ($user->email) {
            \Mail::to($user->email)->send(new \App\Mail\UserActionNotification($user, 'user_deleted'));
        }

        // Delete user's content (questions, answers, posts, etc.)
        $user->questions()->delete();
        $user->answers()->delete();
        $user->posts()->delete();
        
        // Delete user
        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ]);
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from banning themselves
        if ($user->user_id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot ban your own account'
            ], 400);
        }

        $user->update(['status' => 'banned']);

        if ($user->email) {
            \Mail::to($user->email)->send(new \App\Mail\UserActionNotification($user, 'user_banned'));
        }

        return response()->json([
            'message' => 'User banned successfully'
        ]);
    }

    public function unbanUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => 'active']);

        return response()->json([
            'message' => 'User unbanned successfully'
        ]);
    }

    public function changeUserRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $user->update(['role' => $validated['role']]);

        return response()->json([
            'message' => 'User role updated successfully'
        ]);
    }

    // Questions API
    public function questionsStats()
    {
        try {
            $totalQuestions = Question::count();
            $answeredQuestions = Question::whereHas('answers')->count();
            $unansweredQuestions = Question::whereDoesntHave('answers')->count();
            $closedQuestions = Question::where('is_closed', true)->count();
            
            // Calculate answer rate
            $answerRate = $totalQuestions > 0 ? round(($answeredQuestions / $totalQuestions) * 100, 1) : 0;
            
            $stats = [
                'total' => $totalQuestions,
                'answered' => $answeredQuestions,
                'unanswered' => $unansweredQuestions,
                'closed' => $closedQuestions,
                'questions_today' => Question::whereDate('created_at', today())->count(),
                'questions_this_week' => Question::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'questions_this_month' => Question::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
                'answer_rate' => $answerRate,
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load stats: ' . $e->getMessage()], 500);
        }
    }

    public function getQuestions(Request $request)
    {
        try {
            $query = Question::with(['user'])
                ->withCount(['answers']);

            // Search functionality
            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('username', 'like', "%{$search}%");
                      });
                });
            }

            // Status filter
            if ($request->has('status') && $request->status) {
                switch ($request->status) {
                    case 'answered':
                        $query->whereHas('answers');
                        break;
                    case 'unanswered':
                        $query->whereDoesntHave('answers');
                        break;
                    case 'closed':
                        $query->where('is_closed', true);
                        break;
                }
            }

            // Sort functionality
            $sortBy = $request->get('sort', 'latest');
            switch ($sortBy) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'most_answers':
                    $query->orderBy('answers_count', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }

            // Get all questions without pagination for now
            $questions = $query->get();

            // Transform the data
            $transformedQuestions = $questions->map(function ($question) {
                $name = $question->user->name;
                $username = $question->user->username;
                if (!$name && $username) $name = $username;
                if (!$name) $name = 'Unknown User';
                if (!$username && $name) $username = $name;
                $content = $question->description;
                if (!$content) $content = 'N/A';
                return [
                    'id' => $question->question_id,
                    'title' => $question->title,
                    'content' => $content,
                    'user' => [
                        'id' => $question->user->user_id,
                        'name' => $name,
                        'username' => $username,
                        'avatar' => $question->user->avatar_url, // full S3 URL
                    ],
                    'created_at' => $question->created_at,
                    'updated_at' => $question->updated_at,
                    'answers_count' => $question->answers_count,
                    'votes_count' => ($question->upvotes ?? 0) + ($question->downvotes ?? 0),
                    'views_count' => 0,
                    'status' => $question->answers_count > 0 ? 'answered' : 'unanswered',
                    'tags' => [],
                    'is_featured' => false,
                    'is_closed' => $question->is_closed ?? false
                ];
            });

            return response()->json($transformedQuestions);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load questions: ' . $e->getMessage()], 500);
        }
    }

    public function getQuestionDetails($id)
    {
        try {
            $question = Question::with(['user', 'tags', 'answers.user'])
                ->withCount(['answers'])
                ->findOrFail($id);

            $data = [
                'id' => $question->question_id,
                'title' => $question->title,
                'content' => $question->description, // question body
                'user' => [
                    'id' => $question->user->user_id,
                    'name' => $question->user->name,
                    'username' => $question->user->username,
                    'avatar' => $question->user->avatar_url, // full S3 URL
                    'email' => $question->user->email
                ],
                'created_at' => $question->created_at,
                'updated_at' => $question->updated_at,
                'answers_count' => $question->answers_count,
                'votes_count' => ($question->upvotes ?? 0) + ($question->downvotes ?? 0),
                'views_count' => 0, // Questions don't have views_count in this system
                'status' => $question->is_closed ? 'closed' : ($question->answers_count > 0 ? 'answered' : 'unanswered'),
                'is_featured' => false, // Questions don't have is_featured in this system
                'is_closed' => $question->is_closed ?? false,
                'tags' => $question->tags->pluck('name'),
                'answers' => $question->answers->map(function ($answer) {
                    return [
                        'id' => $answer->answer_id,
                        'content' => $answer->content,
                        'user' => [
                            'id' => $answer->user->user_id,
                            'name' => $answer->user->name,
                            'username' => $answer->user->username,
                            'avatar' => $answer->user->avatar_url, // full S3 URL
                        ],
                        'created_at' => $answer->created_at,
                        'votes_count' => $answer->votes_count ?? 0,
                        'is_accepted' => $answer->isAccepted()
                    ];
                })
            ];

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Question not found: ' . $e->getMessage()], 404);
        }
    }

    public function updateQuestion(Request $request, $id)
    {
        try {
            $question = Question::findOrFail($id);
            
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'is_closed' => 'sometimes|boolean',
                'tags' => 'sometimes|array'
            ]);

            $question->update($validated);

            // Update tags if provided
            if (isset($validated['tags'])) {
                $question->tags()->sync($validated['tags']);
            }

            return response()->json([
                'message' => 'Question updated successfully',
                'question' => $question
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update question: ' . $e->getMessage()], 500);
        }
    }

    public function deleteQuestion($id)
    {
        try {
            $question = Question::with('user')->findOrFail($id);
            $user = $question->user;
            $question->delete();
            if ($user && $user->email) {
                \Mail::to($user->email)->send(new \App\Mail\UserActionNotification(
                    $user,
                    'question_deleted',
                    ['question_title' => $question->title ?? '']
                ));
            }
            return response()->json([
                'message' => 'Question deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete question: ' . $e->getMessage()], 500);
        }
    }

    public function toggleQuestionStatus($id)
    {
        try {
            $question = Question::findOrFail($id);
            $question->is_closed = !$question->is_closed;
            $question->save();

            return response()->json([
                'message' => 'Question status updated successfully',
                'status' => $question->is_closed ? 'closed' : 'open'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update question status: ' . $e->getMessage()], 500);
        }
    }

    public function toggleQuestionFeatured($id)
    {
        try {
            $question = Question::findOrFail($id);
            // Questions don't have is_featured field in this system
            return response()->json([
                'message' => 'Featured functionality not available for questions',
                'is_featured' => false
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update question featured status: ' . $e->getMessage()], 500);
        }
    }

    // Answers API
    public function answersStats()
    {
        $stats = [
            'total' => \App\Models\Answer::count(),
            'accepted' => \App\Models\Question::whereNotNull('accepted_answer_id')->count(),
        ];
        return response()->json($stats);
    }

    public function getAnswers(Request $request)
    {
        $query = \App\Models\Answer::with(['user', 'question'])
            ->withCount(['votes']);

        // Filtering
        if ($request->has('status') && $request->status) {
            if ($request->status === 'accepted') {
                $query->whereHas('question', function($q) {
                    $q->whereColumn('answers.answer_id', 'questions.accepted_answer_id');
                });
            } elseif ($request->status === 'pending') {
                $query->whereDoesntHave('question', function($q) {
                    $q->whereColumn('answers.answer_id', 'questions.accepted_answer_id');
                });
            }
        }
        if ($request->has('rating') && $request->rating) {
            switch ($request->rating) {
                case 'high':
                    $query->where('rating', '>=', 4);
                    break;
                case 'medium':
                    $query->whereBetween('rating', [2, 3]);
                    break;
                case 'low':
                    $query->where('rating', '<=', 1);
                    break;
            }
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'most_votes':
                $query->orderBy('votes_count', 'desc');
                break;
            case 'highest_rated':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination (default 20 per page)
        $perPage = (int)($request->get('per_page', 20));
        $answers = $query->paginate($perPage);

        $data = $answers->map(function ($answer) {
            return [
                'id' => $answer->answer_id,
                'content' => $answer->content,
                'user' => [
                    'id' => $answer->user->user_id,
                    'username' => $answer->user->username,
                    'name' => $answer->user->name,
                    'avatar' => $answer->user->avatar_url // Use full S3/public URL
                ],
                'question' => [
                    'id' => $answer->question->question_id,
                    'title' => $answer->question->title
                ],
                'created_at' => $answer->created_at,
                'votes_count' => $answer->votes_count,
                'rating' => $answer->rating ?? 0
            ];
        });

        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $answers->currentPage(),
                'per_page' => $answers->perPage(),
                'total' => $answers->total(),
                'last_page' => $answers->lastPage(),
            ]
        ]);
    }

    // View answer details
    public function getAnswerDetails($id)
    {
        $answer = \App\Models\Answer::with(['user', 'question.user'])
            ->withCount(['votes'])
            ->findOrFail($id);
        return response()->json([
            'id' => $answer->answer_id,
            'content' => $answer->content,
            'user' => [
                'id' => $answer->user->user_id,
                'username' => $answer->user->username,
                'name' => $answer->user->name,
                'avatar' => $answer->user->avatar,
                'email' => $answer->user->email ?? null
            ],
            'question' => [
                'id' => $answer->question->question_id,
                'title' => $answer->question->title,
                'user_name' => $answer->question->user->name ?? null,
                'user_username' => $answer->question->user->username ?? null,
                'content' => $answer->question->description ?? '',
            ],
            'created_at' => $answer->created_at,
            'votes_count' => $answer->votes_count,
            'rating' => $answer->rating ?? 0,
            'is_accepted' => $answer->isAccepted()
        ]);
    }

    // Delete answer (admin)
    public function deleteAnswer($id)
    {
        $answer = \App\Models\Answer::with('user', 'question')->findOrFail($id);
        $user = $answer->user;
        $question = $answer->question;
        $answer->delete();
        if ($user && $user->email) {
            \Mail::to($user->email)->send(new \App\Mail\UserActionNotification(
                $user,
                'answer_deleted',
                ['question_title' => $question->title ?? '']
            ));
        }
        return response()->json(['message' => 'Answer deleted successfully']);
    }

    // Posts API
    public function postsStats()
    {
        $stats = [
            'total' => Post::count(),
            'today' => Post::whereDate('created_at', today())->count(),
            'week' => Post::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => Post::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
        ];
        return response()->json($stats);
    }

    public function getPosts(Request $request)
    {
        $query = Post::with(['user', 'images', 'comments.user']);
        // Filtering (add more as needed)
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->orWhere('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        $perPage = (int)($request->get('per_page', 20));
        $posts = $query->paginate($perPage);
        $data = $posts->map(function ($post) {
            return [
                'id' => $post->post_id ?? $post->id,
                'title' => $post->heading ?? $post->title,
                'content' => $post->details ?? $post->content,
                'user' => [
                    'id' => $post->user->user_id ?? $post->user->id,
                    'name' => $post->user->name,
                    'username' => $post->user->username,
                    'avatar' => $post->user->avatar_url, // full S3 URL
                ],
                'images' => $post->images->map(function($img) { return $img->image_url; }),
                'comments_count' => $post->comments->count(),
                'upvotes' => $post->upvotes ?? 0,
                'downvotes' => $post->downvotes ?? 0,
                'created_at' => $post->created_at,
                'status' => $post->status ?? 'published',
            ];
        });
        return response()->json([
            'data' => $data,
            'pagination' => [
                'current_page' => $posts->currentPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
                'last_page' => $posts->lastPage(),
            ]
        ]);
    }

    public function getPostDetails($id)
    {
        $post = Post::with(['user', 'images', 'comments.user'])->findOrFail($id);
        return response()->json([
            'id' => $post->post_id ?? $post->id,
            'title' => $post->heading ?? $post->title,
            'content' => $post->details ?? $post->content,
            'user' => [
                'id' => $post->user->user_id ?? $post->user->id,
                'name' => $post->user->name,
                'username' => $post->user->username,
                'avatar' => $post->user->avatar
            ],
            'images' => $post->images->map(function($img) { return $img->image_url; }),
            'comments' => $post->comments->map(function($c) {
                return [
                    'id' => $c->comment_id,
                    'text' => $c->comment_text,
                    'user' => $c->user ? ($c->user->name ?? $c->user->username ?? 'Unknown') : 'Unknown',
                    'created_at' => $c->created_at
                ];
            }),
            'upvotes' => $post->upvotes ?? 0,
            'downvotes' => $post->downvotes ?? 0,
            'created_at' => $post->created_at,
            'status' => $post->status ?? 'published',
        ]);
    }

    public function deletePost($id)
    {
        $post = Post::with('user')->findOrFail($id);
        $user = $post->user;
        $post->delete();
        if ($user && $user->email) {
            \Mail::to($user->email)->send(new \App\Mail\UserActionNotification(
                $user,
                'post_deleted',
                ['post_title' => $post->heading ?? $post->title ?? '']
            ));
        }
        return response()->json(['message' => 'Post deleted successfully']);
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

    // API to get all admins
    public function getAdmins()
    {
        $admins = \App\Models\Admin::select('id', 'username', 'name', 'status', 'created_at')->get();
        return response()->json($admins);
    }

    public function deleteAdmin($id)
    {
        $admin = \App\Models\Admin::findOrFail($id);
        // Prevent admin from deleting themselves
        if ($admin->id === auth('admin')->id()) {
            return response()->json(['message' => 'You cannot delete your own admin account'], 400);
        }
        $admin->delete();
        return response()->json(['message' => 'Admin deleted successfully']);
    }
}