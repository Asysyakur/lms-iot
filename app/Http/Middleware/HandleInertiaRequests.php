<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Meeting;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    // public function share(Request $request): array
    // {
    //     return [
    //         ...parent::share($request),
    //         'name' => config('app.name'),
    //         'auth' => [
    //             'user' => $request->user(),
    //         ],
    //         'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
    //     ];
    // }
    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(
            parent::share($request),
            [
                'sidebarMeetings' => $user?->role === 'student'
                    ? Meeting::with([
                        'material',
                        'quizzes',
                        'practice',
                        'lkpd',
                    ])
                        ->orderBy('id')
                        ->get()
                        ->filter(function ($meeting) use ($user) {
                            return $meeting->isAccessibleTo($user?->class);
                        })
                        ->map(function ($meeting) {
                            return [
                            'id' => $meeting->id,

                            'title' => $meeting->title,

                            'menus' => [

                                /**
                                 * MATERI
                                 */
                                [
                                    'title' => 'Materi',

                                    'href' =>
                                    "/student/meetings/{$meeting->id}/material",

                                    'icon' => 'BookOpen',

                                    'unlocked' => (
                                        $meeting->is_active
                                        &&
                                        $meeting->material?->is_active
                                    ),

                                    'completed' => $meeting
                                        ->hasCompletedMaterial(
                                            auth()->id()
                                        ),
                                ],

                                /**
                                 * KUIS
                                 */
                                [
                                    'title' => 'Kuis',

                                    'href' =>
                                    "/student/meetings/{$meeting->id}/quiz",

                                    'icon' =>
                                    'ClipboardCheck',

                                    'unlocked' => (
                                        $meeting->is_active
                                        &&
                                        $meeting->quizzes()
                                        ->where(
                                            'is_active',
                                            true
                                        )
                                        ->exists()
                                        &&
                                        $meeting
                                        ->hasCompletedMaterial(
                                            auth()->id()
                                        )
                                    ),

                                    'completed' => $meeting
                                        ->hasCompletedQuiz(
                                            auth()->id()
                                        ),
                                ],

                                /**
                                 * PRAKTIK
                                 */
                                [
                                    'title' => 'Praktik',

                                    'href' =>
                                    "/student/meetings/{$meeting->id}/practice",

                                    'icon' =>
                                    'FlaskConical',

                                    'unlocked' => (
                                        $meeting->is_active
                                        &&
                                        $meeting->practice?->is_active
                                        &&
                                        $meeting
                                        ->hasCompletedQuiz(
                                            auth()->id()
                                        )
                                    ),

                                    'completed' => $meeting
                                        ->hasCompletedPractice(
                                            auth()->id()
                                        ),
                                ],

                                /**
                                 * LKPD
                                 */
                                [
                                    'title' => 'LKPD',

                                    'href' =>
                                    "/student/meetings/{$meeting->id}/lkpd",

                                    'icon' =>
                                    'FileSpreadsheet',

                                    'unlocked' => (
                                        $meeting->is_active
                                        &&
                                        $meeting->lkpd?->is_active
                                        &&
                                        $meeting
                                        ->hasCompletedPractice(
                                            auth()->id()
                                        )
                                    ),

                                    'completed' => $meeting
                                        ->hasCompletedLkpd(
                                            auth()->id()
                                        ),
                                ],

                                /**
                                 * EVALUASI
                                 */
                                [
                                    'title' => 'Evaluasi',

                                    'href' =>
                                    "/student/meetings/{$meeting->id}/evaluation",

                                    'icon' =>
                                    'ClipboardList',

                                    'unlocked' => (
                                        $meeting->is_active
                                        &&
                                        $meeting->evaluation?->is_active
                                        &&
                                        $meeting
                                        ->hasCompletedLkpd(
                                            auth()->id()
                                        )
                                    ),

                                    'completed' => $meeting
                                        ->hasCompletedEvaluation(
                                            auth()->id()
                                        ),
                                ],
                            ],
                        ];
                        })
                        ->values()
                    : [],
            ]
        );
    }
}
