<?php

namespace App\Console\Commands;

use App\Services\SageCloud\SageCloudApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PopulateDataBundles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'paynacle:populate-data-bundles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the data bundles tables';

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
     * @param SageCloudApiService $sageCloudApi
     * @return int
     */
    public function handle(SageCloudApiService $sageCloudApi)
    {

        $data_providers_table = 'sage_cloud_data_providers';
        $data_bundles_table = 'sage_cloud_data_bundles';

        $data_providers_exists = Schema::hasTable($data_providers_table);
        $data_bundles_exists = Schema::hasTable($data_bundles_table);

        if ( $data_providers_exists ) {
            DB::table($data_providers_table)->truncate();
            DB::table($data_bundles_table)->truncate();
            $providers = $sageCloudApi->fetchDataProviders()['billers'];
            $db_data = [];
            $bundles = [];

            foreach($providers as $provider)
            {
                $db_data[] = [
                    '_id' => $provider['id'],
                    'type' => $provider['type'],
                    'name' => $provider['name'],
                    'narration' => $provider['narration'],
                    'image' => $provider['image'],
                ];

                $bundles = $sageCloudApi->fetchDataBundles(['provider' => $provider['type']])['data'];
                DB::table($data_bundles_table)->insert($bundles);
            }
            DB::table($data_providers_table)->insert($db_data);
        }

        return 0;
    }
}
