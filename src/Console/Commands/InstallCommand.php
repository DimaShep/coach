<?php
namespace Shep\Coach\Console\Commands;


use Shep\Coach\Coach;
use Shep\Coach\CoachServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Intervention\Image\ImageServiceProviderLaravel5;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Process\Process;
use TCG\Voyager\Providers\VoyagerDummyServiceProvider;
use TCG\Voyager\Traits\Seedable;
use TCG\Voyager\VoyagerServiceProvider;

class InstallCommand extends Command
{
    use Seedable;

    protected $seedersPath = __DIR__.'/../../database/seeds/';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'coach:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Coach Admin package';


    /**
     * Get the composer command for the environment.
     *
     * @return string
     */
    protected function findComposer()
    {
        if (file_exists(getcwd().'/composer.phar')) {
            return '"'.PHP_BINARY.'" '.getcwd().'/composer.phar';
        }

        return 'composer';
    }

    public function fire(Filesystem $filesystem)
    {
        return $this->handle($filesystem);
    }

    /**
     * Execute the console command.
     *
     * @param \Illuminate\Filesystem\Filesystem $filesystem
     *
     * @return void
     */
    public function handle(Filesystem $filesystem)
    {
        $this->info('Publishing the Coach assets, database, and config files');

        //Publish only relevant resources on install
        $tags = ['seeds', 'assets', 'public', 'config'];

        $this->call('vendor:publish', ['--provider' => CoachServiceProvider::class, '--tag' => $tags]);

        $this->info('Migrating the database tables into your application');
      //  $this->call('migrate');

        $this->info('Dumping the autoloaded files and reloading all new files');

        $composer = $this->findComposer();

        $process = new Process($composer.' dump-autoload');
        $process->setTimeout(null); //Setting timeout to null to prevent installation from stopping at a certain point in time
        $process->setWorkingDirectory(base_path())->run();

        $this->info('Adding Coach routes to routes/web.php');
        $routes_contents = $filesystem->get(base_path('routes/web.php'));
        if (false === strpos($routes_contents, 'Coach::routes_admin()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nRoute::group(['prefix' => 'admin'], function () {\n    Coach::routes_admin();\n});\n"
            );
        }
        if (false === strpos($routes_contents, 'Coach::routes_site()')) {
            $filesystem->append(
                base_path('routes/web.php'),
                "\n\nCoach::routes_site();\n"
            );
        }

        \Route::group(['prefix' => 'admin'], function () {
            Coach::routes_admin();
        });

        Coach::routes_site();

        $this->info('Seeding data into the database');
        $this->seed('TDatabaseSeeder');

//        $this->info('Setting up the hooks');
//        $this->call('hook:setup');

//        $this->info('Adding the storage symlink to your public folder');
//        $this->call('storage:link');

        $this->info('Successfully installed Coach! Enjoy');
    }
}
