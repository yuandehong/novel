<?php

namespace App\Console\Commands;

use App\Jobs\SnatchUpdate;
use App\Models\Novel;
use Illuminate\Console\Command;

class SnatchHourly extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'snatch:updateHot
                            {number? : 按热度更新小说数量}
                            {--queue : 是否进入队列}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '(Maple) This command is used to update hot novel`s chapters.';

    /**
     * Create a new command instance.
     *
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
        $number = $this->argument('number') ? intval($this->argument('number')) : 30;
        $hot_ids = Novel::continued()->orderBy('hot', 'desc')->take($number)->lists('id');
        if($this->option('queue')) {
            dispatch(new SnatchUpdate($hot_ids));
        } else {
            $snatch = new SnatchUpdate($hot_ids);
            $snatch->handle();
        }
    }
}
