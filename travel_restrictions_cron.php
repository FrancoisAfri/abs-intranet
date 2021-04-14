<?php
	require('base/controller.php');
	$sPath = $config['system']['subpath'];
	$curenttime = time();
	// get list of countries from database
	$countries = Database::GetSelectOptions('general_countries','id', 'printable_name',null, 'printable_name');
	$count = 0;
	$countries = database::query("select id, printable_name from general_countries where printable_name <> '' order by printable_name");
	if ($countries->numrows() > 0)
	{
		while ($countriesArray = $countries->fetchrow())
		{
			$count ++;
			$countryname = !empty($countriesArray['printable_name']) ? $countriesArray['printable_name'] : '';
			$countryid = !empty($countriesArray['id']) ? $countriesArray['id'] : 0;
			if (!empty($countryname) && !empty($countryid))
			{
				$curl = curl_init();

				curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.traveladviceapi.com/search/$countryname",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 0,
					CURLOPT_FOLLOWLOCATION => true,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_HTTPHEADER => array(
						"X-Access-Token: 3cf0f949-0e06-4d7d-868e-e1e500c8acb1", "Content-Type: application/json; charset=UTF-8"
					),
				));

				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response, true);
				//print_r($response);
				$unauthorized =  !empty($response['message']) ? $response['message'] : '';

				if ($unauthorized != 'Unauthorized')
				{
					$risk_level = !empty($response["risk_level"]) ? $response["risk_level"] : '';
					$recommendation = !empty($response["recommendation"]) ? $response["recommendation"] : '';
					$requirements = !empty($response["requirements"]) ? $response["requirements"] : array();
					$tests = !empty($requirements["tests"]) ? $requirements["tests"] : '';
					$quarantine = !empty($requirements["quarantine"]) ? $requirements["quarantine"] : '';
					$masks = !empty($requirements["masks"]) ? $requirements["masks"] : '';
					// trips
					$trips = !empty($response["trips"]) ? $response["trips"] : array() ;
					$trip = !empty($trips[0]) ? $trips[0] : array() ;
					$covidStats = !empty($trip["covid19_stats"]) ? $trip["covid19_stats"] : array() ;
					// covid stats details
					$total_covid_cases = !empty($covidStats["total_cases"]) ? $covidStats["total_cases"] : ''; 
					$new_cases =  !empty($covidStats["new_cases"]) ? $covidStats["new_cases"] : ''; 
					$total_deaths =  !empty($covidStats["total_deaths"]) ? $covidStats["total_deaths"] : '';
					$new_deaths =  !empty($covidStats["new_deaths"]) ? $covidStats["new_deaths"] : '';
					$total_cases_per_million =  !empty($covidStats["total_cases_per_million"]) ? $covidStats["total_cases_per_million"] : ''; 
					$new_cases_per_million =  !empty($covidStats["new_cases_per_million"]) ? $covidStats["new_cases_per_million"] : '';
					$total_deaths_per_million =  !empty($covidStats["total_deaths_per_million"]) ? $covidStats["total_deaths_per_million"] : ''; 
					$new_deaths_per_million =  !empty($covidStats["new_deaths_per_million"]) ? $covidStats["new_deaths_per_million"] : ''; 
					$diabetes_prevalence =  !empty($covidStats["diabetes_prevalence"]) ? $covidStats["diabetes_prevalence"] : '';
					$handwashing_facilities =  !empty($covidStats["handwashing_facilities"]) ? $covidStats["handwashing_facilities"] : ''; 
					$hospital_beds_per_thousand =  !empty($covidStats["hospital_beds_per_thousand"]) ? $covidStats["hospital_beds_per_thousand"] : '';
					$life_expectancy =  !empty($covidStats["life_expectancy"]) ? $covidStats["life_expectancy"] : ''; 
					// end covid stats
					
					// save travel restrictions data $countryid
					$sSQL = <<<SQL
					insert into travel_regulations (country_id,date_saved,risk_level,recommendation,tests
					,quarantine,masks,total_cases,new_cases,total_deaths,new_deaths
					,total_cases_per_million,new_cases_per_million,total_deaths_per_million,new_deaths_per_million
					,diabetes_prevalence,handwashing_facilities,hospital_beds_per_thousand,life_expectancy)
					values ($countryid,$curenttime,'$risk_level','$recommendation','$tests','$quarantine','$masks','$total_covid_cases'
					,'$new_cases','$total_deaths','$new_deaths','$total_cases_per_million','$new_cases_per_million','$total_deaths_per_million'
					,'$new_deaths_per_million','$diabetes_prevalence','$handwashing_facilities','$hospital_beds_per_thousand','$life_expectancy')
SQL;
					database::query($sSQL);
					$travel_regulations_id = database::insertId('travel_regulations_id');
					/////
					$data = !empty($trip["data"]) ? $trip["data"] : array() ;
					$health_systems = !empty($data["health_systems"]) ? $data["health_systems"] : array() ;
					// testing policy
					$testing_policy = !empty($health_systems["testing_policy"]) ? $health_systems["testing_policy"] : array() ;
					$testing_policy_notes = !empty($testing_policy["notes"]) ? $testing_policy["notes"] : array() ;
					// this array will be looped to save note as news for testing policy 
					if (!empty($testing_policy_notes))
					{
						foreach ($testing_policy_notes as $value) {
							// save international travel controls news
							$note = !empty($value['note']) ? $value['note'] : '';
							$note = str_replace("'","",$note);
							database::query("insert into testing_policy (restrictions_id, date_saved, note)
								values ($travel_regulations_id, $curenttime, '$note')");
						}
					}
					// testing policy end
					// contact tracing start
					$contact_tracing = !empty($health_systems["contact_tracing"]) ? $health_systems["contact_tracing"] : array();
					$contact_tracing_notes = !empty($contact_tracing["notes"]) ? $contact_tracing["notes"] : array(); // this array will be looped to save note as news for contact tracing
					if (!empty($contact_tracing_notes))
					{
						foreach ($contact_tracing_notes as $value) {
							// save international travel controls news
							$note = !empty($value['note']) ? $value['note'] : '';
							$note = str_replace("'","",$note);
							database::query("insert into contact_tracing (restrictions_id, date_saved, note)
								values ($travel_regulations_id, $curenttime, '$note')");
						}
					}
					// coontamination closure 
					$containment_and_closure = !empty($data["containment_and_closure"]) ? $data["containment_and_closure"] : array();
					// school closing
					$school_closing = !empty($containment_and_closure["school_closing"]) ? $containment_and_closure["school_closing"] : '';
					$school_closing_notes = !empty($school_closing["notes"]) ? $school_closing["notes"] : '';
					// this array will be looped to save note as news for school closing
					if (!empty($school_closing_notes))
					{
						foreach ($school_closing_notes as $value) {
							// save international travel controls news
							$note = !empty($value['note']) ? $value['note'] : '';
							$note = str_replace("'","",$note);
							database::query("insert into school_closing (restrictions_id, date_saved, note)
								values ($travel_regulations_id, $curenttime, '$note')");
						}
					}
					// workplace closing
					$workplace_closing = !empty($containment_and_closure["workplace_closing"]) ? $containment_and_closure["workplace_closing"] : array();
					$workplace_closing_notes = !empty($workplace_closing["notes"]) ? $workplace_closing["notes"] : array(); // this array will be looped to save note as news for workplace closing
					if (!empty($workplace_closing_notes))
					{
						foreach ($workplace_closing_notes as $value) {
							// save workplace_closing_notes news
							$note = !empty($value['note']) ? $value['note'] : '';
							$note = str_replace("'","",$note);
							database::query("insert into workplace_closing (restrictions_id, date_saved, note)
								values ($travel_regulations_id, $curenttime, '$note')");
						}
					}
					// restrictions_on_internal_movement // this array will be looped to save note as news for restrictions_on_internal_movement
					$restrictions_on_internal_movement = !empty($containment_and_closure["restrictions_on_internal_movement"]) ? $containment_and_closure["restrictions_on_internal_movement"] : array();
					$restrictions_on_internal_movement_notes = !empty($restrictions_on_internal_movement["notes"]) ? $restrictions_on_internal_movement["notes"] : array();
					if (!empty($restrictions_on_internal_movement_notes))
					{
						foreach ($restrictions_on_internal_movement_notes as $value) {
							// save international travel controls news
							$note = !empty($value['note']) ? $value['note'] : '';
							$note = str_replace("'","",$note);
							database::query("insert into restrictions_on_internal_movement (restrictions_id, date_saved, note)
								values ($travel_regulations_id, $curenttime, '$note')");
						}
					}
					// international_travel_controls  this array will be looped to save note as news for international_travel_controls
					$international_travel_controls = !empty($containment_and_closure["international_travel_controls"]) ? $containment_and_closure["international_travel_controls"] : array();
					$international_travel_controls_notes = !empty($international_travel_controls["notes"]) ? $international_travel_controls["notes"] : array();
					if (!empty($international_travel_controls_notes))
					{
						foreach ($international_travel_controls_notes as $value) {
							// save international travel controls news
							$note = !empty($value['note']) ? $value['note'] : '';
							$note = str_replace("'","",$note);
							database::query("insert into international_travel_controls (restrictions_id, date_saved, note)
								values ($travel_regulations_id, $curenttime,'$note')");
						}
					}
				}
				else die('not in heref');
			}
		}
	}
	echo $count;
	die;	
?>