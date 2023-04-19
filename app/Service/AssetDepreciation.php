<?php

namespace App\Service;

use App\CompanyIdentity;
use App\Http\Controllers\UsersController;
use App\HRPerson;
use App\AssetDepreciation;
use App\Models\Assets;
use Carbon\Carbon;
use ErrorException;
use Exception;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use GuzzleHttp;
use App\Traits\TotalDaysWithoutWeekendsTrait;
use Illuminate\Support\Facades\Mail;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use phpDocumentor\Reflection\Types\Integer;
use Rap2hpoutre\FastExcel\FastExcel;


class AssetDepreciation
{
    use TotalDaysWithoutWeekendsTrait;

    /**
     * @throws ErrorException
     * @throws GuzzleException
     */
    public function connectToSage()
    {
        /*$client = new GuzzleHttp\Client();
        $ers_token = leave_configuration::pluck('ers_token_number')->first();
        if (!empty($ers_token)) {
            $token = $ers_token;
        } else {
            throw new ErrorException('Ers Token Not Found');
        }

        //$date_from = Carbon::parse('07:00:00')->format('Y/m/d H:i:s');
        //$date_to = Carbon::parse('18:00:00')->format('Y/m/d H:i:s');
        $date_from = Carbon::now()->format('Y/m/d');
        $date_to = Carbon::now()->format('Y/m/d');

        $todo = 'get_clocks';

        $theUrl = 'https://r14.ersbio.co.za/api/data_client.php?'
            . 't=' . $token
            . '&to_do=' . $todo
            . '&imei=0'
            . '&last_id=1&'
            . 'date_from=' . $date_from
            . '&date_to=' . $date_to
            . '&export=0'
            . '&display=2'; // export type


        $res = $client->request('GET', $theUrl);
        $body = $res->getBody()->getContents();
        return json_decode($body, true);*/

    }

    /**
     * @return void
     * @throws GuzzleException
     * @throws \Throwable
     */
    public function depreciation()
    {
        $date_now = Carbon::now()->toDayDateTimeString();
		// get all assets where status is in "in use, unallocated, in store"
        $assets = Assets::getAssets();
		/// check if assets cllectin retuned values
        if (count($assets) > 0) {
            foreach ($assets as $asset) {
				// loop through results
				$depreciated = AssetDepreciation::where('asset_id',$asset->id)->orderBy('id', 'DESC')->first();
                if (!empty($depreciated))
				{
					// skip if its the last month
					if ($depreciated->years == $asset->AssetType->depreciation_year && $depreciated->months == 12)
						continue;
					// calculation 
					$months = $years = 0;
					if ($depreciated->months == 12)
					{
						$years = $depreciated->years + 1;
						$months = 1;
					}
					else
					{
						$years = $depreciated->years;
						$months = $depreciated->months + 1;
					}
					
					// add a new row
					$depreciation = new AssetDepreciation();
					$depreciation->amount_monthly = $depreciated->amount_monthly;
					$depreciation->initial_amount = $depreciated->balance_amount;
					$depreciation->balance_amount = ($depreciated->balance_amount - $depreciated->amount_monthly);
					$depreciation->months = $months;
					$depreciation->years = $years;
					$depreciation->asset_id = $asset->id;
					$depreciation->notes = "System Monthly Depreciation";
					$depreciation->save();
				}
				else
				{
					// calculation 
					$amount = $asset->price / ($asset->AssetType->depreciation_year * 12);
					
					$depreciation = new AssetDepreciation();
					$depreciation->amount_monthly = $amount;
					$depreciation->initial_amount = $asset->price;
					$depreciation->balance_amount = $asset->price - $amount;
					$depreciation->months = 1;
					$depreciation->years = 1;
					$depreciation->asset_id = $asset->id;
					$depreciation->notes = "System Monthly Depreciation";
					$depreciation->save();
				}
            }
        }
        
    }

}