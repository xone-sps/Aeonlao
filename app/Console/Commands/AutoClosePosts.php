<?php

namespace App\Console\Commands;

use App\Posts;
use Illuminate\Console\Command;

class AutoClosePosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:close {--types=*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically to close posts types {events} when it reached their expires time.';


    public function IsExpired($ctime): bool
    {
        date_default_timezone_set('Asia/Vientiane');
        return strtotime($ctime) <= time();
    }

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $event_posts = Posts::where('type', 'event')->where('status', 'open')->get();
        if (count($event_posts)) {
            foreach ($event_posts as $post) {
                if ($this->IsExpired($post->deadline)) {
                    $post->status = 'close';
                    $post->save();
                    $this->info('The event is expired now, Post Id = ' . $post->id);
                }
            }
        }
    }
}
