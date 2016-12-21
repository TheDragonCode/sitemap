<?php
/*
 * This file is part of the Sitemap package.
 *
 * (c) Andrey Helldar <helldar@ai-rus.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helldar\Sitemap\Console;

use Helldar\Sitemap\Controllers\SitemapController;
use Illuminate\Console\Command;

class SitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old records from the sitemap table.';

    /**
     * @var string
     */
    protected $table_name = 'sitemaps';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute console command.
     *
     * @author  Andrey Helldar <helldar@ai-rus.com>
     *
     * @version 2016-12-21
     */
    public function handle()
    {
        if (!\Schema::hasTable($this->table_name)) {
            $this->error('Table `'.$this->table_name.'` not found!');

            return;
        }

        SitemapController::clearDb();

        $this->warn('Table `'.$this->table_name.'` cleaned successfully!');
    }
}
