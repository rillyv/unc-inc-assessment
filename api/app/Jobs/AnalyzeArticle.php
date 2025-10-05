<?php

namespace App\Jobs;

use App\Models\Article;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyzeArticle implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $articleId) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $article = Article::find($this->articleId);

        if (! $article) {
            Log::warning('Article not found for NLP analysis', ['id' => $this->articleId]);
            return;
        }

        $url = config('services.nlp_service.url');

        try {
            $response = Http::timeout(5)->retry(3, 1000)->post("$url/analyze", [
                'content' => $article->content,
            ]);

            if ($response->failed()) {
                Log::error('NLP service failed', ['body' => $response->body()]);
                return;
            }

            $data = $response->json();

            $article->update([
                'summary' => $data['summary'] ?? null,
                'keywords' => $data['keywords'] ?? [],
            ]);
        } catch (\Throwable $e) {
            Log::error('NLP service exception', ['error' => $e->getMessage()]);
        }
    }
}
