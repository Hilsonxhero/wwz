<?php

use Modules\Sms\Facades\Sms;
use Melipayamak\MelipayamakApi;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Modules\Comment\Entities\Comment;
use Modules\Product\Entities\Product;
use Modules\Category\Entities\Category;
use Modules\Comment\Entities\CommentScore;
use Illuminate\Support\Facades\Notification;
use Modules\Comment\Enums\CommentStatus;
use Modules\Order\Notifications\v1\App\OrderConfirmationNotif;
use Modules\User\Notifications\v1\App\VerifyPhoneNotification;


Route::get('/', function () {

    // $productId = 4;
    // $averageScore = CommentScore::whereIn('comment_id', function ($query) use ($productId) {
    //     $query->select('id')
    //         ->from('comments')
    //         ->where('commentable_id', $productId);
    // })
    //     ->avg('value');
    // return $averageScore;

    // return Cache::remember('comment.scores', 900, function () {
    //     $product = Product::find(4);
    //     return  DB::table('comments')
    //         ->join('comment_scores', 'comments.id', '=', 'comment_scores.comment_id')
    //         ->join('score_models', 'comment_scores.score_model_id', '=', 'score_models.id')
    //         ->where('comments.commentable_id', '=', $product->id)
    //         ->groupBy('score_models.id')
    //         ->select('score_models.id', DB::raw('AVG(comment_scores.value) as avg_value'))
    //         ->get();
    // });
    // return Product::where('id', 4)->with('comments')->paginate();

    // return Product::query()->where('id', 4)->first()->comments()->where('status', CommentStatus::Approved)->paginate(20);

    $comments = Comment::with('scores.score_model')
        ->where('commentable_id', 4)
        ->select('id', 'commentable_id')
        ->join('comment_scores', 'comments.id', '=', 'comment_scores.comment_id')
        ->join('score_models', 'comment_scores.score_model_id', '=', 'score_models.id')
        ->groupBy('score_models.id')
        ->select('score_models.id', 'score_models.title', DB::raw('AVG(comment_scores.value) as avg_value'))->get();

    return $comments;


    $product = Product::with(['comments.scores.score_model'])
        ->find(4);


    $scores = $product->comments->flatMap(function ($comment) {
        return $comment->scores->groupBy('score_model_id')
            ->map(function ($scores) {
                return $scores->avg('value');
            });
    })->groupBy(function ($score_value, $score_model_id) {
        return $score_model_id;
    })->map(function ($scores, $score_model_id) {
        return [
            'id' => $score_model_id,
            'avg_value' => round($scores->avg(), 4)
        ];
    })->values();
    return $scores;
    return view('welcome');
});
